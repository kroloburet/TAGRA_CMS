<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер страниц "Контакты"
 *
 * Методы для работы с страницами "Контакты" в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_contact_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_contact_model');
    }

    /**
     * Загрузить шаблон управления страницами
     *
     * @return void
     */
    function get_list()
    {
        $contact_pages = $this->_filter_list('contact_pages');
        $data['contact_pages'] = $contact_pages['result'];
        $data['filter'] = $contact_pages['filter'];
        $data['view_title'] = 'Управление страницами "Контакты"';
        $this->_viewer('back/contact_pages/contact_list_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор страницы
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('contact_pages', $id);
        $data['view_title'] = 'Редактировать страницу "Контакты"';
        $this->_viewer('back/contact_pages/contact_edit_view', $data);
    }

    /**
     * Редактировать страницу
     *
     * Метод принимает данные из post переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @param string $id Идентификатор страницы
     * @return void
     */
    function edit(string $id)
    {
        $p = array_map('trim', $this->input->post());
        $res = $this->back_contact_model->edit_contact_page($id, $p);
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/contact/get_list']
            , JSON_FORCE_OBJECT));
    }
}
