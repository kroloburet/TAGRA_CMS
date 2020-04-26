<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер языков
 *
 * Методы для работы с языками в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_language_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_language_model');
    }

    /**
     * Добавить/редактировать язык
     *
     * Метод изменяет язык по умолчанию если языком по умолчанию
     * назначен другой язык, добавляет или редактирует данные языка в БД
     *
     * @param array $data Данные языка
     * @param string $action 'add'||'edit' действие с данными в БД
     * @return boolean
     */
    protected function _set_lang(array $data, string $action)
    {
        /**
         * переопределить язык по умолчанию
         */
        if (isset($data['def']) && $data['def']) {// если переопределен
            // сбросить язык по умолчанию в БД
            if (!$this->back_language_model->reset_def_lang()) {
                return false;
            }
        }
        /**
         * добавить язык
         */
        if ($action === 'add') {
            // добавить язык в БД
            if (!$this->back_basic_model->add($data, 'languages')) {
                return false;
            }
            // создать каталог в /application/language и /upload
            if (!$this->_add_lang_dir($data['tag'])) {
                return false;
            }
            // добавить страницы "Главная" и "Контакты"
            $page = [
                'creation_date' => date('Y-m-d'),
                'layout_l_width' => $this->app('conf.layout_l_width'),
                'addthis_share' => $this->app('conf.addthis.share_def'),
                'addthis_follow' => $this->app('conf.addthis.follow_def'),
                'img_prev' => $this->app('conf.img_prev_def'),
                'lang' => $data['tag']
            ];
            if (
                !$this->back_basic_model->add(
                    $page + ['title' => 'Привет, Мир!', 'description' => 'Привет, Мир!'], 'index_pages') ||
                !$this->back_basic_model->add(
                    $page + ['title' => 'Контакты', 'description' => 'Контакты'], 'contact_pages')
            ) {
                return false;
            }
            /**
             * редактировать язык
             */
        } elseif ($action === 'edit') {
            if (!$this->back_basic_model->edit($data['id'], $data, 'languages')) {
                return false;
            }
            /**
             * недопустимое действие
             */
        } else {
            return false;
        }
        return true;
    }

    /**
     * Создать каталоги языка
     *
     * Метод создает каталог языка для загрузки медиа
     * и каталог для файлов локализации на основе файлов
     * локализации языка по умолчанию.
     *
     * @param string $tag Тег языка
     * @return boolean
     */
    protected function _add_lang_dir(string $tag)
    {
        /**
         * создать каталог в /application/language
         */
        $def_tag = $this->app('conf.lang_def.tag');
        $def_lang_dir = APPPATH . "language/$def_tag/";
        $new_lang_dir = APPPATH . "language/$tag/";
        $d = opendir($def_lang_dir);
        if (!$d) {
            return false;
        }
        // создать новый каталог языка
        if (!mkdir($new_lang_dir, 0755)) {
            return false;
        }
        // копировать все файлы в новый каталог языка
        while ($f = readdir($d)) {
            if ($f != '.' && $f != '..') {
                if (!file_exists($new_lang_dir . $f)) {
                    if (!copy($def_lang_dir . $f, $new_lang_dir . $f)) {
                        return false;
                    }
                }
            }
        }
        closedir($d);
        /**
         * создать каталог в /upload
         */
        if (!mkdir(getcwd() . "/upload/$tag", 0755)) {
            return false;
        }
        return true;
    }

    /**
     * Удалить каталоги языка
     *
     * @param string $tag Тег языка
     * @return boolean
     */
    protected function _del_lang_dir(string $tag)
    {

        /**
         * рекурсивное удаление каталога со всем содержимым
         */
        function _rrd(string $dir)
        {
            if (!file_exists($dir)) {
                return true;
            }
            $objs = glob("$dir/*");
            if ($objs) {
                foreach ($objs as $v) {
                    is_dir($v) ? _rrd($v) : unlink($v);
                }
            }
            return rmdir($dir);
        }

        // удалить каталоги с содержимым
        if (!_rrd(APPPATH . "language/$tag") || !_rrd(getcwd() . "/upload/$tag")) {
            return false;
        }
        return true;
    }

    /**
     * Получить содержимое файла локализации
     *
     * Метод принимает данные из POST переданные
     * ajax запросом и возвращает в строке ответа
     * содержимое файла локализации.
     *
     * @return void
     */
    function get_localization_file()
    {
        $p = array_map('trim', $this->input->post());
        echo !file_exists($p['path']) ? 'error' : file_get_contents($p['path']);
    }

    /**
     * Валидировать и сохранить содержимое файла локализации
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, валидирует и сохраняет содержимое
     * файла если валидация успешна.
     * Возвращает в json строке ответа содержимое файла
     * и сообщение об ошибках синтаксиса, если они есть.
     *
     * @return void
     */
    function save_localization_file()
    {
        $res['status'] = 'error';
        $p = $this->input->post();
        $i = 1; //счетчик строк
        // разрешенные лексемы
        $T_allow = [
            'T_INLINE_HTML',
            'T_OPEN_TAG',
            'T_COMMENT',
            'T_WHITESPACE',
            'T_VARIABLE',
            'T_CONSTANT_ENCAPSED_STRING',
            'T_DOUBLE_ARROW',
            'T_ARRAY',
            'T_ENCAPSED_AND_WHITESPACE',
            'T_CURLY_OPEN',
            'T_DOLLAR_OPEN_CURLY_BRACES',
            'T_STRING_VARNAME',
            'T_NUM_STRING',
            'T_LNUMBER',
            'T_DNUMBER'
        ];
        // разрешенные символы
        $str_allow = [';', '.', ',', '"', '\'', '[', ']', '(', ')', '{', '}', '='];
        !$p['path'] || !$p['text'] ? exit(json_encode($res)) : null;
        /**
         * валидация содержимого файла локализации
         */
        foreach (token_get_all($p['text']) as $v) {
            if (is_array($v)) {
                $t = token_name($v[0]);
                // не пробельный символ до <?php или запрещенная лексема
                if (($t === 'T_INLINE_HTML' && preg_match('/\S/', $v[1])) || !in_array($t, $T_allow)) {
                    $res['msg'] = "Недопустимый символ <mark>$v[1]</mark> в строке $i";
                    exit(json_encode($res));
                }
                // номер следующей строки
                $i += ($t !== 'T_ENCAPSED_AND_WHITESPACE' || $t !== 'T_CONSTANT_ENCAPSED_STRING') ? substr_count($v[1], "\n") : 1;
                // запрещенный символ
            } elseif (!in_array($v, $str_allow)) {
                $res['msg'] = "Недопустимый символ <mark>$v</mark> в строке $i";
                exit(json_encode($res));
            }
        }
        /**
         * валидация успешна, перезаписать файл
         */
        if (!file_exists($p['path']) || !file_put_contents($p['path'], $p['text'], LOCK_EX)) {
            $res['msg'] = "Файл не существует или не может быть перезаписан";
            exit(json_encode($res));
        }
        $res['status'] = 'ok';
        exit(json_encode($res));
    }

    /**
     * Загрузить шаблон управления языками
     *
     * @return void
     */
    function get_list()
    {
        $langs = $this->_filter_list('languages');
        $data['langs'] = $langs['result'];
        $data['filter'] = $langs['filter'];
        $data['view_title'] = 'Управление языками';
        $this->_viewer('back/languages/languages_list_view', $data);
    }

    /**
     * Загрузить шаблон добавления
     *
     * @return void
     */
    function add_form()
    {
        $data['added_tags'] = json_encode(array_column($this->app('conf.langs'), 'tag'));
        $data['view_title'] = 'Добавить язык';
        $this->_viewer('back/languages/languages_add_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор языка
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('languages', $id);
        $data['view_title'] = 'Редактировать язык';
        $this->_viewer('back/languages/languages_edit_view', $data);
    }

    /**
     * Добавить язык
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, добавит данные и выведет json ответ.
     *
     * @return void
     */
    function add()
    {
        $p = array_map('trim', $this->input->post());
        $res = $this->_set_lang($p, 'add');
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                // направить редактировать файлы локализации
                'redirect' => '/admin/language/edit_form/' . $p['id']]
            , JSON_FORCE_OBJECT));
    }

    /**
     * Редактировать язык
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @return void
     */
    function edit()
    {
        $p = array_map('trim', $this->input->post());
        $res = $this->_set_lang($p, 'edit');
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/language/get_list']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Удалить язык
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит: язык, каталоги языка
     * с их содержимым, все материалы и комментарии
     * пренадлежащие этому языку.
     * Выведет строку ответа.
     *
     * @return void
     */
    function del()
    {
        $p = $this->input->post();
        // нельзя удалять текущий язык по умолчанию
        !$this->db->get_where('languages', ['tag' => $p['tag'], 'def !=' => 1])->result() ? exit('def_lang_error') : null;
        // удалить каталоги языка
        !$this->_del_lang_dir($p['tag']) ? exit('dir_lang_error') : null;
        // удалить язык с дочерними материалами из БД
        !$this->back_language_model->del_lang($p['tag']) ? exit('db_lang_error') : null;
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit('ok');
    }
}
