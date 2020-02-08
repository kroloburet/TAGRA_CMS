<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель страниц
 *
 * Методы для работы со страницами в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_page_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить страницу
     *
     * @param string $id Идентификатор страницы
     * @return array
     */
    function get_page(string $id)
    {
        $q = $this->db->where(['public' => 1, 'id' => $id])->get('pages')->result_array();
        return isset($q[0]) ? $q[0] : [];
    }
}
