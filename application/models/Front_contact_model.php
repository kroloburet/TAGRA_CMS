<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель страниц "Контакты"
 *
 * Методы для работы с страницами "Контакты" в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_contact_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить страницу "Контакты"
     *
     * @return array
     */
    function get_contact_page()
    {
        $q = $this->db->where('lang', $this->app('conf.user_lang'))->get('contact_pages')->result_array();
        return isset($q[0]) ? $q[0] : [];
    }
}
