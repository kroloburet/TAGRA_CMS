<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель галерей
 *
 * Методы для работы с галереями в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_gallery_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавить галерею
     *
     * @param array $data Данные
     * @return boolean
     */
    function add_gallery(array $data)
    {
        $res = $this->db->insert('gallerys', $data);
        // добавить связи с материалом в версиях
        $res && $data['versions'] ? $this->set_versions('gallerys', $data) : null;
        return $res;
    }

    /**
     * Редактировать галерею
     *
     * @param string $id Идентификатор галереи
     * @param array $data Данные
     * @return boolean
     */
    function edit_gallery(string $id, array $data)
    {
        $q = $this->db->where('id', $id)->get('gallerys')->result_array(); // изменяемый материал
        if ($q[0]['versions'] !== $data['versions']) {// версии изменились
            $this->set_versions('gallerys', $data, $q[0]); // добавить/обновить связи с материалом в версиях
        }
        return $this->db->update('gallerys', $data, ['id' => $id]);
    }

    /**
     * Удалить галерею
     *
     * Вместе с галереей удалит все комментарии к ней
     *
     * @param string $id Идентификатор галереи
     * @return boolean
     */
    function del_gallery(string $id)
    {
        $url = 'gallery/' . $id;
        // удалить материал и комментарии
        if (
            $this->db->delete('gallerys', ['id' => $id]) === false ||
            $this->db->delete('comments', ['url' => $url]) === false
        ) {
            return false;
        }
        // удалить связи с материалом в версиях
        $this->del_versions('gallerys', '/' . $url);
        return true;
    }
}
