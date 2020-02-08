<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Разметка структурированных данных META и JSON-LD
 */
 class Markup_data{

    private $CI;
    private $data;
    private $conf;
    private $lexic;
    private $lang;
    private $img_prev;
    private $material;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->data = $this->CI->app('data');
        $this->conf = $this->CI->app('conf');
        $this->lexic = $this->CI->app('lexic');
        $this->lang = isset($this->data['lang'])
            ? $this->data['lang']
            : $this->conf['user_lang'];
        $this->img_prev = empty($this->data['img_prev'])
            ? empty($this->conf['img_prev_def'])
                ? base_url('img/tagra_share.jpg')
                : $this->conf['img_prev_def']
            : $this->data['img_prev'];
        $this->material = $this->CI->uri->segment(1);
    }

    /**
     * Вывести разметку для материала
     *
     * Метод выводит метаданные и ld+json данные материала
     *
     * @return void
     */
    public function print()
    {
        if (empty($this->data) || empty($this->conf) || empty($this->lexic)) {
            exit('Упс! Недостаточно данных для работы класса ' . __CLASS__);
        }

        $img_prev_size = @getimagesize($this->img_prev);

        echo '
    <!--
    ########### Разметка структурированных данных
    -->

    <!--google-->
    <meta itemprop="name" content="' . htmlspecialchars($this->conf['site_name']) . '">
    <meta itemprop="description" content="' . htmlspecialchars($this->data['description']) . '">
    <meta itemprop="image" content="' . htmlspecialchars($this->img_prev) . '">
    <!--twitter-->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="' . htmlspecialchars($this->conf['site_name']) . '">
    <meta name="twitter:title" content="' . htmlspecialchars($this->data['title']) . '">
    <meta name="twitter:description" content="' . htmlspecialchars($this->data['description']) . '">
    <meta name="twitter:image:src" content="' . htmlspecialchars($this->img_prev) . '">
    <meta name="twitter:domain" content="' . base_url() . '">
    <!--facebook-->
    <meta property="og:title" content="' . htmlspecialchars($this->data['title']) . '">
    <meta property="og:description" content="' . htmlspecialchars($this->data['description']) . '">
    <meta property="og:image" content="' . htmlspecialchars($this->img_prev) . '">
    <meta property="og:image:width" content="' . (@$img_prev_size[0] ? @$img_prev_size[0] : 1200) . '">
    <meta property="og:image:height" content="' . (@$img_prev_size[1] ? @$img_prev_size[1] : 630) . '">
    <meta property="og:url" content="' . current_url() . '">
    <meta property="og:site_name" content="' . htmlspecialchars($this->conf['site_name']) . '">
    <!--другие-->
    <link rel="image_src" href="' . htmlspecialchars($this->img_prev) . '">
    ' . $this->_contact() . '
    ' . $this->_breadcrumb() . '
    ' . $this->_article() . '
    ';
    }

    /**
     * Получить разметку контактов ld+json
     *
     * @return string
     */
    private function _contact()
    {
        // получить json контактов
        $q = $this->CI->db
            ->where('lang', $this->lang)
            ->get('contact_pages')
            ->result_array()[0]['contacts'];

        // если нет контактов
        if (!$q) {
            return '';
        }

        $tel = $mail = $address = '';
        $tmp = ['t'=>[], 'm'=>[]];// временный массив данных
        $c = json_decode($q, true); // json в массив

        // заполнить временный массив и адрес
        foreach ($c as $v) {
            $v['tel'] ? $tmp['t'][] = $v['tel'] : null;
            $v['mail'] ? $tmp['m'][] = $v['mail'] : null;
            $v['address']
                ? $address .= '{"@type":"PostalAddress","streetAddress":' . json_encode($v['address']) . '}'
                : null;
        }

        // телефоны и email из временного массива в строку, в кавычки, оставить уникальные
        $tel = implode(',', array_map(function($i) {
                return '"' . preg_replace('/\s+/', '', $i) . '"';
            }, array_unique($tmp['t'])));
        $mail = implode(',', array_map(function($i) {
                return '"' . preg_replace('/\s+/', '', $i) . '"';
            }, array_unique($tmp['m'])));

        return '
    <!--разметка контактов-->
    <script type="application/ld+json">
      {
        "@context":"http://schema.org",
        "@type":"Organization",
        "name":' . json_encode($this->conf['site_name']) . ',
        "url":"' . base_url() . '",
        "logo":"' . $this->img_prev . '"
        ' . (!empty($tel) ? ',"telephone":[' . $tel . ']' : '') . '
        ' . (!empty($mail) ? ',"email":[' . $mail . ']' : '') . '
        ' . ($address ? ',"address":[' . preg_replace('/\}\{/m', '},{', $address) . ']' : '') . '
      }
    </script>
    ';
    }

    /**
     * Получить разметку "хлебных крошек" ld+json
     *
     * @return string
     */
    private function _breadcrumb()
    {
        if (
            $this->material === 'contact'
            || !isset($this->conf['breadcrumbs']['public'])
            || !$this->conf['breadcrumbs']['public']
        ) {
            return '';
        }

        $CI = $this->CI;
        $data = $this->data;
        $home = isset($this->conf['breadcrumbs']['home']) && $this->conf['breadcrumbs']['home']// главная в цепи
            ? '{"@type":"ListItem","position":1,"name":'
                . json_encode($this->lexic['breadcrumbs']['home']) . ',"item":"'
                . base_url() . '"}'
            : '';
        $breadcrumb = $home;// добавить главную в цепь

        /**
         * Добавить в цепь подразделы
         *
         * @param string $id Идентификатор родительского раздела
         * @param int $pos Позиция звена в цепи
         * @return viod
         */
        $get_sub_sections = function (string $id, int $pos) use ($CI, $data, &$breadcrumb, &$get_sub_sections) {
            $q = $CI->db
                ->where(['public'=>1, 'id'=>$id, 'lang'=>$data['lang']])
                ->select('title, id, section')
                ->get('sections')
                ->result_array();
            if (isset($q[0]) && !empty($q[0])) {// такой id есть
                if ($q[0]['section']) {// есть родитель - рекурсия
                    $get_sub_sections($q[0]['section'], $pos);
                    $pos = $pos + 1;
                }
                $breadcrumb .= '{"@type":"ListItem","position":'
                    . $pos . ',"name":'
                    . json_encode($q[0]['title']) . ',"item":"'
                    . base_url('section/' . $q[0]['id']) . '"}';
            }
        };

        if (@$data['section']) {// етсть раздел
            $get_sub_sections($data['section'], $home ? 2 : 1);
        }

        return '
    <!--разметка "хлебных крошек"-->
    <script type="application/ld+json">
      {
        "@context":"http://schema.org",
        "@type":"BreadcrumbList",
        "itemListElement":[' . preg_replace('/\}\{/m', '},{', $breadcrumb) . ']
      }
    </script>
    ';
    }

    /**
     * Получить разметку статьи ld+json
     *
     * @return string
     */
    private function _article()
    {
        if ($this->material === 'contact') {
            return '';
        }

        $creation_date = !empty($this->data['creation_date']) ? $this->data['creation_date'] : date('Y-m-d');
        $last_mod_date = !empty($this->data['last_mod_date']) ? $this->data['last_mod_date'] : date('Y-m-d');
        $layout = @$this->data['layout_t'] . @$this->data['layout_l'] . @$this->data['layout_r'] . @$this->data['layout_b'];
        $imgs = $audios = $comments_count = $comments = '';

        /**
         * обработка контента статьи
         */
        if (!empty($layout)) {
            //изображения в контенте
            preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i', $layout, $layout_imgs);
            if (!empty($layout_imgs[1])) {
                foreach ($layout_imgs[1] as $v) {
                    if (!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i', $v)) {
                        continue; // тип не разрешен
                    }
                    $v = preg_match('/^https?:\/\//i', $v) ? $v : base_url($v); // url должен быть абсолютный
                    $imgs .= '{"@type":"ImageObject","url":"' . $v . '"}';
                }
            }
            // аудио в контенте
            preg_match_all('/<audio[^>]+src="([^"]+)"[^>]*>/i', $layout, $layout_audios);
            if (!empty($layout_audios[1])) {
                foreach ($layout_audios[1] as $v) {
                    $v = preg_match('/^https?:\/\//i', $v) ? $v : base_url($v); //url должен быть абсолютный
                    $audios .= '{"@type":"AudioObject","url":"' . $v . '"}';
                }
            }
        }

        /**
         * обработка галерей статьи
         */
        if (isset($this->data['gallery_opt']) && $this->data['gallery_opt']) {
            switch ($this->data['gallery_type']) {

                // галерея из папки с изображениями
                case'foto_folder':

                    /**
                     * Получить ld+json с URL изображений из папки
                     *
                     * @param string $dir Путь к папке с изображениями
                     * @return string
                     */
                    function get_foto_folder_srcs(string $dir)
                    {
                        $result = '';
                        if ($dir_handle = @opendir('.' . $dir)) {// открыть папку
                            while ($file = readdir($dir_handle)) {// проход по файлам
                                if ($file == '.' || $file == '..')
                                    continue; // пропустить ссылки на другие папки
                                if (!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i', $file)) {
                                    continue;// тип не разрешен
                                }
                                $result .= '{"@type":"ImageObject","url":"' . base_url($dir . '/' . $file) . '"}';
                            }
                            closedir($dir_handle);
                        }
                        return $result;
                    }

                    $imgs .= get_foto_folder_srcs(json_decode($this->data['gallery_opt'], true)['f_folder']);
                    break;

                // галерея изображений с описаниями
                case'foto_desc':
                    $g_opt = json_decode($this->data['gallery_opt'], true);
                    foreach ($g_opt as $v) {
                        if (!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i', $v['f_url'])) {
                            continue;//тип не разрешен
                        }
                        //url должен быть абсолютный
                        $v['f_url'] = preg_match('/^https?:\/\//i', $v['f_url'])
                            ? $v['f_url']
                            : base_url($v['f_url']);
                        $imgs .= '{"@type":"ImageObject","name":'
                            . json_encode($v['f_title']) . ',"description":'
                            . json_encode($v['f_desc']) . ',"url":"'
                            . $v['f_url'] . '"}';
                    }
                    break;

                // аудио галерея
                case'audio':
                    $g_opt = json_decode($this->data['gallery_opt'], true);
                    foreach ($g_opt as $v) {
                        // url должен быть абсолютный
                        $v['a_url'] = preg_match('/^https?:\/\//i', $v['a_url'])
                            ? $v['a_url']
                            : base_url($v['a_url']);
                        $audios .= '{"@type":"AudioObject","name":'
                            . json_encode($v['a_title']) . ',"url":"'
                            . $v['a_url'] . '"}';
                    }
                    break;
            }
        }

        /**
         * обработка комментариев статьи
         */
        if (isset($this->data['comments']) && $this->data['comments'] !== 'off') {
            $q = $this->CI->db
                ->where(['public' => 1, 'url' => uri_string()])
                ->get('comments')
                ->result_array();
            if (!empty($q)) {
                $tree_arr = [];
                // получить многомерный массив
                foreach (array_reverse($q) as $v) {
                    $tree_arr[$v['pid']][] = $v;
                }

                /**
                 * Получить ld+json дерева комментариев
                 *
                 * @param array $tree_arr Многомерный массив комментариев
                 * @param string $pid Идентификатор родительского комментария
                 * @return string
                 */
                function build_tree(array $tree_arr, string $pid = '0')
                {//построение дерева
                    if (!is_array($tree_arr) || !isset($tree_arr[$pid])) {
                        return; // нет данных
                    }
                    $tree = '';
                    foreach ($tree_arr[$pid] as $v) {
                        $name = filter_var($v['name'], FILTER_VALIDATE_EMAIL)
                            ? explode('@', $v['name'])[0]
                            : $v['name'];
                        $tree .= '{"@type":"Comment","datePublished":"'
                            . $v['creation_date'] . '","text":'
                            . json_encode($v['comment']) . ',"creator":{"@type":"Person","name":'
                            . json_encode($name) . '}}';
                        $tree .= build_tree($tree_arr, $v['id']);
                    }
                    return $tree;
                }
                $comments_count = count($q); //всего комментариев
                $comments = build_tree($tree_arr);
            }
        }

        return '
    <!--разметка статьи-->
    <script type="application/ld+json">
      {
         "@context":"http://schema.org",
         "@type":"Article",
         "mainEntityOfPage":{"@type":"WebPage","@id":"' . current_url() . '"},
         "headline":' . json_encode($this->data['title']) . ',
         "description":' . json_encode($this->data['description']) . ',
         "datePublished":"' . $creation_date . '",
         "dateModified":"' . $last_mod_date . '",
         "author":{"@type":"Person","name":' . json_encode($this->conf['site_name']) . '},
         "publisher":{"@type":"Organization","name":' . json_encode($this->conf['site_name']) . ',"logo":"' . $this->img_prev . '"},
         "image":[{"@type":"ImageObject","representativeOfPage":true,"url":"' . $this->img_prev . '"}' . ($imgs ? ',' . preg_replace('/\}\{/m', '},{', $imgs) : '') . ']
         ' . ($audios ? ',"audio":[' . preg_replace('/\}\{/m', '},{', $audios) .']' : '') . '
         ' . ($comments ? ',"commentCount":"' . $comments_count . '","comment":[' . preg_replace('/\}\{/m', '},{', $comments) . ']' : '') . '
      }
    </script>
    ';
    }
}
