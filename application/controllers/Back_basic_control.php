<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Базовый контроллер
 *
 * Общие методы для работы в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_basic_control extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('back_basic_model');
        // проверка авторизации при обращении к контроллеру
        $this->login();
    }

    /**
     * Получить значение из массива "app"
     *
     * app[] содержит: конфигурацию ресурса, публичные данные пользователей,
     * массив локализации и иные вспомагательные опции общего пользования.
     * Метод реализует удобный доступ к нужному значению передав в него "путь"
     * из ключей через точку.
     * Например: $this->app('conf.langs') === $app['conf']['langs']
     *
     * @param string $path Путь к значению через точку или null чтобы получить весь массив
     * @return mixed Значение из массива, весь массив или null если ключ не найден
     */
    function app(string $path = null)
    {
        // заполнить app[] если пуст
        if (empty($this->config->item('app'))) {
            $this->config->set_item('app', $this->back_basic_model->get_config());
        }
        // вернуть весь массив если путь не передан
        if (!$path || !is_string($path)) {
            return $this->config->item('app');
        }
        // обработать путь и вернуть значение массива
        return array_reduce(explode('.', $path), function ($i, $k) {
            return isset($i[$k]) ? $i[$k] : null;
        }, $this->config->item('app'));
    }

    /**
     * Редактировать массив "app"
     *
     * Добавляет или изменяет значение
     *
     * @param array $data Данные путь=>значение ['conf.langs.ru.title'=>'RU','lexic.basic.home'=>'Домой']
     * @return void|boolean
     */
    function set_app(array $data = [])
    {
        if (empty($data)) {
            return false;
        }
        foreach ($data as $path => $val) {
            $level = &$this->config->config['app'];
            $path_arr = explode('.', $path);
            foreach ($path_arr as $k) {
                if (!key_exists($k, $level) || !is_array($level[$k])) {
                    $level[$k] = [];
                }
                $level = &$level[$k];
            }
            $level = $val;
        }
    }

    /**
     * Авторизация пользователя
     *
     * Метод проверяет логин/пароль пользователя
     * административной части.
     * * При успешной авторизации:
     * * * создать сессию пользователя
     * * * зафиксировать время входа, ip
     * * * задать конфигурацию
     * * При неуспешной авторизации:
     * * * прекратить работу контроллера и вывести форму входа
     *
     * @return void
     */
    function login()
    {
        $data_msg = [];
        $q = $this->back_basic_model->get_back_users();
        /**
         * переданы данные для входа
         */
        if ($this->input->post('lgn') && $this->input->post('pswd')) {
            $p = array_map('trim', $this->input->post());
            // сверить данные каждого пользователя
            foreach ($q as $v) {
                // логин и пароль совпали
                if (password_verify($p['lgn'], $v['login']) && password_verify($p['pswd'], $v['password'])) {
                    // это запрещенный модератор
                    if ($v['status'] === 'moderator' && !$v['access']) {
                        $data_msg['msg'] = '<p class="TUI_notice-r mini TUI_full">'
                            . 'Упс! Администратор запретил вам вход и все действия от имени модератора.</p>';
                        break;
                    }
                    // запретов нет - пропустить
                    $this->session->set_userdata($v['status'], $v['password'] . $v['login']); //стартует сессия
                    $this->back_basic_model->edit_back_user($v['id'], [
                        'last_login_date' => date('Y-m-d H:i:s'),
                        'ip' => $this->input->server('REMOTE_ADDR')
                    ]); // фиксировать дату авторизации, ip
                    break;
                }
                // данные неверны
                $data_msg['msg'] = '<p class="TUI_notice-r mini TUI_full">Нет пользователя с такими данными!</p>';
            }
        }
        /**
         * админ или модератор залогинен
         */
        if (isset($this->session->administrator) || isset($this->session->moderator)) {
            // сверить данные с данными каждого пользователя
            foreach ($q as $v) {
                // это админ или модератор
                if (
                    $this->session->administrator === $v['password'] . $v['login'] ||
                    $this->session->moderator === $v['password'] . $v['login']) {
                    // это запрещенный модератор
                    if ($v['status'] === 'moderator' && !$v['access']) {
                        $data_msg['msg'] = '<p class="TUI_notice-r mini TUI_full">'
                            . 'Упс! Администратор запретил вам вход и все действия от имени модератора.</p>';
                        break;
                    }
                    // запретов нет - пропустить
                    $data = $this->back_basic_model->get_config(); // получить конфигурацию
                    $data['status'] = $v['status'] === 'administrator' ? 'administrator' : 'moderator'; // записать статус
                    $data['admin_mail'] = $this->_get_admin_param('email'); // записать email админа
                    $data['moderator_mail'] = implode(',', array_column(array_filter($q, function ($i) {
                        return $i['status'] == 'moderator' && $i['access'];
                    }), 'email')); // записать email модераторов в строку через запятые
                    $data['langs'] = $this->back_basic_model->get_langs(); // записать все языки системы
                    // записать язык системы по умолчанию
                    foreach ($data['langs'] as $i) {
                        if ($i['def']) {
                            $data['lang_def'] = $i;
                            break;
                        }
                    }
                    $this->set_app(['conf' => $data]); // записать конфигурацию в массив "app"
                    return true;
                }
            }
        }
        /**
         * авторизация неуспешна! прекратить работу контроллера и вывести форму входа
         */
        exit($this->load->view('back/login_view', $data_msg, true));
    }

    /**
     * Выход
     *
     * Удалить все сессии пользователей админки
     * Перенаправить на страницу входа
     *
     * @return void
     */
    function logout()
    {
        $this->session->unset_userdata(['administrator', 'moderator']);
        redirect('admin');
    }

    /**
     * Получить значение поля администратора
     *
     * @param string $param Поле
     * @return mixed Значение поля
     */
    protected function _get_admin_param(string $param)
    {
        $q = $this->db->where('status', 'administrator')->get('back_users')->result_array();
        return $q[0][$param];
    }

    /**
     * Получить значение поля модератора
     *
     * @param string $id_mail Идентификатор или email модератора
     * @param string $param Поле
     * @return mixed|boolean Значение поля или false
     */
    protected function _get_moderator_param(string $id_mail, string $param)
    {
        if (filter_var($id_mail, FILTER_VALIDATE_EMAIL)) {
            // email
            $this->db->where(['email' => $id_mail]);
        } else {
            // id
            $this->db->where(['id' => $id_mail]);
        }
        $this->db->where(['status' => 'moderator']);
        $q = $this->db->get('back_users')->result_array();
        if ($q[0]) {
            return $q[0][$param];
        } else {
            return false;
        }
    }

    /**
     * Получить отфильтрованную выборку таблицы
     *
     * Метод получает выборку таблицы, добавляет/изменяет
     * данные фильтра выборки в сессию фильтра,
     * настраивает пагинацию. Применяется для получения
     * отфильтрованной выборки в методе "get_list()" класса
     * материалов. В результате записи данных фильтра в сессию,
     * настройки фильтра "запоминаются" для каждого материала
     * отдельно.
     *
     * @param string $table Имя таблицы материала
     * @return array Отфильтрованная выборка с данными примененного фильтра
     */
    protected function _filter_list(string $table)
    {
        $filter = $this->input->get('filter') ? $this->input->get('filter') : [];// данные полей фильтра
        // создать сессию фильтра если не создана
        !$this->session->filter ? $this->session->filter = [] : null;
        // определить данные фильтра для запроса в БД и настройки пагинации
        if (!empty($filter)) {
            // есть GET данные фильтра
            // слиять GET данные фильтра с сессией фильтра
            $this->session->set_userdata('filter',
                array_merge($this->session->filter,
                    [$table => $filter]));
        } elseif (isset($this->session->filter[$table])) {
            // GET данных нет но содержатся в сессии фильтра
            // использовать данные фильтра материала
            $filter = $this->session->filter[$table];
        }
        // получить отфильтрованную выборку из таблицы
        $data = $this->back_basic_model->get_list($table, $filter);
        // настроить пагинацию
        $this->load->library('pagination');
        $conf['page_query_string'] = TRUE;// использовать аргументы строки запроса
        $conf['reuse_query_string'] = TRUE;// не игнорировать существующие аргументы строки запроса
        $conf['base_url'] = current_url();// URL после которого идет аргумент пагинации
        $conf['total_rows'] = $data['count_result'];// всего записей в запросе
        $conf['per_page'] = isset($filter['limit'])// количество полей для отображения
            ? ($filter['limit'] === 'all' ? $data['count_result'] : $filter['limit'])
            : $data['count_result'];
        $this->pagination->initialize($conf);
        return $data;
    }

    /**
     * Форматировать данные
     *
     * Метод возвращает отформатировнный массив данных.
     * Используется в основном для post\get данных перед записью в базу.
     *
     * @param array $data Данные
     * @param bool $trim Использовать ли trim() для значений
     * @param string|null $arr_to как обрабатывать вложенные массивы:
     * * null - не обрабатывать (по умолчанию)
     * * 'comma' - значения в строку через запятую
     * * 'json' - значения в строку json
     * @return array|boolean
     */
    protected function _format_data(array $data = [], bool $trim = true, string $arr_to = null)
    {
        if (!is_array($data) || empty($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            if (is_array($v) && $arr_to) {
                $result[$k] = $arr_to == 'comma' ? implode(',', $v) : json_encode($v);
            } else {
                $trim ? $result[$k] = trim($v) : $result[$k] = $v;
            }
        }
        return $result;
    }

    /**
     * Вывод шаблона
     *
     * Метод дополнит массив app['data'] данными материала для view-шаблонов
     * и загрузит шаблоны материала.
     *
     * @param string $path Путь к шаблону материала относительно /application/views/
     * @param array $data Данные материала
     * @return void
     */
    protected function _viewer(string $path, array $data)
    {
        // записать данные в массив app['data'] для использования в хелперах и view-шаблонах
        $this->set_app(['data' => is_array($this->app('data')) ? $this->app('data') + $data : $data]);
        $this->load->view('back/components/header_view', $this->app()); // header
        $this->load->view($path, $this->app()); // body
        $this->load->view('back/components/footer_view', $this->app()); // footer
    }

    /**
     * Выбор языка
     *
     * Применяется в методах загрузки шаблонов добавления материала, меню.
     * Создавая новый материал, если в системе больше одного языка
     * и если язык не передан в GET, метод загружает шаблон выбора языка.
     *
     * @param array $data Данные материала
     * @return boolean
     */
    protected function _lang_selection(array $data)
    {
        $langs = $this->app('conf.langs'); // языки в системе
        $lang = $this->input->get('lang'); // выбраный язык из get
        if (count($langs) === 1) {// в системе один язык
            $this->set_app(['data.lang' => $langs[0]['tag']]);
            return false;
        } elseif (isset($lang) && in_array($lang, array_column($langs, 'tag'))) {// язык выбран, существует в системе
            $this->set_app(['data.lang' => $lang]);
            return false;
        }
        $this->_viewer('back/language_selection_view', $data); // язык не выбран - отдать шаблон выбора языка
        return true;
    }

    /**
     * Проверка на уникальность title в таблице БД
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, проверяет уникальность и выводит строку ответа.
     *
     * @return void
     */
    function check_title()
    {
        $p = $this->input->post();
        $res = $this->back_basic_model->check_title($p['title'], $p['id'], $p['tab']);
        exit($res ? 'found' : 'ok');
    }

    /**
     * Переключение публикации материала
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, переключает публикацию и выводит строку ответа.
     *
     * @return void
     */
    function toggle_public()
    {
        $p = $this->input->post();
        $res = $this->back_basic_model->toggle_public($p['id'], $p['tab']);
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit($res !== false ? $res : 'error');
    }

    /**
     * Загрузить шаблон управления картой сайта (sitemap.xml)
     *
     * @return void
     */
    function sitemap()
    {
        $data['view_title'] = 'Генератор карты сайта';
        $this->_viewer('back/sitemap/sitemap_view', $data);
    }

    /**
     * Задать конфигурацию генератора карты сайта
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @return void
     */
    function set_sitemap_config()
    {
        $conf = json_encode(array_map('trim', $this->input->post()));
        $res = $this->db->update('config', ['value' => $conf], ['name' => 'sitemap']);
        $this->sitemap_generator(); // обновить карту сайта
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/sitemap']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Генератор карты сайта (sitemap.xml)
     *
     * @return void|boolean
     */
    function sitemap_generator()
    {
        // проверка sitemap.xml на доступность для записи
        if (!is_writable(getcwd() . '/sitemap.xml')) {
            return false;
        }
        $pages = $sections = $gallerys = '';
        // только индексируемые
        $where = ['robots !=' => 'none', 'robots !=' => 'noindex'];
        // если включать только опубликованные материалы
        $this->app('conf.sitemap.allowed') === 'public' ? $where['public'] = 1 : null;
        $select = 'id';
        $p = $this->db->where($where)->select($select)->get('pages')->result_array();
        $s = $this->db->where($where)->select($select)->get('sections')->result_array();
        $g = $this->db->where($where)->select($select)->get('gallerys')->result_array();
        // разметка <url>
        if (!empty($p)) {// есть страницы
            foreach ($p as $i) {
                $pages .= '<url><loc>' . base_url('page/' . $i['id']) . '</loc></url>' . PHP_EOL;
            }
        }
        if (!empty($s)) {// есть разделы
            foreach ($s as $i) {
                $sections .= '<url><loc>' . base_url('section/' . $i['id']) . '</loc></url>' . PHP_EOL;
            }
        }
        if (!empty($g)) {// есть галереи
            foreach ($g as $i) {
                $gallerys .= '<url><loc>' . base_url('gallery/' . $i['id']) . '</loc></url>' . PHP_EOL;
            }
        }
        // перезаписать sitemap.xml
        $f = fopen(getcwd() . '/sitemap.xml', 'a'); // открыть файл
        ftruncate($f, 0); // очистить файл
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
            . '<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->' . PHP_EOL
            . '<!-- Generator: Tagra CMS ' . $this->config->item('tagra_version') . ' -->' . PHP_EOL
            . '<!-- Latest update: ' . date('Y-m-d H:i:s') . ' -->' . PHP_EOL
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL
            . '<!-- Home page -->' . PHP_EOL
            . '<url><loc>' . base_url() . '</loc></url>' . PHP_EOL
            . '<!-- Contact page -->' . PHP_EOL
            . '<url><loc>' . base_url('contact') . '</loc></url>' . PHP_EOL
            . '<!-- Pages -->' . PHP_EOL
            . $pages
            . '<!-- Sections -->' . PHP_EOL
            . $sections
            . '<!-- Gallerys -->' . PHP_EOL
            . $gallerys
            . '</urlset>' . PHP_EOL;
        fwrite($f, $sitemap); // записать sitemap.xml
        fclose($f); // закрыть файл
        return true;
    }

    /**
     * Загрузить шаблон управления конфигурацией системы
     *
     * @return void
     */
    function index()
    {
        $data['view_title'] = 'Конфигурация';
        $this->_viewer('back/config_view', $data);
    }
}
