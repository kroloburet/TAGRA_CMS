<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Front_basic_control.php');

/**
 * Контроллер галерей
 *
 * Методы для работы с галереями в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_gallery_control extends Front_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_gallery_model');
    }

    /**
     * Загрузить шаблон галереи
     *
     * @param string $id Идентификатор галереи
     * @return void
     */
    function get_gallery(string $id)
    {
        $data = $this->front_gallery_model->get_gallery($id);
        $data ? $this->_viewer('front/gallery_view', $data) : redirect('404_override');
    }
}
