<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель страниц
 *
 * Методы для работы со страницами в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_page_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавить страницу
     *
     * @param array $data Данные
     * @return boolean
     */
    function add_page(array $data)
    {
        $res = $this->db->insert('pages', $data);
        // добавить связи с материалом в версиях
        $res && $data['versions'] ? $this->set_versions('pages', $data) : null;
        return $res;
    }

    /**
     * Редактировать страницу
     *
     * @param string $id Идентификатор страницы
     * @param array $data Данные
     * @return boolean
     */
    function edit_page(string $id, array $data)
    {
        $q = $this->db->where('id', $id)->get('pages')->result_array(); // изменяемый материал
        if ($q[0]['versions'] !== $data['versions']) { // версии изменились
            $this->set_versions('pages', $data, $q[0]); // добавить/обновить связи с материалом в версиях
        }
        return $this->db->update('pages', $data, ['id' => $id]);
    }

    /**
     * Удалить страницу
     *
     * Вместе со страницей удалит все комментарии к ней
     *
     * @param string $id Идентификатор страницы
     * @return boolean
     */
    function del_page(string $id)
    {
        $url = 'page/' . $id;
        // удалить материал и комментарии
        if (
            $this->db->delete('pages', ['id' => $id]) === false ||
            $this->db->delete('comments', ['url' => $url]) === false
        ) {
            return false;
        }
        // удалить связи с материалом в версиях
        $this->del_versions('pages', '/' . $url);
        return true;
    }
}
