<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель галерей
 *
 * Методы для работы с галереями в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_gallery_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить галерею
     *
     * @param string $id Идентификатор галереи
     * @return array
     */
    function get_gallery(string $id)
    {
        $q = $this->db->where(['public' => 1, 'id' => $id])->get('gallerys')->result_array();
        return isset($q[0]) ? $q[0] : [];
    }
}
