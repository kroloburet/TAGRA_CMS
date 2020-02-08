<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель страниц "Главная"
 *
 * Методы для работы с страницами "Главная" в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_home_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить страницу "Главная"
     *
     * @return array
     */
    function get_home_page()
    {
        $q = $this->db->where('lang', $this->app('conf.user_lang'))->get('index_pages')->result_array();
        return isset($q[0]) ? $q[0] : [];
    }
}
