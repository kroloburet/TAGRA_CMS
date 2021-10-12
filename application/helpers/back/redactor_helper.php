<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$data = $CI->app('data');
$pages = $CI->db->where('lang', $data['lang'])->select('title, id')->order_by('title')->get('pages')->result_array();
$sections = $CI->db->where('lang', $data['lang'])->select('title, id')->order_by('title')->get('sections')->result_array();
$galleries = $CI->db->where('lang', $data['lang'])->select('title, id')->order_by('title')->get('galleries')->result_array();
?>

<!--
########### Редактор
-->

<script src="/scripts/libs/tinymce_4.7.11/tinymce.min.js"></script>
<script>

    /**
     * Список ссылок материалов
     */
    const mce_link_list = [

        <?php if ($pages) { ?>
        {
            title: "Страницы", menu: [
                <?php foreach ($pages as $i) { ?>
                {title: <?= json_encode($i['title']) ?>, value: "<?= '/page/' . $i['id'] ?>"},
                <?php } ?>
            ]
        },
        <?php } ?>

        <?php if ($sections) { ?>
        {
            title: "Разделы", menu: [
                <?php foreach ($sections as $i) { ?>
                {title: <?= json_encode($i['title']) ?>, value: "<?= '/section/' . $i['id'] ?>"},
                <?php } ?>
            ]
        },
        <?php } ?>

        <?php if ($galleries) { ?>
        {
            title: "Галереи", menu: [
                <?php foreach ($galleries as $i) { ?>
                {title: <?= json_encode($i['title']) ?>, value: "<?= '/gallery/' . $i['id'] ?>"},
                <?php } ?>
            ]
        },
        <?php } ?>

        {title: "Главная", value: "/"},
        {title: "Контакты", value: "/contact"},
    ];

    /**
     * Конфигурация редактора по умолчанию
     */
    const mce_overall_conf = {
        // content_css:"/css/back/redactor.css",// стили для редактируемого контента
        language: "ru", // язык редактора
        menubar: false,
        element_format: "html", // теги в формате
        code_dialog_width: 800,
        relative_urls: false, // относительные или абсолютные урлы
        remove_script_host: true,
        style_formats_merge: true, // добавлять или нет свои классы к классам по умолчанию в меню "формат"
        browser_spellcheck: true, // проверка орфографии
        valid_elements: "*[*]", // разрешенные
        //allow_script_urls: true,// разрешить\запретить внешние скрипты
        //invalid_elements:"strong,em",// запрещенные
        //extended_valid_elements:"img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]",// добавить разрешенные
        image_advtab: true, // расширенный диалог вставки изображения
        image_title: true,
        image_class_list: [// предустановленные классы для картинок
            {title: 'Не применять', value: ''},
            {title: 'По центру', value: 'TUI_to-c'},
            {title: 'Справа', value: 'TUI_to-r'},
            {title: 'Слева', value: 'TUI_to-l'},
            {title: 'Не обтекать', value: 'TUI_to-non'}
        ],
        plugins: "advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code textcolor media table contextmenu paste nonbreaking moxiemanager fullscreen",
        toolbar: "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table charmap link image media insertfile | fullscreen code",
        link_list: mce_link_list,
        // настройки файлового менеджера
        moxiemanager_rootpath: '/upload',
        moxiemanager_title: 'Mенеджер файлов',
        moxiemanager_view: 'thumbs',
        moxiemanager_leftpanel: false
    };
</script>
