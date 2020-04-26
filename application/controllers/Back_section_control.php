<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер разделов
 *
 * Методы для работы с разделами в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_section_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_section_model');
    }

    /**
     * Загрузить шаблон управления разделами
     *
     * @return void
     */
    function get_list()
    {
        $sections = $this->_filter_list('sections');
        $data['sections'] = $sections['result'];
        $data['filter'] = $sections['filter'];
        $data['view_title'] = 'Управление разделами';
        $this->_viewer('back/sections/sections_list_view', $data);
    }

    /**
     * Загрузить шаблон добавления
     *
     * @return void
     */
    function add_form()
    {
        $data['view_title'] = 'Добавить раздел';
        if ($this->_lang_selection($data)) {
            return false;
        }
        $this->_viewer('back/sections/sections_add_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор раздела
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('sections', $id);
        $data['view_title'] = 'Редактировать раздел';
        $this->_viewer('back/sections/sections_edit_view', $data);
    }

    /**
     * Добавить раздел
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, добавит данные и выведет json ответ.
     *
     * @return void
     */
    function add()
    {
        $res = $this->back_section_model->add_section(array_map('trim', $this->input->post()));
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/section/get_list']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Редактировать раздел
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @param string $id Идентификатор раздела
     * @return void
     */
    function edit(string $id)
    {
        $res = $this->back_section_model->edit_section($id, array_map('trim', $this->input->post()));
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/section/get_list']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Удалить раздел
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит материал и выведет строку ответа.
     *
     * @return void
     */
    function del()
    {
        $p = $this->input->post();
        $res = $this->back_section_model->del_section($p['id']);
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit($res ? 'ok' : 'error');
    }
}
