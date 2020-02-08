<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер страницы "Главная"
 *
 * Методы для работы с страницей "Главная" в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_home_control extends Front_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_home_model');
    }

    /**
     * Загрузить шаблон страницы
     *
     * @return void
     */
    function index()
    {
        $data = $this->front_home_model->get_home_page();
        $this->_viewer('front/home_view', $data);
    }
}
