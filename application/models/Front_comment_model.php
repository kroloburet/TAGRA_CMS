<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель комментариев
 *
 * Методы для работы с комментариями в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_comment_model extends Front_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавить комментарий
     *
     * @param array $data Данные
     * @return boolean
     */
    function add_comment(array $data = [])
    {
        return $this->db->insert('comments', $data);
    }

    /**
     * Добавить рейтинг
     *
     * @param string $id Идентификатор комментария
     * @param string $rating json данные рейтинга
     * @return boolean
     */
    function add_comment_rating(string $id, string $rating = '')
    {
        return $this->db->update('comments', ['rating' => $rating], ['id' => $id]);
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
     * @global array $ids
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
}
