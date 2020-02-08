<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель языков
 *
 * Методы для работы с языками в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_language_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Сбросить язык по умолчанию
     *
     * @return boolean
     */
    function reset_def_lang()
    {
        return $this->db->update('languages', ['def' => 0], ['def' => 1]);
    }

    /**
     * Удалить язык
     *
     * Удалит язык и принадлижащие к языку: все материалы, все связи в материалах и комментарии.
     *
     * @param string $tag Тег языка
     * @return boolean
     */
    function del_lang(string $tag)
    {
        $tables = ['index_pages', 'contact_pages', 'pages', 'sections', 'gallerys', 'comments', 'menu'];
        // удалить все материалы принадлежащие языку
        if ($this->db->delete($tables, ['lang' => $tag]) === false) {
            return false;
        }
        // поиск и удаление связей материалов удаляемого языка в версиях
        foreach ($tables as $table) {
            // искать только в этих материалах или к следующей итерации
            if (!in_array($table, ['pages', 'sections', 'gallerys'])) {
                continue;
            }
            // выбрать записи с удаляемым языком в версиях
            $q = $this->db->select('id, versions')->like('versions', '"' . $tag . '"')->get($table)->result_array();
            // если выборка пуста - к следующей итерации
            if (empty($q)) {
                continue;
            }
            // проход по выборке
            foreach ($q as $k => $v) {
                $vers = json_decode($v['versions'], true); // версии в массив
                if (isset($vers[$tag])) {// найден удаляемый язык
                    unset($vers[$tag]); // удалить из версий
                    // версии в json, если не пусто
                    $q[$k]['versions'] = empty($vers) ? '' : json_encode($vers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
            // перезаписать измененные версии в таблице
            !empty($q) ? $this->db->update_batch($table, $q, 'id') : null;
        }
        // удалить язык
        return $this->db->delete('languages', ['tag' => $tag]) !== false ? true : false;
    }
}
