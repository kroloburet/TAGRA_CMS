<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Получить заголовок материала
 */
class Id_to_title
{

    protected $material = [];
    protected $limit;

    /**
     *
     * @param string $table Имя таблицы материала в БД
     * @param int $limit Лимит символов для вывода
     */
    function __construct(string $table = 'sections', int $limit = 20)
    {
        $CI = &get_instance();
        $q = $CI->db->select('title,id')->get($table)->result_array();
        $this->limit = $limit;
        if (!empty($q)) {
            foreach ($q as $v) {
                $this->material[$v['id']] = $v['title'];
            }
        }
    }

    /**
     *
     * @param string $id Идентификатор материала
     * @return string Заголовок материала
     */
    function get_title(string $id)
    {
        return isset($this->material[$id])
            ? mb_strimwidth($this->material[$id], 0, $this->limit, '...')
            : '';
    }
}
