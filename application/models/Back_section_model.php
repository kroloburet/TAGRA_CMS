<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель разделов
 *
 * Методы для работы с разделами в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_section_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавить раздел
     *
     * @param array $data Данные
     * @return boolean
     */
    function add_section(array $data)
    {
        $res = $this->db->insert('sections', $data);
        // добавить связи с материалом в версиях
        $res && $data['versions'] ? $this->set_versions('sections', $data) : null;
        return $res;
    }

    /**
     * Редактировать раздел
     *
     * @param string $id Идентификатор раздела
     * @param array $data Данные
     * @return boolean
     */
    function edit_section(string $id, array $data)
    {
        $q = $this->db->where('id', $id)->get('sections')->result_array(); // изменяемый материал
        if ($q[0]['versions'] !== $data['versions']) {// версии изменились
            $this->set_versions('sections', $data, $q[0]); // добавить/обновить связи с материалом в версиях
        }
        return $this->db->update('sections', $data, ['id' => $id]);
    }

    /**
     * Удалить раздел
     *
     * Удаляя раздел также удалит дочерние материалы
     * этого раздела и комментарии раздела.
     *
     * @param string $id Идентификатор раздела
     * @return boolean
     */
    function del_section(string $id)
    {
        $url = 'section/' . $id;

        // получить url дочерних материалов для удаления комментариев
        $urls[] = $url;
        $ids['p'] = $this->db->select('id')->get_where('pages', ['section' => $id]);
        $ids['s'] = $this->db->select('id')->get_where('sections', ['section' => $id]);
        $ids['g'] = $this->db->select('id')->get_where('gallerys', ['section' => $id]);
        // url дочерних страниц
        foreach ($ids['p'] as $material_id) {
            $urls[] = 'page/' . $material_id;
        }
        // url дочерних разделов
        foreach ($ids['s'] as $material_id) {
            $urls[] = 'section/' . $material_id;
        }
        // url дочерних галерей
        foreach ($ids['g'] as $material_id) {
            $urls[] = 'gallery/' . $material_id;
        }

        // удалить материал, дочерние материалы и комментарии
        if (
            $this->db->delete('sections', ['id' => $id]) === false ||
            $this->db->delete(['sections', 'pages', 'gallerys'], ['section' => $id]) === false ||
            $this->db->where_in('url', $urls)->delete('comments') === false
        ) {
            return false;
        }
        // удалить связи с материалом в версиях
        $this->del_versions('sections', '/' . $url);
        return true;
    }
}
