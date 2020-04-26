<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель настроек конфигурации
 *
 * Методы для работы с настройками конфигурации в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_setting_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Редактировать конфигурацию
     *
     * @param array $data Данные
     * @return boolean
     */
    function set_config(array $data)
    {
        foreach ($data as $name => $value) {
            if (!$this->db->update('config', ['name' => $name, 'value' => $value], ['name' => $name])) {
                return false;
            }
        }
        return true;
    }
}
