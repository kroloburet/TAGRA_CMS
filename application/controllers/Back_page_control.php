<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер страниц
 *
 * Методы для работы со страницами в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_page_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_page_model');
    }

    /**
     * Загрузить шаблон управления страницами
     *
     * @return void
     */
    function get_list()
    {
        $pages = $this->_filter_list('pages');
        $data['pages'] = $pages['result'];
        $data['filter'] = $pages['filter'];
        $data['view_title'] = 'Управление страницами';
        $this->_viewer('back/pages/pages_list_view', $data);
    }

    /**
     * Загрузить шаблон добавления
     *
     * @return void
     */
    function add_form()
    {
        $data['view_title'] = 'Добавить страницу';
        if ($this->_lang_selection($data)) {
            return false;
        }
        $this->_viewer('back/pages/pages_add_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор галереи
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('pages', $id);
        $data['view_title'] = 'Редактировать страницу';
        $this->_viewer('back/pages/pages_edit_view', $data);
    }

    /**
     * Добавить страницу
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, добавит данные и выведет json ответ.
     *
     * @return void
     */
    function add()
    {
        $res = $this->back_page_model->add_page(array_map('trim', $this->input->post()));
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit(json_encode([
            'status' => $res ? 'ok' : 'error',
            'redirect' => '/admin/page/get_list']
                , JSON_FORCE_OBJECT));
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
        $res = $this->back_page_model->edit_page($id, array_map('trim', $this->input->post()));
        exit(json_encode([
            'status' => $res ? 'ok' : 'error',
            'redirect' => '/admin/page/get_list']
                , JSON_FORCE_OBJECT));
    }

    /**
     * Удалить страницу
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит материал и выведет строку ответа.
     *
     * @return void
     */
    function del()
    {
        $p = $this->input->post();
        $res = $this->back_page_model->del_page($p['id']);
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit($res ? 'ok' : 'error');
    }
}
