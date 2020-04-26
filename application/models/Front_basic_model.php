<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Базовая модель
 *
 * Общие методы для работы в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_basic_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Получить значение из массива "app"
     *
     * app[] содержит: конфигурацию ресурса, публичные данные пользователей,
     * массив локализации и иные вспомагательные опции общего пользования.
     * Метод реализует удобный доступ к нужному значению передав в него "путь"
     * из ключей через точку.
     * Например: $this->app('conf.langs') === $app['conf']['langs']
     *
     * @param string $path Путь к значению через точку или null чтобы получить весь массив
     * @return mixed Значение из массива, весь массив или null если ключ не найден
     */
    function app(string $path = null)
    {
        // заполнить app[] если пуст
        if (empty($this->config->item('app'))) {
            $this->config->set_item('app', $this->get_config());
        }
        // вернуть весь массив если путь не передан
        if (!$path || !is_string($path)) {
            return $this->config->item('app');
        }
        // обработать путь и вернуть значение массива
        return array_reduce(explode('.', $path), function ($i, $k) {
            return isset($i[$k]) ? $i[$k] : null;
        }, $this->config->item('app'));
    }

    /**
     * Редактировать массив "app"
     *
     * Добавляет или изменяет значение
     *
     * @param array $data Данные путь=>значение ['conf.langs.ru.title'=>'RU','lexic.basic.home'=>'Домой']
     * @return void|boolean
     */
    function set_app(array $data = [])
    {
        if (empty($data)) {
            return false;
        }
        foreach ($data as $path => $val) {
            $level = &$this->config->config['app'];
            foreach (explode('.', $path) as $k) {
                if (!key_exists($k, $level) || !is_array($level[$k])) {
                    $level[$k] = [];
                }
                $level = &$level[$k];
            }
            $level = $val;
        }
    }

    /**
     * Получить конфигурацию
     *
     * Метод записывает и возвращает в массиве все публичные
     * настройки, данные сеанса, язык пользователя с данными локализации...
     * Задавайте здесь ваши данные которые повсеместно используются
     * в текущем севнсе
     *
     * @return array
     */
    function get_config()
    {
        // получить массив конфигурации
        $q = $this->db->get('config')->result_array();
        foreach ($q as $v) {
            $json = @json_decode($v['value'], true);
            // если значение - json - преобразовать в массив
            $data['conf'][$v['name']] = $json === null ? $v['value'] : $json;
        }
        // модераторы системы
        $m = []; // массив будет хранить emailы всех модераторов
        $ip = $this->input->server('REMOTE_ADDR'); // текущий ip
        $q = $this->db->get('back_users')->result_array();
        $data['conf']['back_user'] = false;
        foreach ($q as $v) {
            // это админ/разрешенный модератор или обычный смертный
            $v['ip'] === $ip && $v['access'] ? $data['conf']['back_user'] = true : null;
            switch ($v['status']) {
                case'administrator':
                    $data['conf']['admin_mail'] = $v['email'];
                    break;
                case'moderator':
                    $v['access'] ? $m[] = $v['email'] : null;
                    break;
            }
        }
        // emailы всех разрешенных модераторов в строку через запятую
        $data['conf']['moderator_mail'] = implode(',', $m);
        // языки системы
        $data['conf']['langs'] = $this->db->get('languages')->result_array();
        // язык системы по умолчанию
        foreach ($data['conf']['langs'] as $i) {
            if ($i['def']) {
                $data['conf']['lang_def'] = $i;
                break;
            }
        }
        $tags = array_column($data['conf']['langs'], 'tag'); // массив тегов языков системы
        $hal = substr($this->input->server('HTTP_ACCEPT_LANGUAGE'), 0, 2); // тег языка браузера пользователя
        $ulc = $this->input->cookie('user_lang'); // куки с языком пользоватля
        // язык пользователя
        $data['conf']['user_lang'] = $ulc && in_array($ulc, $tags) ? $ulc :
            (in_array($hal, $tags) ? $hal : $data['conf']['lang_def']['tag']);
        // данные локализации
        $data['lexic'] = $this->lang->load('front_template', $data['conf']['user_lang'], true);
        return $data;
    }

    /**
     * Получить меню
     *
     * Получает из БД все пункты меню пренадлежащие языку пользователя,
     * возвращает в отформатированном массиве (дерево)
     *
     * @return array
     */
    function get_menu()
    {
        $q = $this->db->where(['public' => 1, 'lang' => $this->app('conf.user_lang')])->
        order_by('order')->get('menu')->result_array();
        if (empty($q)) {
            return [];
        }

        // выборку в многомерный массив (дерево)
        function maketree($input, $pid = 0)
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
}
