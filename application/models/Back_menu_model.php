<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Модель меню
 *
 * Методы для работы с меню в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_menu_model extends Back_basic_model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить меню
     *
     * Возвращает многомерный массив (дерево) пунктов меню переданного языка
     *
     * @param string $lang Тег языка
     * @return array
     */
    function get_menu(string $lang)
    {
        $q = $this->db->where('lang', $lang)->order_by('order')->get('menu')->result_array();
        if (empty($q)) {
            return [];
        }

        // выборку в многомерный массив (дерево)
        function maketree(array $input, string $pid = '0')
        {
            $output = []; // будет содержать результирующий массив
            // обход входного массива
            foreach ($input as $n => $v) {
                // родитель равен запрашиваемому
                if ($v['pid'] == $pid) {
                    $bufer = $v; // записать в буфер
                    unset($input[$n]); // удалить записанный элемент из входного массива
                    $nodes = maketree($input, $v['id']); // рекурсивно выбрать дочерние элементы
                    // есть дочерние - записать в буфер
                    if (count($nodes) > 0) {
                        $bufer['nodes'] = $nodes;
                    }
                    $output[] = $bufer; // записать буфер в результирующий массив
                }
            }
            return $output;
        }

        return maketree($q);
    }

    /**
     * Добавить пункт меню
     *
     * Встраивает пункт в ветку меняя порядок соседних пунктов
     *
     * @param array $data Данные пункта
     * @return int|boolean Число обновленных записей или false
     */
    function add_item(array $data)
    {
        // выбрать пункты того же родителя, порядок которых больше или равен добавляемого пункта
        $q = $this->db->
        where(['lang' => $data['lang'], 'pid' => $data['pid'], 'order >=' => $data['order']])->
        get('menu')->result_array();
        $ids = []; // массив id с новым порядком для изменения
        if (!empty($q)) {
            foreach ($q as $v) {
                $ids[] = ['id' => $v['id'], 'order' => $v['order'] + 1];
            }
        }
        return (
        $this->db->insert('menu', $data) &&
        empty($ids) ? true : $this->db->update_batch('menu', $ids, 'id')
        );
    }

    /**
     * Редактировать пункт меню
     *
     * Если изменился порядок пункта - перестраивает порядок соседних пунктов
     *
     * @param array $data Данные пункта
     * @return int|boolean Число обновленных записей или false
     */
    function edit_item(array $data)
    {
        $q_ = $this->db->where('lang', $data['lang'])->get('menu')->result_array();
        // формат выборки
        foreach ($q_ as $k => $v) {
            $q[$v['id']] = $v;
        }
        unset($q_);
        $id = $data['id']; // пункт который изменяется
        $old_ord = $q[$id]['order']; // старое место
        $old_pid = $q[$id]['pid']; // старый родитель
        $new_ord = $data['order']; // новое место
        $new_pid = $data['pid']; // новый родитель
        // пункт перемещается - удалить из старого места, вставить в новое
        if ($new_ord !== $old_ord || $new_pid !== $old_pid) {
            // пересчитать порядок (удаление)
            foreach ($q as $k => $v) {
                // не обрабатывать изменяемый пункт
                if ($v['id'] === $id) {
                    continue;
                }
                // в группе старого родителя, изменить порядок
                if ($v['pid'] === $old_pid && $v['order'] > $old_ord) {
                    $q[$k]['order'] = $v['order'] - 1;
                }
            }
            // пересчитать порядок (вставка)
            foreach ($q as $k => $v) {
                // не обрабатывать изменяемый пункт
                if ($v['id'] === $id) {
                    continue;
                }
                // в группе нового родителя, изменить порядок
                if ($v['pid'] === $new_pid && $v['order'] >= $new_ord) {
                    $q[$k]['order'] = $v['order'] + 1;
                }
            }
        }
        $q[$id] = $data; // перезаписать данные изменяемого пункта
        return $this->db->update_batch('menu', $q, 'id') !== false ? true : false;
    }

    /**
     * Удалить пункт меню
     *
     * Вместе с пунктом удалит всю его дочернюю ветку,
     * изменит порядок соседних пунктов
     *
     * @param array $data Данные пункта
     * @return int|boolean Число обновленных записей или false
     */
    function del_item(array $data)
    {
        $q = $this->db->where('lang', $data['lang'])->get('menu')->result_array();
        $ids['del'][] = $data['id']; // массив id для удаления
        $ids['decrement'] = []; // массив id с новым порядком для изменения
        // рекурсивный сбор id пунктов ветки
        $get_del_ids = function ($arr, $id) use (&$ids, &$get_del_ids) {
            foreach ($arr as $v) {
                if ($id === $v['pid']) {
                    $ids['del'][] = $v['id'];
                    $get_del_ids($arr, $v['id']);
                }
            }
        };
        $get_del_ids($q, $data['id']);
        foreach ($q as $v) {// сбор id с новым порядком
            // того же родителя, порядок которых больше удаляемого пункта
            if ($data['pid'] === $v['pid'] && $data['order'] < $v['order']) {
                $ids['decrement'][] = ['id' => $v['id'], 'order' => $v['order'] - 1];
            }
        }
        return (
        $this->db->where_in('id', $ids['del'])->delete('menu') !== false &&
        empty($ids['decrement']) ? true : $this->db->update_batch('menu', $ids['decrement'], 'id')
        );
    }

    /**
     * Переключить публикацию пункта меню
     *
     * Инвертирует значение поля "public"
     *
     * @param array $data Данные пункта
     * @return boolean
     */
    function public_item(array $data)
    {
        return $this->db->update('menu', ['public' => $data['public'] ? 0 : 1], ['id' => $data['id']]);
    }
}
