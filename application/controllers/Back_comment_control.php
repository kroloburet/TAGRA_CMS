<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер комментариев
 *
 * Методы для работы с комментариями в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_comment_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_comment_model');
        $this->c_conf = $this->app('conf.comments');
        $this->domen = str_replace('www.', '', $this->input->server('HTTP_HOST'));
    }

    /**
     * Загрузить шаблон управления комментариями
     *
     * @return void
     */
    function get_list()
    {
        $comments = $this->_filter_list('comments');
        $data['conf'] = $this->c_conf;
        $data['new_comments'] = $this->back_comment_model->get_new();
        $data['comments'] = $comments['result'];
        $data['filter'] = $comments['filter'];
        $data['view_title'] = 'Управление комментариями';
        $this->_viewer('back/comments/comments_list_view', $data);
    }

    /**
     * Удалить ветвь комментариев
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит комментарий вместе с
     * дочерними и выведет json ответ.
     *
     * @return void
     */
    function del_branch()
    {
        $p = $this->input->post();
        !$p || !$p['id'] || !$p['url'] ? exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT)) : null;
        $ids = $this->back_comment_model->del_branch($p['id'], $p['url']);
        $ids
            ? exit(json_encode(['status' => 'ok', 'ids' => $ids], JSON_FORCE_OBJECT))
            : exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
    }

    /**
     * Удалить новый комментарий
     *
     * @param string $code Код премодерации
     * @return void
     */
    function del_new(string $code)
    {
        $this->back_comment_model->del_new($code);
        redirect('admin/comment/get_list');
    }

    /**
     * Публиковать новый комментарий
     *
     * @param string $code Код премодерации
     * @return void
     */
    function public_new(string $code)
    {
        $q = $this->db->where('premod_code', $code)->get('comments')->result_array();
        // если комментарий существует и не опубликован
        if (isset($q[0]) && !empty($q[0])) {
            // если опубликован и обратная связь разрешена
            if ($this->back_comment_model->public_new($code) && $this->c_conf['feedback']) {
                // отправить уведомление об ответе
                $this->_send_feedback($q[0]);
            }
        }
        redirect('admin/comment/get_list');
    }

    /**
     * Вывести количество новых (неопубликованых) комментариев
     *
     * @return void
     */
    function get_count_new()
    {
        echo count($this->back_comment_model->get_new());
    }

    /**
     * Задать конфигурацию комментариев
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @return void
     */
    function set_comments_config()
    {
        $p = $this->input->post();
        $res = $this->back_comment_model->set_comments_config($p);
        exit(json_encode(['status' => $res ? 'ok' : 'error'], JSON_FORCE_OBJECT));
    }

    /**
     * Отправить уведомление об ответе на комментарий подписчику
     *
     * Метод отправляет уведомление об ответе
     * владельцу комментария если тот указал
     * вместо имени свой email (подписчик).
     *
     * @param array $data Данные комментария
     * @return boolean
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
        <title>' . htmlspecialchars($lexic['comments']['feedback_title']) . $this->domen . '</title>
    </head>
    <body>
        <h2>' . $lexic['comments']['feedback_title'] . $this->domen . '</h2>
        <p style="padding:0;margin:0.5em 0 0 0">
            <b>' . explode('@', $q[0]['name'])[0] . '</b>&nbsp;
            <time style="color:#888">' . $lexic['comments']['published'] . $q[0]['creation_date'] . '</time><br>
            ' . $q[0]['comment'] . '
        </p>
        <p style="padding:0;margin:0.5em 0 0 2em" title="' . htmlspecialchars($lexic['comments']['new']) . '">
            <b><i style="color:green">* </i>$reply_name</b>&nbsp;
            <time style="color:#888">' . $lexic['comments']['published'] . $data['creation_date'] . '</time><br>
            ' . $data['comment'] . '<br>
            <a href="' . base_url($data['url'] . '#comment_' . $data['id']) . '"
                target="_blank">' . $lexic['comments']['go_to'] . '</a>
        </p>
        <hr>
        ' . $lexic['comments']['unfeedback_msg'] . '<br>
        <a href="' . base_url('do/comment_unfeedback?
            action=uncomment
            &pid=' . $q[0]['id'] . '
            &mail=' . $q[0]['name'] . '
            &url=' . $data['url'] . '
            &lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['uncomment_more']) . '">
            ' . $lexic['comments']['uncomment_less'] . '
        </a>&nbsp;|&nbsp;
        <a href="' . base_url('do/comment_unfeedback?
            action=unpage
            &pid=' . $q[0]['id'] . '
            &mail=' . $q[0]['name'] . '
            &url=' . $data['url'] . '
            &lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['unpage_more']) . '">
            ' . $lexic['comments']['unpage_less'] . '
        </a>&nbsp;|&nbsp;
        <a href="' . base_url('do/comment_unfeedback?
            action=unsite
            &pid=' . $q[0]['id'] . '
            &mail=' . $q[0]['name'] . '
            &url=' . $data['url'] . '
            &lang=' . $data['lang']) . '"
            target="_blank" title="' . htmlspecialchars($lexic['comments']['unsite_more']) . '">
            ' . $lexic['comments']['unsite_less'] . '
        </a>
    </body>
</html>
';
        $this->load->library('email');
        $this->email->subject(htmlspecialchars($lexic['comments']['feedback_title']) . $this->domen);
        $this->email->from('Robot@' . $this->domen, $this->app('conf.site_name'));
        $this->email->to($q[0]['name']);
        $this->email->message($msg);
        return $this->email->send();
    }
}
