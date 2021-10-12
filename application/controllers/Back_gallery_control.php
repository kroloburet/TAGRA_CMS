<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер галерей
 *
 * Методы для работы с галереями в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_gallery_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_gallery_model');
    }

    /**
     * Загрузить шаблон управления галереями
     *
     * @return void
     */
    function get_list()
    {
        $galleries = $this->_filter_list('galleries');
        $data['galleries'] = $galleries['result'];
        $data['filter'] = $galleries['filter'];
        $data['view_title'] = 'Управление галереями';
        $this->_viewer('back/galleries/galleries_list_view', $data);
    }

    /**
     * Загрузить шаблон добавления
     *
     * @return void
     */
    function add_form()
    {
        $data['view_title'] = 'Добавить галерею';
        if ($this->_lang_selection($data)) {
            return false;
        }
        $this->_viewer('back/galleries/galleries_add_view', $data);
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @param string $id Идентификатор галереи
     * @return void
     */
    function edit_form(string $id)
    {
        $data = $this->back_basic_model->get_where_id('galleries', $id);
        $data['view_title'] = 'Редактировать галерею';
        $this->_viewer('back/galleries/galleries_edit_view', $data);
    }

    /**
     * Добавить галерею
     *
     * Метод принимает данные из post переданные
     * ajax запросом, добавит данные и выведет json ответ.
     *
     * @return void
     */
    function add()
    {
        $res = $this->back_gallery_model->add_gallery(array_map('trim', $this->input->post()));
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/gallery/get_list']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Редактировать галерею
     *
     * Метод принимает данные из post переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @param string $id Идентификатор галереи
     * @return void
     */
    function edit(string $id)
    {
        $res = $this->back_gallery_model->edit_gallery($id, array_map('trim', $this->input->post()));
        exit(json_encode([
                'status' => $res ? 'ok' : 'error',
                'redirect' => '/admin/gallery/get_list']
            , JSON_FORCE_OBJECT));
    }

    /**
     * Удалить галерею
     *
     * Метод принимает данные из post переданные
     * ajax запросом, удалит материал и выведет строку ответа.
     *
     * @return void
     */
    function del()
    {
        $p = $this->input->post();
        $res = $this->back_gallery_model->del_gallery($p['id']);
        $this->app('conf.sitemap.generate') === 'auto' ? $this->sitemap_generator() : null;
        exit($res ? 'ok' : 'error');
    }
}
