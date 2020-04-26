<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер ошибок
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Errors extends Front_basic_control
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Загрузить шаблон ошибки 404
     *
     * @return void
     */
    function error_404()
    {
        $data['robots'] = 'none';
        $data['addthis_follow'] = 0;
        $data['addthis_share'] = 0;
        $data['lang'] = $this->app('conf.user_lang');
        $data['description'] = htmlspecialchars($this->app('lexic.404.title'));
        $data['title'] = $data['description'];
        $this->output->set_status_header('404');
        $this->_viewer('404_view', $data);
    }
}
