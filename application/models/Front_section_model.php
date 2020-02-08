<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель разделов
 *
 * Методы для работы с разделами в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_section_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить раздел
     *
     * @param string $id Идентификатор раздела
     * @return array
     */
    function get_section(string $id)
    {
        $q = $this->db->where(['public' => 1, 'id' => $id])->get('sections')->result_array();
        return isset($q[0]) ? $q[0] : [];
    }

    /**
     * Получить дочерние разделы
     *
     * @param string $id Идентификатор раздела
     * @return array
     */
    function get_sub_sections(string $id)
    {
        $q = $this->db->where(['public' => 1, 'section' => $id])->get('sections')->result_array();
        return !empty($q) ? $q : [];
    }

    /**
     * Получить дочерние галереи
     *
     * @param string $id Идентификатор раздела
     * @return array
     */
    function get_sub_gallerys(string $id)
    {
        $q = $this->db->where(['public' => 1, 'section' => $id])->get('gallerys')->result_array();
        return !empty($q) ? $q : [];
    }

    /**
     * Получить доченрние страницы
     *
     * @param string $id Идентификатор раздела
     * @return array
     */
    function get_sub_pages(string $id)
    {
        $q = $this->db->where(['public' => 1, 'section' => $id])->get('pages')->result_array();
        return !empty($q) ? $q : [];
    }
}
