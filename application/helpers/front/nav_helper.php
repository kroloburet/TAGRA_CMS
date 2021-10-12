<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Класс навигации
 */
class Nav
{

    private $CI;
    private $conf;
    private $data;
    private $lexic;
    private $list = ['langs' => '', 'menu' => '', 'breadcrumbs' => ''];

    function __construct()
    {
        $this->CI = &get_instance();
        $this->conf = $this->CI->app('conf');
        $this->data = $this->CI->app('data');
        $this->lexic = $this->CI->app('lexic');
    }

    /**
     * Вывести переключатель языков
     *
     * @return void
     */
    public function langs()
    {
        $material = $this->CI->uri->segment(1, '/');
        $versions = isset($this->data['versions']) && !empty($this->data['versions'])
            ? json_decode($this->data['versions'], true) : [];

        // если в системе из опубликованных только один язык
        if (count($this->conf['langs']) < 2) {
            return;
        }

        // сформировать и вывести HTML ссылки
        $this->list['langs'] .= '
        <!-- переключение языка -->
        <span class="l_s">';
        foreach ($this->conf['langs'] as $v) {// проход по языкам системы
            if ($v['tag'] !== $this->conf['user_lang'] && $v['public']) {// язык опубликован, язык не текущего материала
                if (isset($versions[$v['tag']])) {// есть языковые версии материала
                    $this->list['langs'] .= '
          <a href="' . $versions[$v['tag']]['url'] . '" title="' . $versions[$v['tag']]['title'] . '">' . $v['title'] . '</a>';
                }
                if (in_array($material, ['/', 'contact'])) {// текущая страница "Главная" или "Контакты"
                    $this->list['langs'] .= '
          <a href="/do/change_lang/' . $v['tag'] . '/' . $material . '">' . $v['title'] . '</a>';
                }
            }
        }
        $this->list['langs'] .= '
        </span>
        ' . PHP_EOL;
        echo $this->list['langs'];
    }

    /**
     * Вывести главное меню
     *
     * @return void
     */
    public function menu()
    {
        // если пустое меню
        if (empty($this->data['front_menu_list'])) {
            return;
        }

        // сформировать и вывести HTML список
        $this->list['menu'] .= '
        <!-- главное меню -->
        <ul class="TUI_Menu TUI_noprint">';
        $this->_menu_get_list($this->data['front_menu_list']);
        $this->list['menu'] .= '
        </ul>' . PHP_EOL;
        echo $this->list['menu'];
    }

    /**
     * Дополнить HTML список меню пунктами
     *
     * @param array $list Массив меню
     * @return void
     */
    private function _menu_get_list(array $list)
    {
        foreach ($list as $i) {
            $this->list['menu'] .= '
          <li>
            ' . ($i['url']
                    ? '<a href="' . $i['url'] . '" target="' . $i['target'] . '">' . $i['title'] . '</a>'
                    : '<span>' . $i['title'] . '</span>');
            // если есть вложенные элементы
            if (isset($i['nodes'])) {
                $this->list['menu'] .= '
            <ul>';
                $this->_menu_get_list($i['nodes']); // рекурсия
                $this->list['menu'] .= '
            </ul>';
            }
            $this->list['menu'] .= '
          </li>';
        }
    }

    /**
     * Вывести навигацию "Хлебные крошки"
     *
     * @return void
     */
    public function breadcrumbs()
    {
        // если "Хлебные крошки" скрыты конфигурацией
        if (!$this->conf['breadcrumbs']['public']) {
            return;
        }

        $home = isset($this->conf['breadcrumbs']['home']) && $this->conf['breadcrumbs']['home'] // "главная" в цепи
            ? '<li><a href="/" class="bc_home">'
            . $this->lexic['breadcrumbs']['home'] . '</a></li>'
            : '';

        // сформировать и вывести HTML список
        $this->list['breadcrumbs'] .= '
        <!-- навигация "хлебные крошки" -->
        <ul class="breadcrumbs TUI_noprint">
          ' . $home; // начало цепи + "главная"

        // если есть раздел - получить цепь подразделов
        if (@$this->data['section']) {
            $this->_breadcrumbs_get_sub_sections_list($this->data['section']);
        }

        $this->list['breadcrumbs'] .= '
          <li class="bc_end">' . $this->data['title'] . '</li>
        </ul>' . PHP_EOL; //конец цепи
        echo $this->list['breadcrumbs'];
    }

    /**
     * Дополнить HTML список "Хлебных крошек" цепочкой подразделов
     *
     * @param string $id Идентификатор родительского раздела
     * @return void
     */
    private function _breadcrumbs_get_sub_sections_list(string $id)
    {
        $q = $this->_breadcrumbs_get_section($id);

        if (!empty($q)) {
            // если есть родитель - рекурсия
            if ($q['section']) {
                $this->_breadcrumbs_get_sub_sections_list($q['section']);
            }

            $this->list['breadcrumbs'] .= '
          <li><a href="/section/' . $q['id'] . '">' . $q['title'] . '</a></li>';
        }
    }

    /**
     * Получить данные материала из БД
     *
     * @param string $id Идентификатор материала
     * @return array
     */
    private function _breadcrumbs_get_section(string $id)
    {
        $q = $this->CI->db
            ->where(['public' => 1, 'id' => $id])
            ->select('title, id, section')
            ->get('sections')
            ->result_array();
        return isset($q[0]) ? $q[0] : [];
    }
}
