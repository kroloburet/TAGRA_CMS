<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель страниц "Главная"
 *
 * Методы для работы с страницами "Главная" в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_home_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Редактировать страницу "Главная"
     *
     * @param string $id Идентификатор страницы "Главная"
     * @param array $data Данные
     * @return bollean
     */
    function edit_home_page(string $id, array $data)
    {
        return $this->db->update('index_pages', $data, ['id' => $id]);
    }
}
