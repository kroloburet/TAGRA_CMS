<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Базовая модель
 *
 * Общие методы для работы в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_basic_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавить данные
     *
     * @param array $data Данные
     * @param string $table Имя таблицы
     * @return boolean
     */
    function add(array $data, string $table)
    {
        return $this->db->insert($table, $data);
    }

    /**
     * Редактировать данные
     *
     * @param string $id Идентификатор записи
     * @param array $data Данные
     * @param string $table Имя таблицы
     * @return bulean
     */
    function edit(string $id, array $data, string $table)
    {
        return $this->db->update($table, $data, ['id' => $id]);
    }

    /**
     * Удалить данные
     *
     * @param string $table Имя таблицы
     * @param string $id Идентификатор записи
     * @return boolean
     */
    function del(string $table, string $id)
    {
        return $this->db->delete($table, ['id' => $id]) !== false ? true : false;
    }

    /**
     * Переключить публикацию
     *
     * Инвертирует значение поля "public"
     *
     * @param string $id Идентификатор записи
     * @param string $table Имя таблицы
     * @return string Новое значение поля "public"
     */
    function toggle_public(string $id, string $table)
    {
        // получить материал
        $q = $this->db->where('id', $id)->get($table)->result_array();
        if (!isset($q[0]['public'])) {
            return false;
        }
        return (string) (
            $this->db->update($table, ['public' => $q[0]['public'] ? 0 : 1], ['id' => $id]) ?
            $q[0]['public'] ? 0 : 1 :
            false
            );
    }

    /**
     * Добавить/редактировать версии материала
     *
     * @param string $table Имя таблицы
     * @param array $new Mассив новых данных материала (будут записаны в БД)
     * @param array $old Массив старых данных материала (уже записаны в БД)
     * @return void|boolean
     */
    function set_versions(string $table, array $new = [], array $old = [])
    {
        if (!$table || empty($new)) {
            return false;
        }
        $db = $this->db;
        // получить в массив версии материала
        $get_versions = function (string $table, string $id) use ($db) {
            $q = $db->where('id', $id)->select('versions')->get($table)->result_array();
            return empty($q[0]['versions']) ? [] : json_decode($q[0]['versions'], true);
        };
        // удалить все связи с собой
        if (!empty($old['versions'])) {// версии материала уже есть в БД
            $ov = json_decode($old['versions'], true);
            // проход по версиям
            foreach ($ov as $k => $v) {
                $q = $get_versions($table, $v['id']); // получить в массив версии каждой из версий
                if (isset($q[$new['lang']])) {// есть связи с материалами связываемого языка
                    unset($q[$new['lang']]); // удалить связь
                    $db->where('id', $v['id'])->update($table, ['versions' => empty($q) ? '' :
                            json_encode($q, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
                }
            }
        }
        // добавить связь с собой
        if (!empty($new['versions'])) {
            $nv = json_decode($new['versions'], true);
            $preurl = '/';
            switch ($table) {
                case'pages':$preurl = '/page/';
                    break;
                case'sections':$preurl = '/section/';
                    break;
                case'gallerys':$preurl = '/gallery/';
                    break;
            }
            // пройти по новым связанным материалам
            foreach ($nv as $k => $v) {
                $q = $get_versions($table, $v['id']); // получить в массив версии каждой из версий
                if (isset($q[$new['lang']])) {// есть связи с материалами связываемого языка
                    $q2 = $get_versions($table, $q[$new['lang']]['id']);
                    unset($q2[$k]); // удалить связь
                    $db->where('id', $q[$new['lang']]['id'])->update($table, ['versions' => empty($q2) ? '' :
                            json_encode($q2, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
                }
                $db->where('id', $v['id'])->update(
                    $table, ['versions' => json_encode(
                        [$new['lang'] => ['id' => $new['id'], 'title' => $new['title'], 'url' => $preurl . $new['id']]] + $q,
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
        }
    }

    /**
     * Удалить связи с метериалом в версиях
     *
     * @param string $table Имя таблицы
     * @param string $url URL материала
     * @return void|boolean
     */
    function del_versions(string $table, string $url)
    {
        // вернуть записи с искомой
        $q = $this->db->select('id,versions')->like('versions', '"' . $url . '"')->get($table)->result_array();
        if (empty($q)) {
            return false;
        }
        // проход по записям
        foreach ($q as $k => $v) {
            $vers = json_decode($v['versions'], true);
            foreach ($vers as $lang => $opt) {
                if ($opt['url'] === $url) {
                    unset($vers[$lang]);
                }
            }
            // проход по массиву опций, удалить искомое
            $q[$k]['versions'] = empty($vers) ? '' :
                json_encode($vers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // массив опций в json, для отправки
        }
        // изменить в базе записи с искомым
        return $this->db->update_batch($table, $q, 'id') !== false ? true : false;
    }

    /**
     * Получить все языки из БД
     *
     * @return array
     */
    function get_langs()
    {
        return $this->db->get('languages')->result_array();
    }

    /**
     * Получить запись по идентификатору
     *
     * @param string $table Имя таблицы
     * @param string $id Идентификатор записи
     * @return array|boolean
     */
    function get_where_id(string $table, string $id)
    {
        $q = $this->db->where('id', $id)->get($table)->result_array();
        return isset($q[0]) ? $q[0] : false;
    }

    /**
     * Получить значение из поля в таблице
     *
     * @param string $table Имя таблицы
     * @param string $field Поле
     * @param string $field_val Значение в поле
     * @param string $res_field Получить значение (из найденной записи)
     * @return mixed|boolean Значение поля "$res_field" или false
     */
    function get_val(string $table, string $field, string $field_val, string $res_field)
    {
        $q = $this->db->get_where($table, [$field => $field_val])->result_array();
        return isset($q[0][$res_field]) ? $q[0][$res_field] : false;
    }

    /**
     * Проверка уникальности заголовка
     *
     * Проверка не затрагивает запись с id (если передан), поскольку метод
     * применяется в основном при редактировании "title" этой же записи
     *
     * @param string $title Заголовок
     * @param string|null $id Идентификатор текущей записи
     * @param string $table Имя таблицы
     * @return boolean false если запись уникальна или true если нашлось совпадение
     */
    function check_title(string $title, string $id, string $table)
    {
        $where = $id ? ['title' => $title, 'id !=' => $id] : ['title' => $title];
        $q = $this->db->where($where)->get($table)->result_array();
        return empty($q) ? false : true;
    }

    /**
     * Получить отфильтрованную выборку
     *
     * Метод применяет данные фильтра для выборки
     * из таблицы материала.
     *
     * @param string $t Имя таблицы
     * @param array $f Данные фильтра
     * @return array Отфильтрованная выборка с данными примененного фильтра
     */
    function get_list(string $t, array $f = [])
    {
        $data = ['filter' => $f, 'result' => [], 'count_result' => 0];// массив для возврата
        if (!$t) {
            return $data;
        }
        // подготовить запрос
        $t = $this->db->dbprefix($t);
        $where = '';
        $like = '';
        $order_by = 'ORDER BY creation_date DESC';
        $limit = '';
        if (isset($f['lang']) && $f['lang'] !== 'all') {
            $where = "WHERE lang='{$f['lang']}'";
        }
        if (isset($f['context_search']) && isset($f['search']) && $f['search'] !== '') {
            $like = $where ? 'AND ' : 'WHERE ';
            $search = "LIKE LOWER('%{$this->db->escape_like_str($f['search'])}%') ESCAPE '!'";
            if ($f['context_search'] === 'content') {
                $like .= "(
                    LOWER(layout_t) {$search}
                    OR LOWER(layout_l) {$search}
                    OR LOWER(layout_r) {$search}
                    OR LOWER(layout_b) {$search}
                )";
            } else {
                $like .= "LOWER({$f['context_search']}) {$search}";
            }
        }
        if (isset($f['order'])) {
            $order_by = "ORDER BY {$f['order']}";
            if (
                $f['order'] === 'id'
                || $f['order'] === 'creation_date'
                || $f['order'] === 'last_mod_date'
                || $f['order'] === 'def'
            ) {
                $order_by .= ' DESC';
            }
        }
        if (isset($f['limit']) && $f['limit'] !== 'all') {
            if ($this->input->get('per_page')) {
                $limit = "LIMIT {$this->input->get('per_page')}, {$f['limit']}";
            } else {
                $limit = "LIMIT {$f['limit']}";
            }
        }
        // выполнить запрос
        $q = $this->db->query("SELECT * FROM `{$t}` {$where} {$like} {$order_by} {$limit};")->result_array();
        $data['count_result'] = count($this->db->query("SELECT * FROM `{$t}` {$where} {$like} {$order_by};")->result_array());
        $data['result'] = $q;
        // отладка
//        print_r($f);
//        echo "<br> SELECT * FROM `{$t}` {$where} {$like} {$order_by} {$limit};";
        // вернуть данные
        return $data;
    }

    /**
     * Получить пользователей админки
     *
     * Выбрать всех по email если передан
     *
     * @param string|null $email
     * @return array|boolean
     */
    function get_back_users(string $email = null)
    {
        if ($email) {
            $this->db->where('email', $email);
        }
        $q = $this->db->get('back_users')->result_array();
        return empty($q) ? false : $q;
    }

    /**
     * Редактировать данные пользователя админки
     *
     * @param string $id Идентификатор пользователя
     * @param array $data Данные
     * @return boolean
     */
    function edit_back_user(string $id, array $data = [])
    {
        return $this->db->update('back_users', $data, ['id' => $id]);
    }

    /**
     * Получить конфигурацию
     *
     * Возвращает массив ['name']='value'. Если 'value' - json - преобразовывает в подмассив
     *
     * @return array
     */
    function get_config()
    {
        $q = $this->db->get('config')->result_array();
        foreach ($q as $v) {
            $json = @json_decode($v['value'], true);
            $data[$v['name']] = $json === null ? $v['value'] : $json;
        }
        return $data;
    }
}
