<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Базовый контроллер
 *
 * Общие методы для работы в пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
class Front_basic_control extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('front_basic_model');
        // проверка доступа к пользовательской части
        $this->_is_site_access();
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
            $this->config->set_item('app', $this->front_basic_model->get_config());
        }
        // вернуть весь массив если путь не передан
        if (!$path || !is_string($path)) {
            return $this->config->item('app');
        }
        // обработать путь и вернуть значение массива
        return array_reduce(explode('.', $path), function($i, $k) {
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
            $path_arr = explode('.', $path);
            foreach ($path_arr as $k) {
                if (!key_exists($k, $level) || !is_array($level[$k])) {
                    $level[$k] = [];
                }
                $level = &$level[$k];
            }
            $level = $val;
        }
    }

    /**
     * Доступ к пользовательской части
     *
     * Метод перенаправляет на страницу-заглушку
     * если в настройках конфигурации доступ к сайту
     * закрыт и пользователь не админ или модератор.
     *
     * @return void
     */
    protected function _is_site_access()
    {
        !$this->app('conf.site_access') && !$this->app('conf.back_user')
            ? redirect('plug.html', 'location', 302)
            : null;
    }

    /**
     * Вывод шаблона
     *
     * Метод дополнит массив app['data'] данными материала для view-шаблонов
     * и загрузит шаблоны материала.
     *
     * @param string $path Путь к шаблону материала относительно /application/views/
     * @param array $data Данные материала
     * @return void
     */
    protected function _viewer(string $path, array $data)
    {
        // установить языком пользователя язык материала
        if (isset($data['lang']) && $this->app('conf.user_lang') !== $data['lang']) {
            $this->input->set_cookie('user_lang', $data['lang'], 0);
            $this->set_app([
                'conf.user_lang' => $data['lang'],
                'lexic' => $this->lang->load('front_template', $data['lang'], true)
            ]);
        }
        // добавить меню сайта и данные материала
        $this->set_app([
            'data' => $data,
            'data.front_menu_list' => $this->front_basic_model->get_menu()
        ]);
        $this->load->view('front/components/header_view', $this->app()); // header
        $this->load->view($path, $this->app()); // body
        $this->load->view('front/components/footer_view', $this->app()); // footer
    }

    /**
     * Изменить язык пользователя
     *
     * Метод проверяет, есть ли в системе
     * переданный язык, если есть - установить
     * его языком пользователя и перенаправить
     * на переданный URL.
     *
     * @param string $tag Тег языка
     * @param string $url URL для перенаправления
     * @return void
     */
    function change_lang(string $tag, string $url = '/')
    {
        if (!in_array($tag, array_column($this->app('conf.langs'), 'tag'))) {
            redirect('404_override');
            return false;
        }
        $this->input->set_cookie('user_lang', $tag, 0);
        redirect($url);
    }
}
