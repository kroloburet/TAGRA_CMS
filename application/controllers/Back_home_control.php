<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер страниц "Главная"
 *
 * Методы для работы с страницами "Главная" в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_home_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_home_model');
    }

    /**
     * Загрузить шаблон управления страницами
     *
     * @return void
     */
    function get_list()
    {
        $home_pages = $this->_filter_list('index_pages');
        $data['home_pages'] = $home_pages['result'];
        $data['filter'] = $home_pages['filter'];
        $data['view_title'] = 'Управление страницами "Главная"';
        $this->_viewer('back/home_pages/home_list_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор страницы
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('index_pages', $id);
        $data['view_title'] = 'Редактировать страницу "Главная"';
        $this->_viewer('back/home_pages/home_edit_view', $data);
    }

    /**
     * Редактировать страницу
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @param string $id Идентификатор страницы
     * @return void
     */
    function edit(string $id)
    {
        $p = array_map('trim', $this->input->post());
        $res = $this->back_home_model->edit_home_page($id, $p);
        exit(json_encode([
            'status' => $res ? 'ok' : 'error',
            'redirect' => '/admin/home/get_list']
                , JSON_FORCE_OBJECT));
    }
}
