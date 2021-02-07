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
     * Метод удаляет раздел с комментариями и рекурсивно
     * удаляет все дочерние материалы и относящиеся к ним
     * комментарии.
     *
     * @param string $id Идентификатор удаляемого раздела
     * @param string $lang Язык удаляемого раздела
     * @return array Идентификаторы удаленных разделов
     */
    function del_section(string $id, string $lang)
    {
        /**
         * Получить и форматировать данные
         */
        $m = []; // будет содержать отфарматированные данные
        $del = ['sections' => [], 'gallerys' => [], 'pages' => [], 'uri' => []]; // будет содержать id и uri для удаления
        // получить разделы и страницы
        $q['sections'] = $this->db->select('id, section')->get_where('sections', ['lang' => $lang])->result_array();
        $q['gallerys'] = $this->db->select('id, section')->get_where('gallerys', ['lang' => $lang])->result_array();
        $q['pages'] = $this->db->select('id, section')->get_where('pages', ['lang' => $lang])->result_array();
        // форматировать данные разделов
        if (!empty($q['sections'])) {
            foreach ($q['sections'] as $v) {
                $v['type'] = 'sections';
                $v['uri'] = "section/{$v['id']}";
                $m[] = $v;
            }
        }
        // форматировать данные галерей
        if (!empty($q['gallerys'])) {
            foreach ($q['gallerys'] as $v) {
                $v['type'] = 'gallerys';
                $v['uri'] = "section/{$v['id']}";
                $m[] = $v;
            }
        }
        // форматировать данные страниц
        if (!empty($q['pages'])) {
            foreach ($q['pages'] as $v) {
                $v['type'] = 'pages';
                $v['uri'] = "page/{$v['id']}";
                $m[] = $v;
            }
        }
        unset($q);

        /**
         * Рекурсивно получать данные для удаления
         * дочерних материалов и удалять связи с
         * материалом в версиях.
         *
         * @param array $data Отформатированный массив данных
         * @param string $id Идентификатор удаляемого раздела
         */
        $get_del_ids = function (array $data, string $id) use (&$del, &$get_del_ids) {
            foreach ($data as $v) {
                if ($id === $v['section']) {
                    $del[$v['type']][] = $v['id'];
                    $del['uri'][] = $v['uri'];
                    $this->del_versions($v['type'], "/{$v['uri']}");
                    $get_del_ids($data, $v['id']);
                }
            }
        };
        $get_del_ids($m, $id);
        // добавить данные для удаления текущего удоляемого раздела (родителя)
        $del['sections'][] = $id;
        $del['uri'][] = "section/{$id}";
        // удалить связи с текущим (родителем) разделом в версиях
        $this->del_versions('sections', "/section/{$id}");

        /**
         * Удалить дочерние материалы и вернуть id удаленных разделов
         */
        // удалить комментарии дочерних материалов и текущего раздела
        $result['comments'] = $this->db->where(['lang' => $lang])->where_in('url', $del['uri'])->delete('comments');
        // если есть, удалить дочерние галереи дочерних разделов и дочерние галереи текущего раздела
        $result['gallerys'] = !empty($del['gallerys']) ? $this->db->where(['lang' => $lang])->where_in('id', $del['gallerys'])->delete('gallerys') : true;
        // если есть, удалить дочерние страницы дочерних разделов и дочерние страницы текущего раздела
        $result['pages'] = !empty($del['pages']) ? $this->db->where(['lang' => $lang])->where_in('id', $del['pages'])->delete('pages') : true;
        // удалить дочерние разделы и текущий раздел
        $result['sections'] = $this->db->where(['lang' => $lang])->where_in('id', $del['sections'])->delete('sections');
        if ($result['comments'] === false || $result['gallerys'] === false || $result['pages'] === false || $result['sections'] === false) {
            return [];
        }
        return $del['sections'];
    }
}
