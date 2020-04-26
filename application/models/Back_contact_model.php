<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель страниц "Контакты"
 *
 * Методы для работы с страницами "Контакты" в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_contact_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Редактировать страницу "Контакты"
     *
     * @param string $id Идентификатор страницы "Контакты"
     * @param array $data Данные
     * @return boolean
     */
    function edit_contact_page(string $id, array $data)
    {
        return $this->db->update('contact_pages', $data, ['id' => $id]);
    }
}
