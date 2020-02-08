<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Класс вывода дерева разделов в выпадающем списке
 */
class Select_section
{

    private $CI;
    private $data;
    private $q;
    private $i;
    private $s;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->data = $this->CI->app('data');
        $this->i = isset($this->data['id']) ? $this->data['id'] : false;
        $this->s = isset($this->data['section']) ? $this->data['section'] : false;
        $lang = $this->data['lang'];
        $this->q = $this->CI->db->where('lang', $lang)->select('title,id,section')->get('sections')->result_array();
        // вывод
        $this->get_select();
    }

    /**
     * Вывести выпадающий список с деревом опций
     */
    private function get_select()
    {
        echo '<label class="select"><select name="section">' . PHP_EOL;
        echo '<option value="">Нет</option>' . PHP_EOL;
        echo $this->get_options($this->q); // вывод дерева опций
        echo '</select></label>' . PHP_EOL;
    }

    /**
     * Получить опции списка в виде дерева
     *
     * @param array $input Входные данные
     * @param string $section Идентификатор родительского раздела
     * @param int $level Уровень вложености
     * @return string
     */
    private function get_options(array &$input, string $section = '', int $level = 0)
    {
        if (empty($input)) {
            return;
        }

        $options = ''; // будет содержать дерево опций списка

        // обход входного массива
        foreach ($input as $k => $v) {
            // начать заполнять с корня
            if ($v['section'] == $section) {
                $bufer = '<option value="' . $v['id'] . '" '
                    . ($this->s && $this->s == $v['id'] ? 'selected' : '') . ' '
                    . ($this->i && $this->i == $v['id'] ? 'disabled' : '') . '>'
                    . str_repeat('&#183; ', $level) . $v['title']
                    . '</option>' . PHP_EOL; // записать в буфер
                unset($input[$k]); // удалить записанный элемент из входного массива
                // рекурсивно выбрать дочерние элементы
                $sublevel = $this->get_options($input, $v['id'], $level + 1);
                // если есть дочерние - записать в буфер
                if ($sublevel) {
                    $bufer .= $sublevel;
                }
                // записать буфер в дерево опций списка
                $options .= $bufer;
            }
        }

        return $options;
    }
}
