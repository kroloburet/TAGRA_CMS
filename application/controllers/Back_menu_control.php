<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер меню
 *
 * Методы для работы с меню в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_menu_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_menu_model');
    }

    /**
     * Получить массив данных для управления меню
     *
     * @param string $lang Тег языка
     * @return array
     */
    protected function _set_data(string $lang)
    {
        $data['lang'] = $lang;
        $data['view_title'] = 'Главное меню сайта';
        $data['menu'] = $this->back_menu_model->get_menu($lang);
        $p = $this->db->where('lang', $lang)->select('title,id,section')->order_by('title')->get('pages')->result_array();
        $s = $this->db->where('lang', $lang)->select('title,id,section')->order_by('title')->get('sections')->result_array();
        $g = $this->db->where('lang', $lang)->select('title,id,section')->order_by('title')->get('gallerys')->result_array();
        $data['materials'] = ['pages' => $p, 'sections' => $s, 'gallerys' => $g];
        return $data;
    }

    /**
     * Загрузить шаблон редактирования
     *
     * @return void
     */
    function edit_form()
    {
        if ($this->_lang_selection(['view_title' => 'Главное меню сайта'])) {
            return false;
        }
        $this->_viewer('back/menu_view', $this->_set_data($this->app('data.lang')));
    }

    /**
     * Добавить пункт меню
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, добавит пункт меню и выведет json строку ответа.
     *
     * @return void
     */
    function add_item()
    {
        $p = array_map('trim', $this->input->post());
        if (!$p || !$this->back_menu_model->add_item($p)) {
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
        $resp = $this->_set_data($p['lang']);
        $resp['html'] = $this->load->view('back/menu_view', ['data' => $resp], true);
        $resp['status'] = 'ok';
        exit(json_encode($resp, JSON_FORCE_OBJECT));
    }

    /**
     * Редактировать пункт меню
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует пункт меню и выведет json строку ответа.
     *
     * @return void
     */
    function edit_item()
    {
        $p = array_map('trim', $this->input->post());
        if (!$p || !$this->back_menu_model->edit_item($p)) {
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
        $resp = $this->_set_data($p['lang']);
        $resp['html'] = $this->load->view('back/menu_view', ['data' => $resp], true);
        $resp['status'] = 'ok';
        exit(json_encode($resp, JSON_FORCE_OBJECT));
    }

    /**
     * Удалить пункт меню
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит пункт меню вместе с
     * дочерними и выведет json строку ответа.
     *
     * @return void
     */
    function del_item()
    {
        $p = $this->input->post();
        if (!$p || !$this->back_menu_model->del_item($p)) {
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
        $resp = $this->_set_data($p['lang']);
        $resp['html'] = $this->load->view('back/menu_view', ['data' => $resp], true);
        $resp['status'] = 'ok';
        exit(json_encode($resp, JSON_FORCE_OBJECT));
    }

    /**
     * Переключить публикацию пункта меню
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, переключит публикацию пункта
     * меню и выведет json строку ответа.
     *
     * @return void
     */
    function public_item()
    {
        $p = $this->input->post();
        if (!$p || !$this->back_menu_model->public_item($p)) {
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
        $resp = $this->_set_data($p['lang']);
        $resp['html'] = $this->load->view('back/menu_view', ['data' => $resp], true);
        $resp['status'] = 'ok';
        exit(json_encode($resp, JSON_FORCE_OBJECT));
    }
}
