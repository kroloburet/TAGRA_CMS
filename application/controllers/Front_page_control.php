<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер страниц
 *
 * Методы для работы со страницами в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_page_control extends Front_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_page_model');
    }

    /**
     * Загрузить шаблон страницы
     *
     * @param string $id Идентификатор страницы
     * @return void
     */
    function get_page(string $id)
    {
        $data = $this->front_page_model->get_page($id);
        $data ? $this->_viewer('front/page_view', $data) : redirect('404_override');
    }
}
