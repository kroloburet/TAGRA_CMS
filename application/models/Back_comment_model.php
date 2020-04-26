<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель комментариев
 *
 * Методы для работы с комментариями в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_comment_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить новые (неопубликованые) комментарии
     *
     * @return array
     */
    function get_new()
    {
        return $this->db->where(['premod_code !=' => '', 'public' => 0])->get('comments')->result_array();
    }

    /**
     * Публиковать новый комментарий
     *
     * @param string $code Код премодерации
     * @return boolean
     */
    function public_new(string $code)
    {
        return $this->db->update(
            'comments',
            ['public' => 1, 'creation_date' => date('Y-m-d'), 'premod_code' => ''],
            ['premod_code' => $code, 'public' => 0]
        );
    }

    /**
     * Удалить новый комментарий
     *
     * @param string $code Код премодерации
     * @return boolean
     */
    function del_new(string $code)
    {
        return $this->db->delete('comments', ['premod_code' => $code, 'public' => 0]) !== false ? true : false;
    }

    /**
     * Удалить ветвь комментариев
     *
     * Удалит комментарий вместе с дочерними
     *
     * @param string $id Идентификатор комментария
     * @param string $url URL комментируемого материала
     * @return array|boolean
     */
    function del_branch(string $id, string $url)
    {
        $q = $this->db->where('url', $url)->get('comments')->result_array();
        $ids[] = $id;

        // наполнить массив идентификаторами ветви комментариев
        $get_branch_ids = function (array $arr, string $id) use (&$ids, &$get_branch_ids) {
            foreach ($arr as $v) {
                if ($id === $v['pid']) {
                    $ids[] = $v['id'];
                    $get_branch_ids($arr, $v['id']);
                }
            }
        };

        $get_branch_ids($q, $id);
        return $this->db->where_in('id', $ids)->delete('comments') !== false ? $ids : false;
    }

    /**
     * Задать конфигурацию комментариев
     *
     * @param array $data Данные конфигурации
     * @return boolean
     */
    function set_comments_config(array $data)
    {
        $conf = json_encode(array_map('trim', $data));
        return $this->db->update('config', ['value' => $conf], ['name' => 'comments']);
    }
}
