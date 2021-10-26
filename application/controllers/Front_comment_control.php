<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер комментариев
 *
 * Методы для работы с комментариями в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_comment_control extends Front_basic_control
{

    protected $c_conf = []; // массив настроек комментариев
    protected $domain;
    protected $lexic;

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_comment_model');
        $this->c_conf = $this->app('conf.comments');
        $this->domain = str_replace('www.', '', $this->input->server('HTTP_HOST'));
    }

    /**
     * Отправить уведомление об ответе на комментарий подписчику
     *
     * Метод отправляет уведомление об ответе
     * владельцу комментария если тот указал
     * вместо имени свой email (подписчик).
     *
     * @param array $data Данные комментария
     * @return void|boolean
     */
    protected function _send_feedback(array $data)
    {
        /**
         * проверка разрешений и данных
         */
        if (!$this->c_conf['feedback'] || !$data['public'] || $data['pid'] === '0') {
            return false;
        }
        // получить родительский комментарий
        $q = $this->db->where('id', $data['pid'])->get('comments')->result_array();
        // проверить: разрешена ли подписка на странице, подписан ли комментатор, корректен ли email
        if (!isset($q[0]) || empty($q[0]) || !filter_var($q[0]['name'], FILTER_VALIDATE_EMAIL) || !$q[0]['feedback']) {
            return false;
        }
        /**
         * подготовка и отправка уведомления
         */
        $lexic = $this->lang->load('front_template', in_array($q[0]['lang'], array_column($this->app('conf.langs'), 'tag')) ?
            $q[0]['lang'] : $this->app('conf.lang_def.tag'), true);
        $reply_name = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? explode('@', $data['name'])[0] : $data['name'];
        $msg = '
<html>
    <head>
        <title>' . htmlspecialchars($lexic['comments']['feedback_title']) . $this->domain . '</title>
    </head>
    <body>
        <h2>' . $lexic['comments']['feedback_title'] . $this->domain . '</h2>
        <p style="padding:0;margin:0.5em 0 0 0">
            <b>' . explode('@', $q[0]['name'])[0] . '</b>&nbsp;
            <time style="color:#888">' . $lexic['comments']['published'] . $q[0]['creation_date'] . '</time><br>
            ' . $q[0]['comment'] . '
        </p>
        <p style="padding:0;margin:0.5em 0 0 2em" title="' . htmlspecialchars($lexic['comments']['new']) . '">
            <b><i style="color:green">* </i>' . $reply_name . '</b>&nbsp;
            <time style="color:#888">' . $lexic['comments']['published'] . $data['creation_date'] . '</time><br>
            ' . $data['comment'] . '<br>
            <a href="' . base_url($data['url'] . '#comment_' . $data['id']) . '"
               target="_blank">' . $lexic['comments']['go_to'] . '</a>
        </p>
        <hr>
        ' . $lexic['comments']['unfeedback_msg'] . '<br>
        <a href="' . base_url('do/comment_unfeedback?action=uncomment&pid=' . $q[0]['id'] . '&mail=' . $q[0]['name'] . '&url=' . $data['url'] . '&lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['uncomment_more']) . '">
            ' . $lexic['comments']['uncomment_less'] . '
        </a>&nbsp;|&nbsp;
        <a href="' . base_url('do/comment_unfeedback?action=unpage&pid=' . $q[0]['id'] . '&mail=' . $q[0]['name'] . '&url=' . $data['url'] . '&lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['unpage_more']) . '">
            ' . $lexic['comments']['unpage_less'] . '
        </a>&nbsp;|&nbsp;
        <a href="' . base_url('do/comment_unfeedback?action=unsite&pid=' . $q[0]['id'] . '&mail=' . $q[0]['name'] . '&url=' . $data['url'] . '&lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['unsite_more']) . '">
            ' . $lexic['comments']['unsite_less'] . '
        </a>
    </body>
</html>
';
        $this->load->library('email');
        $this->email->subject(htmlspecialchars($lexic['comments']['feedback_title']) . $this->domain);
        $this->email->from('Robot@' . $this->domain, $this->app('conf.site_name'));
        $this->email->to($q[0]['name']);
        $this->email->message($msg);
        return $this->email->send();
    }

    /**
     * Отправить уведомление администратору и модераторам
     *
     * Метод отправляет комментарий установленным
     * в настройках конфигурации комментариев пользователям
     * административной части (администратору/модераторам)
     * с действиями над комментарием.
     *
     * @param array $data Данные комментария
     * @return void|boolean
     */
    protected function _send_notific(array $data)
    {
        /**
         * подготовка уведомления
         */
        $mail_to = $actions = $parent['html'] = $parent['child_css'] = false;
        // действия быстрого управления комментарием из email (без публикации)
        $no_premod_actions = '
<a href="' . base_url($data['url'] . '#comment_' . $data['id']) . '" target="_blank">Перейти к этому ответу в материале</a>
&nbsp;|&nbsp;
<a href="' . base_url('admin/comment/get_list') . '" target="_blank">Управление комментариями</a>
&nbsp;|&nbsp;
<a href="' . base_url('do/comment_action/del_branch/' . $data['premod_code']) . '" target="_blank">Удалить с дочерней ветвью</a>
';
        // действия быстрого управления комментарием из email (с публикацией)
        $premod_actions = '
<a href="' . base_url('do/comment_action/public/' . $data['premod_code']) . '" target="_blank">Публиковать</a>
&nbsp;|&nbsp;
<a href="' . base_url('do/comment_action/del/' . $data['premod_code']) . '" target="_blank">Удалить</a>
&nbsp;|&nbsp;
<a href="' . base_url('admin/comment/get_list') . '" target="_blank">Управление комментариями</a>
';
        // на чей email отправлять уведомление
        switch ($this->c_conf['notific']) {
            // без премодерации
            case 'site_mail':
                $mail_to = $this->app('conf.site_mail');
                $actions = $no_premod_actions;
                break;
            case 'admin_mail':
                $mail_to = $this->app('conf.admin_mail');
                $actions = $no_premod_actions;
                break;
            case 'moderator_mail':
                $mail_to = $this->app('conf.moderator_mail');
                $actions = $no_premod_actions;
                break;
            // с премодерацией
            case 'premod_site_mail':
                $mail_to = $this->app('conf.site_mail');
                $actions = $premod_actions;
                break;
            case 'premod_admin_mail':
                $mail_to = $this->app('conf.admin_mail');
                $actions = $premod_actions;
                break;
            case 'premod_moderator_mail':
                $mail_to = $this->app('conf.moderator_mail');
                $actions = $premod_actions;
                break;
            // никому не отправлять
            default :
                return;
        }

        // если есть родительский комментарий
        if ($data['pid'] > 0) {
            $q = $this->db->where('id', $data['pid'])->get('comments')->result_array();
            if (isset($q[0]) && !empty($q[0])) {
                $parent['html'] = '
<p style="padding:0;margin:0.5em 0 0 0">
    <b>' . $q[0]['name'] . '</b> <time style="color:#888">опубликован ' . $q[0]['creation_date'] . '</time><br>
    ' . $q[0]['comment'] . '
</p>
';
                $parent['child_css'] = 'padding:0;margin:0.5em 0 0 2em';
            }
        }
        // тело уведомления
        $msg = '
<html>
    <head>
        <title>Новый комментарий на ' . $this->domain . '</title>
    </head>
    <body>
        <h2>Новый комментарий на ' . $this->domain . '</h2>
        IP пользователя: ' . $data['ip'] . '<br>
        Материал: <a href="' . base_url($data['url']) . '" target="_blank">' . base_url($data['url']) . '</a><br>
        ' . $parent['html'] . '
        <p style="' . $parent['child_css'] . '" title="Новый комментарий">
            <b><i style="color:green">* </i>' . $data['name'] . '</b>&nbsp;
            <time style="color:#888">отправлен ' . $data['creation_date'] . '</time><br>
            ' . $data['comment'] . '
        </p>
        <hr>
        ' . $actions . '
    </body>
</html>
';
        /**
         * отправка уведомления
         */
        $this->load->library('email');
        $this->email->subject('Новый комментарий на ' . $this->domain);
        $this->email->from('Robot@' . $this->domain, $this->app('conf.site_name'));
        $this->email->to($mail_to);
        $this->email->message($msg);
        return $this->email->send();
    }

    /**
     * Добавить комментарий
     *
     * Метод принимает данные из post переданные
     * ajax запросом, добавит комментарий в БД,
     * инициирует отправку уведомлений о новом комментарии
     * и выведет json ответ.
     *
     * @return void
     */
    function add_comment()
    {
        /**
         * проверка и подготовка данных
         */
        !$this->input->post() ? exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT)) : null;
        $p = array_map('strip_tags', array_map('trim', $this->input->post()));
        $back_user = $this->app('conf.back_user');
        // если есть зарезервированные имена и комментирует не администратор/модератор
        if ($this->c_conf['reserved_names'] && !$back_user) {
            preg_grep('/^' . $p['name'] . '$/ui', array_map('trim', explode(';', $this->c_conf['reserved_names'])))
                ? exit(json_encode(['status' => 'reserved_name'], JSON_FORCE_OBJECT))
                : null;
        }
        // данные для добавления в БД
        $data = [
            'id' => $p['id'],
            'pid' => $p['pid'],
            'name' => $p['name'],
            'comment' => $p['comment'],
            'url' => $p['url'],
            'creation_date' => date('Y-m-d H:i:s'),
            'ip' => $this->input->server('REMOTE_ADDR'),
            // язык комментируемого материала
            'lang' => $p['lang'],
            // если в имени валидный email - подписка на ответы
            'feedback' => filter_var($p['name'], FILTER_VALIDATE_EMAIL) ? 1 : 0,
            // код премодерации если комментирует не администратор/модератор
            'premod_code' => !$back_user && in_array($this->c_conf['notific'],
                ['premod_site_mail', 'premod_admin_mail', 'premod_moderator_mail']) ? microtime(true) : '',
            // не публиковать если премодерация и комментирует не администратор/модератор
            'public' => $back_user || in_array($this->c_conf['notific'],
                ['off', 'site_mail', 'admin_mail', 'moderator_mail']) ? 1 : 0
        ];
        // запомнить имя комментатора для подстановки в поле формы
        $this->input->set_cookie('comment_name', $p['name'], 0);
        /**
         * добавление в БД и отправка уведомлений
         */
        !$this->front_comment_model->add_comment($data)
            ? exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT))
            : null;
        // если комментирует не администратор/модератор - отправить уведомление администратору/модераторам
        if (!$back_user) {
            $this->_send_notific($data);
        }
        // отправить уведомление подписчику если это ответ на его комментарий
        $this->_send_feedback($data);
        /**
         * публикация комментария
         */
        if (!$data['public']) {
            // публикация после премодерации
            exit(json_encode(['status' => 'premod'], JSON_FORCE_OBJECT));
        } else {
            // публикация немедленно
            $q = $this->db->where('id', $data['id'])->get('comments')->result_array();
            // если комментарий удален
            !isset($q[0]) || empty($q[0]) ? exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT)) : null;
            // загрузить хелпер комментариев
            $this->load->helper('front/comments');
            // передать глобальную конфигурацию комментариев с локальными настройками формы комментирования в материале
            $comm = new comments(array_replace($this->c_conf, ['form' => $p['conf']]));
            // вывести json ответ c html комментария
            exit(json_encode(['status' => 'onpublic', 'html' => $comm->print_comment($q[0])], JSON_FORCE_OBJECT));
        }
    }

    /**
     * Действия над комментарием из email
     *
     * Метод обрабатывает действия над комментарием
     * и загружает шаблон с результатом обработки.
     *
     * @param string $action Действие над комментарием
     * @param string $code Код премодерации комментария
     * @return void
     */
    function comment_action(string $action, string $code)
    {
        $data['msg_class'] = 'TUI_notice-error';
        $data['msg'] = '
Ой! Ошибка..(<br>
Возможно это временные неполадки, попробуйте снова.<br>
<i class="fas fa-spin fa-spinner"></i>&nbsp;завершение сценария...
';
        $q = $this->db->where('premod_code', $code)->get('comments')->result_array();
        if (!isset($q[0]) || empty($q[0]) || ($action !== 'public' && $action !== 'del' && $action !== 'del_branch')) {
            // некорректное действие или нет комментария с этим кодом
            $data['msg_class'] = 'TUI_notice-error';
            $data['msg'] = '
Действие невозможно! Комментарий уже удален или опубликован.<br>
<i class="fas fa-spin fa-spinner"></i>&nbsp;завершение сценария...
';
        } else {
            if ($action === 'public') {
                /**
                 * публиковать комментарий
                 */
                $resp = $this->front_comment_model->public_new($code);
                if ($resp && $this->c_conf['feedback']) {
                    // опубликован и обратная связь разрешена
                    $q[0]['public'] = 1;
                    $q[0]['creation_date'] = date('Y-m-d H:i:s');
                    // отправить уведомление об ответе
                    $this->_send_feedback($q[0]);
                }
                $data['msg_class'] = 'TUI_notice-success';
                $data['msg'] = '
Комментарий успешно опубликован!<br>
<i class="fas fa-spin fa-spinner"></i>&nbsp;завершение сценария...
';
            } elseif ($action === 'del') {
                /**
                 * удалить комментарий
                 */
                if ($this->front_comment_model->del_new($code)) {
                    $data['msg_class'] = 'TUI_notice-success';
                    $data['msg'] = '
Комментарий успешно удален!<br>
<i class="fas fa-spin fa-spinner"></i>&nbsp;завершение сценария...
';
                }
            } elseif ($action === 'del_branch') {
                /**
                 * удалить с ветвью дочерних комментариев
                 */
                if ($this->front_comment_model->del_branch($q[0]['id'], $q[0]['url'])) {
                    $data['msg_class'] = 'TUI_notice-success';
                    $data['msg'] = '
Комментарий и ветвь дочерних комментариев успешно удалены!<br>
<i class="fas fa-spin fa-spinner"></i>&nbsp;завершение сценария...
';
                }
            }
        }
        /**
         * загрузить шаблон с результатом обработки
         */
        $this->load->view('front/do/comment_action_view', $data);
    }

    /**
     * Отписка от обратной связи
     *
     * Метод обрабатывает действия из уведомления
     * подписчику об ответе и загружает шаблон
     * с результатом обработки.
     *
     * @return void
     */
    function comment_unfeedback()
    {
        /**
         * валидация и подготовка данных
         */
        $g = $this->input->get();
        $action = isset($g['action']) && !empty($g['action']) ? $g['action'] : false;
        $pid = isset($g['pid']) && !empty($g['pid']) ? $g['pid'] : false;
        $mail = filter_var(isset($g['mail']) ? $g['mail'] : false, FILTER_VALIDATE_EMAIL);
        $url = filter_var(isset($g['url']) ? $g['url'] : false, FILTER_SANITIZE_URL);
        $lexic = $this->lang->load('front_template',
            isset($g['lang']) && in_array($g['lang'], array_column($this->app('conf.langs'), 'tag')) ?
                $g['lang'] : $this->app('conf.lang_def.tag'), true);
        $reload = '<a href="#" onclick="window.location.reload(true);return false;">' . $lexic['comments']['try_again'] . '</a>';
        $close = '<a href="#" onclick="window.close();return false;">' . $lexic['comments']['close_window'] . '</a>';
        $data['title'] = $lexic['comments']['unfeedback_page_title'];
        $data['msg_class'] = 'TUI_notice-error';
        $data['msg'] = "{$lexic['comments']['data_error']} $close";
        // если данные переданы и корректны
        if ($action && $pid && $mail && $url) {
            switch ($action) {
                /**
                 * отписка от комментария
                 */
                case 'uncomment':
                    $where = ['feedback' => 1, 'id' => $pid, 'name' => $mail, 'url' => $url];
                    $data['msg_class'] = 'TUI_notice-success';
                    $data['msg'] = "{$lexic['comments']['uncomment_ok']} {$lexic['comments']['feedback_again']}<br>$close";
                    break;
                /**
                 * отписка от всех комментариев в материале
                 */
                case 'unpage':
                    $where = ['feedback' => 1, 'name' => $mail, 'url' => $url];
                    $data['msg_class'] = 'TUI_notice-success';
                    $data['msg'] = "{$lexic['comments']['unpage_ok']} {$lexic['comments']['feedback_again']}<br>$close";
                    break;
                /**
                 * отписка от всех комментариев на сайте
                 */
                case 'unsite':
                    $where = ['feedback' => 1, 'name' => $mail];
                    $data['msg_class'] = 'TUI_notice-success';
                    $data['msg'] = "{$lexic['comments']['unsite_ok']} {$lexic['comments']['feedback_again']}<br>$close";
                    break;
                /**
                 * действие не корректное. загрузить шаблон с ошибкой и выйти
                 */
                default :
                    $this->load->view('front/do/comment_unfeedback_view', $data);
                    return;
            }
            // если не удалось редактировать данные
            if (!$this->db->where($where)->update('comments', ['feedback' => 0])) {
                $data['msg_class'] = 'TUI_notice-error';
                $data['msg'] = "{$lexic['basic']['error']}<br>{$reload}&nbsp;|&nbsp;{$close}";
            }
        }
        /**
         * загрузить шаблон с результатом обработки
         */
        $this->load->view('front/do/comment_unfeedback_view', $data);
    }

    /**
     * Изменить рейтинг
     *
     * Метод принимает данные из post переданные
     * ajax запросом, изменит рейтинг материала
     * и выведет json ответ.
     *
     * @return void
     */
    function comment_rating()
    {
        if (!$this->input->post()) {
            $resp['status'] = 'error';
        } else {
            // данные переданы
            $p = array_map('strip_tags', array_map('trim', $this->input->post()));
            $q = $this->db->select('rating')->get_where('comments', ['id' => $p['id']])->result_array();
            if (!isset($q[0])) {
                // нет такого комментария
                $resp['status'] = 'error';
            } else {
                // получить или задать массив значений рейтинга
                $arr = !$q[0]['rating'] ? ['like' => 0, 'dislike' => 0] : json_decode($q[0]['rating'], true);
                // увеличить значение
                $arr[$p['action']]++;
                // записать в БД
                $resp['status'] = $this->front_comment_model->
                add_comment_rating($p['id'], json_encode($arr, JSON_FORCE_OBJECT)) ? 'ok' : 'error';
                // запомнить выбор пользователя для этого комментария
                $resp['status'] === 'ok' ? $this->input->set_cookie($p['hash'], $this->input->server('REMOTE_ADDR'), 0) : null;
                $resp['rating'] = $arr;
            }
        }
        // вывести json ответ
        exit(json_encode($resp, JSON_FORCE_OBJECT));
    }
}
