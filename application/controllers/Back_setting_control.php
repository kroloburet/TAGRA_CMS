<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH . 'controllers/Back_basic_control.php');

/**
 * Контроллер конфигурации
 *
 * Методы для работы с конфигурацией и пользователями системы в административной части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Back_setting_control extends Back_basic_control
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_setting_model');
    }

    /**
     * Проверить уникальность данных пользователя
     *
     * @param string $login
     * @param string $pass
     * @param string|null $mail
     * @param string|null $id
     * @return boolean
     */
    protected function _check_back_user(string $login, string $pass, string $mail = null, string $id = null)
    {
        $q = $this->back_basic_model->get_back_users();
        if (!$q) {
            return true;
        }
        foreach ($q as $v) {
            // логин и пароль не уникальны
            if (password_verify($login, $v['login']) && password_verify($pass, $v['password'])) {
                return false;
            }
            // модератор с таким email уже есть
            if ($mail && $v['email'] === $mail && $v['status'] === 'moderator') {
                // это не текущий модератор (который сейчас редактируется)
                if (!$id || $id !== $v['id']) {
                    return false;
                }
            }
        }
        // данные уникальны
        return true;
    }

    /**
     * Редактировать данные администратора
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, валидирует, перезаписывает данные и выведет строку ответа.
     *
     * @return void
     */
    function edit_administrator()
    {
        $p = array_map('trim', $this->input->post());
        /**
         * подготовка данных, проверка, валидация
         */
        // валидация email
        !filter_var($p['email'], FILTER_VALIDATE_EMAIL)
            ? exit(json_encode(['status' => 'nomail'], JSON_FORCE_OBJECT))
            : null;
        // проверка уникальности логина и пароля
        !$this->_check_back_user($p['login'], $p['password'])
            ? exit(json_encode(['status' => 'nounq'], JSON_FORCE_OBJECT))
            : null;
        // если логин не передан использовать старый, иначе - шифровать новый
        $p['login'] = $p['login'] === ''
            ? $this->_get_admin_param('login')
            : password_hash($p['login'], PASSWORD_BCRYPT);
        // если пароль не передан использовать старый, иначе - шифровать новый
        $p['password'] = $p['password'] === ''
            ? $this->_get_admin_param('password')
            : password_hash($p['password'], PASSWORD_BCRYPT);
        $p['last_mod_date'] = date('Y-m-d H:i:s');
        /**
         * перезаписать данные
         */
        !$this->back_basic_model->edit_back_user($this->_get_admin_param('id'), $p)
            ? exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT))
            : null;
        // перезаписать сессию чтобы не выбрасывало из админки
        $this->session->administrator
            ? $this->session->set_userdata('administrator', $p['password'] . $p['login'])
            : null;
        // вернуть ok и json запись админа
        $q = $this->db->where('status', 'administrator')->get('back_users')->result_array();
        if (empty($q)) {
            $a = '{}';
        } else {
            foreach ($q as $v) {
                $a[$v['id']] = $v;
            }
        }
        exit(json_encode(['status' => 'ok', 'opt' => $a], JSON_FORCE_OBJECT));
    }

    /**
     * Добавить модератора
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, валидирует, сохраняет данные и выведет строку ответа.
     *
     * @return void
     */
    function add_moderator()
    {
        $p = array_map('trim', $this->input->post());
        /**
         * подготовка данных, проверка, валидация
         */
        // валидация email
        !filter_var($p['email'], FILTER_VALIDATE_EMAIL)
            ? exit(json_encode(['status' => 'nomail'], JSON_FORCE_OBJECT))
            : null;
        // проверка уникальности логина, пароля и email
        !$this->_check_back_user($p['login'], $p['password'], $p['email'])
            ? exit(json_encode(['status' => 'nounq'], JSON_FORCE_OBJECT))
            : null;
        $p['creation_date'] = date('Y-m-d H:i:s');
        $p['status'] = 'moderator';
        $p['login'] = password_hash($p['login'], PASSWORD_BCRYPT);
        $p['password'] = password_hash($p['password'], PASSWORD_BCRYPT);
        /**
         * добавить данные
         */
        if ($this->back_basic_model->add($p, 'back_users')) {
            $q = $this->db->where('status', 'moderator')->get('back_users')->result_array();
            if (empty($q)) {
                $m = '{}';
            } else {
                foreach ($q as $v) {
                    $m[$v['id']] = $v;
                }
            }
            // данные добавлены
            exit(json_encode(['status' => 'ok', 'opt' => $m], JSON_FORCE_OBJECT));
        } else {
            // ошибка при добавлении данных
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
    }

    /**
     * Редактировать данные модератора
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, валидирует, перезаписывает данные и выведет строку ответа.
     *
     * @param string $id Идентификатор модератора
     * @return void
     */
    function edit_moderator(string $id)
    {
        $p = array_map('trim', $this->input->post());
        /**
         * подготовка данных, проверка, валидация
         */
        // валидация email
        !filter_var($p['email'], FILTER_VALIDATE_EMAIL)
            ? exit(json_encode(['status' => 'nomail'], JSON_FORCE_OBJECT))
            : null;
        // проверка уникальности логина и пароля
        !$this->_check_back_user($p['login'], $p['password'], $p['email'], $id)
            ? exit(json_encode(['status' => 'nounq'], JSON_FORCE_OBJECT))
            : null;
        //если логин не передан использовать старый, иначе - шифровать новый
        if ($p['login'] !== '') {
            $p['login'] = password_hash($p['login'], PASSWORD_BCRYPT);
        } else {
            unset($p['login']);
        }
        //если пароль не передан использовать старый, иначе - шифровать новый
        if ($p['password'] !== '') {
            $p['password'] = password_hash($p['password'], PASSWORD_BCRYPT);
        } else {
            unset($p['password']);
        }
        $p['last_mod_date'] = date('Y-m-d H:i:s');
        /**
         * редактировать данные
         */
        if ($this->back_basic_model->edit_back_user($id, $p)) {
            $q = $this->db->where('status', 'moderator')->get('back_users')->result_array();
            if (empty($q)) {
                $m = '{}';
            } else {
                foreach ($q as $v) {
                    $m[$v['id']] = $v;
                }
            }
            // данные отредактированы
            exit(json_encode(['status' => 'ok', 'opt' => $m], JSON_FORCE_OBJECT));
        } else {
            // ошибка при редактировании данных
            exit(json_encode(['status' => 'error'], JSON_FORCE_OBJECT));
        }
    }

    /**
     * Удалить модератора
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, удалит модератора и выведет строку ответа.
     *
     * @return void
     */
    function del_moderator()
    {
        // если в системе только один модератор - не удалять
        $this->db->where('status', 'moderator')->from('back_users')->count_all_results() === 1 ? exit('last') : null;
        $id = $this->input->post('id');
        $this->back_basic_model->del('back_users', $id) ? exit('ok') : exit('error');
    }

    /**
     * Редактировать настройки конфигурации системы
     *
     * Метод принимает данные из POST переданные
     * ajax запросом, редактирует данные и выведет json ответ.
     *
     * @return void
     */
    function set_config()
    {
        $conf = $this->_format_data($this->input->post(), true, 'json');
        $res = $this->back_setting_model->set_config($conf);
        exit(json_encode(['status' => $res ? 'ok' : 'error'], JSON_FORCE_OBJECT));
    }
}
