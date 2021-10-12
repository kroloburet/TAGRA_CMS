<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Контроллер "Восстановить доступ"
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Change_login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('back_basic_model');
    }

    /**
     * Генерировать пароль
     *
     * @param int $lenght Символов в строке результата
     * @return string
     */
    protected function _gen_pass(int $lenght = 10)
    {
        $alphabet = range('a', 'z');
        $up_alphabet = range('A', 'Z');
        $digits = range('1', '9');
        $spech = ['~', '@', '#', '$', '[', ']', '_', '-'];
        $full_array = array_merge($alphabet, $up_alphabet, $digits, $spech);
        $password = '';
        for ($i = 0; $i < $lenght; $i++) {
            $entrie = array_rand($full_array);
            $password .= $full_array[$entrie];
        }
        return $password;
    }

    /**
     * Сбросить и отправить новые логин и пароль пользователю
     *
     * Метод принимает данные из post переданные
     * ajax запросом, валидирует, перезаписывает логин и пароль,
     * отправляет новые логин и пароль пользователю, выведет строку ответа.
     *
     * @return void
     */
    function index()
    {
        $count = 0; // счетчик сообщений
        $p = array_map('trim', $this->input->post()); // данные
        $mail = filter_var($p['send_pass_mail'], FILTER_VALIDATE_EMAIL); // поле email
        /**
         * проверка, валидация данных
         */
        // если бот
        $p['fuck_bot'] !== '' ? exit(json_encode(['status' => 'bot'], JSON_FORCE_OBJECT)) : null;
        // валидация email
        !$mail ? exit(json_encode(['status' => 'nomail'], JSON_FORCE_OBJECT)) : null;
        // получить выборку по email
        !$q = $this->back_basic_model->get_back_users($mail) ?
            exit(json_encode(['status' => 'nomail'], JSON_FORCE_OBJECT)) : null;
        /**
         * обработка выборки
         */
        $domain = str_replace('www.', '', $this->input->server('HTTP_HOST')); // домен
        $site_name = $this->back_basic_model->get_val('config', 'name', 'site_name', 'value'); // имя сайта
        $this->load->library('email');
        // проход по выборке
        foreach ($q as $v) {
            // если запрещенный модератор
            if ($v['status'] === 'moderator' && !$v['access']) {
                // если кроме него в выборке никого нет
                count($q) < 2 ? exit(json_encode(['status' => 'noaccess'], JSON_FORCE_OBJECT)) : null;
                continue; // пропустить итерацию
            }
            // имя пользователя email или генерировать
            $login = strstr($mail, '@', true) ? strstr($mail, '@', true) : $this->_gen_pass(8);
            // генерировать новый пароль
            $pass = $this->_gen_pass();
            // шифровать логин для БД
            $data['login'] = password_hash($login, PASSWORD_BCRYPT);
            // шифровать пароль для БД
            $data['password'] = password_hash($pass, PASSWORD_BCRYPT);
            $data['last_mod_date'] = date('Y-m-d H:i:s');
            // перезаписать данные пользователя
            $this->back_basic_model->edit_back_user($v['id'], $data)
                ? exit(json_encode(['status' => 'noedit'], JSON_FORCE_OBJECT))
                : null;
            // отправить новые данные пользователю
            $msg = '
<html>
    <head>
        <title>Пароли к ' . $domain . '</title>
    </head>
    <body>
        <h2>Здравствуйте!</h2>
        <p>
            ' . date('Y-m-d H:i:s') . ' вам отосланы новые логин и пароль для авторизации на сайте ' . $domain . '.<br>
            Вы можете использовать их для <a href="' . base_url('admin') . '" target="_blank">входа в админку сайта</a>.
            Старые логин и пароль были перезаписаны с целью безопасности. Ваш статус в системе &mdash; ' . $v['status'] . '.
        </p>
        <p>
            Логин: ' . $login . '<br>
            Пароль: ' . $pass . '<br>
        </p>
        <hr>
        <b>Внимание!</b> &mdash; Эта информация конфиденциальна и отправлена только вам!
        Не храните ее в месте доступном для посторонних. Сообщение сгенерировано системой. Не отвечайте на него.
    </body>
</html>
';
            $this->email->from('Robot@' . $domain, $site_name);
            $this->email->to($mail);
            $this->email->subject('Пароли к ' . $domain);
            $this->email->message($msg);
            $this->email->send() ? $count++ : exit(json_encode(['status' => 'nosend'], JSON_FORCE_OBJECT));
        }
        exit(json_encode([
                'status' => 'ok',
                'html' => 'На указанный e-mail <q>' . $mail . '</q> отправлено сообщений: ' . $count . '<br>'
                    . 'Сообщения могут быть помещены в <q>спам</q>']
            , JSON_FORCE_OBJECT));
    }
}
