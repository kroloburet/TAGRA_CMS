<!DOCTYPE html>
<html>
<head>
    <meta name="generator"
          content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/TUI/TUI.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Установка системы управления контентом</title>
</head>
<body>
<div id="content">
    <h1>Статус выполнения установки системы</h1>

    <?php

    /**
     * Подготовить данные
     */

    $_POST = array_map('trim', $_POST); // убирать пробелы в начале и конце
    $datetime = date('Y-m-d H:i:s'); // текущая дата и время
    $ip = $_SERVER["REMOTE_ADDR"]; // текущий IP
    $id = round(microtime(true) * 1000); // уникальный id

    // база данных
    $db_name = $_POST['db_name'];
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_tabl_prefix = $_POST['db_tabl_prefix'] . '_';

    // начальные установки системы
    $site_name = $_POST['site_name'];
    $domain = $_POST['domain'];
    $admin_mail = $_POST['admin_mail'];

    // шифрование логина\пароля
    $admin_name = password_hash($_POST['admin_name'], PASSWORD_BCRYPT);
    $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_BCRYPT);

    // сообщения пользователю о статусе установки
    $good_msg = '<h2 style="margin-top:1.5em">Отлично, вы установили систему!</h2>Инсталлятор сделал свою работу и теперь, для безопасности системы, его необходимо удалить. Нажмите на <q>Удалить инсталлятор</q> или удалите вручную каталог <b>/instal</b> со всем ее содержимым.<p>Да, вот еще что! Часто бывает, что систему нужно расширять, модифицировать под ваши персональные нужды для повышения эффективности и конверсии сайта. Поэтому, и в целях скромной саморекламы, я, как разработчик системы, внизу страниц сайта оставил свой email, по которому со мной всегда можно связаться &mdash; пожалуйста, не удаляйте его. Приятного использования!</p><div class="TUI_fieldset"><a href="/instal/destructor.php" class="TUI_btn-link">Удалить инсталлятор</a></div>';
    $bad_msg = '<h2 style="margin-top:1.5em">Упс! Что-то пошло не так..(</h2><p>Возможно вы допустили ошибку при заполнении полей с данными доступа к базе или недостаточно прав доступа к файлу или каталогу, а может это какие-то проблемы с сервером. Вы можете вернуться назад и повторить попытку или обратиться к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p><div class="TUI_fieldset"><a href="#" class="TUI_btn-link" onclick="window.history.back();return false">Вернуться назад</a></div>';

    // промо-данные для записи в БД
    $index_title = 'Привет, Мир!';
    $index_content_l = '<p><img src="/img/tagra_share.svg" alt="Tagra CMS"></p>';
    $index_content_r = '<p>Добро пожаловать в систему управления контентом <q>Tagra</q>!<br> Итак. Для быстрого старта вашего сайта &mdash; <a href="/admin">зайдите в админку</a>, используя логин и пароль, созданный вами при установке системы, и начинайте творить..) Но прежде, предлагаю ознакомиться с короткой справкой <a href="/about-Tagra.html" target="_blank">о системе</a> и руководству <a href="/TUI" target="_blank">для верстальщика</a>.</p>';
    $contact_title = 'Контакты';

    /**
     * Создать таблицы БД
     */

    // подключение
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($db->connect_errno) {
        die("<div class='TUI_notice-error'>Не удалось соединиться с сервером: $db->connect_error</div>$bad_msg");
    }
    $db->set_charset('utf8');
    echo "<div class='TUI_notice-success'>Соединение с сервером успешно установлено</div>";

    // sessions
    $t = $db_tabl_prefix . 'sessions';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` varchar(128) NOT NULL,
`ip_address` varchar(45) NOT NULL,
`timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
`data` blob NOT NULL,
PRIMARY KEY (`id`),
KEY `ci_sessions_timestamp` (`timestamp`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    // languages
    $t = $db_tabl_prefix . 'languages';
    $q_c = $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` bigint UNSIGNED NOT NULL,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`tag` varchar(10) NOT NULL,
`title` varchar(20) NOT NULL,
`def` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
PRIMARY KEY (`id`,`tag`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    $q_i = $db->query("INSERT INTO `$t` (`id`,`creation_date`,`last_mod_date`,`tag`,`title`,`def`) VALUES
($id,'$datetime','$datetime','ru','RU',1);");
    if (!$q_c) {
        die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";
    if (!$q_i) {
        die("<div class='TUI_notice-error'>Не удалось записать начальные данные в таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Начальные данные успешно записаны в таблицу <q>$t</q></div>";

    // config
    $t = $db_tabl_prefix . 'config';
    $q_c = $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`name` varchar(100) NOT NULL,
`value` text NOT NULL,
PRIMARY KEY (`name`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    $q_i = $db->query("INSERT INTO `$t` (`name`,`value`) VALUES
('site_access','1'),
('site_name','$site_name'),
('site_mail','$admin_mail'),
('jq','https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'),
('emmet','0'),
('gapi_key',''),
('body_width','1000'),
('content_l_width','60'),
('addthis','{\"js\":\"\",\"share\":\"\",\"follow\":\"\",\"share_def\":\"1\",\"follow_def\":\"1\"}'),
('img_prev_def','{$domain}img/tagra_share.jpg'),
('breadcrumbs','{\"public\":\"1\",\"home\":\"1\"}'),
('markup_data','1'),
('sitemap','{\"generate\":\"auto\",\"allowed\":\"public\"}'),
('comments','{\"form\":\"on\",\"reserved_names\":\"\",\"rating\":\"1\",\"name_limit\":\"50\",\"text_limit\":\"500\",\"show\":\"3\",\"notific\":\"off\",\"feedback\":\"1\"}');");
    if (!$q_c) {
        die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";
    if (!$q_i) {
        die("<div class='TUI_notice-error'>Не удалось записать начальные данные в таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Начальные данные успешно записаны в таблицу <q>$t</q></div>";

    // back_users
    $t = $db_tabl_prefix . 'back_users';
    $q_c = $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`last_login_date` datetime NOT NULL,
`ip` varchar(45) NOT NULL,
`status` varchar(45) NOT NULL,
`login` text NOT NULL,
`password` text NOT NULL,
`email` varchar(100) NOT NULL,
`access` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    $q_i = $db->query("INSERT INTO `$t` (`creation_date`,`last_mod_date`,`last_login_date`,`ip`,`status`,`login`,`password`,`email`) VALUES
('$datetime','$datetime','$datetime','$ip','administrator','$admin_name','$admin_pass','$admin_mail');");
    if (!$q_c) {
        die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";
    if (!$q_i) {
        die("<div class='TUI_notice-error'>Не удалось записать начальные данные в таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Начальные данные успешно записаны в таблицу <q>$t</q></div>";

    // index_pages
    $t = $db_tabl_prefix . 'index_pages';
    $q_c = $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`title` varchar(250) NOT NULL,
`description` varchar(250) NOT NULL,
`robots` varchar(45) NOT NULL DEFAULT 'all',
`css` text NOT NULL,
`js` text NOT NULL,
`content_t` text NOT NULL,
`content_l` text NOT NULL,
`content_r` text NOT NULL,
`content_b` text NOT NULL,
`content_l_width` int(4) UNSIGNED NOT NULL,
`addthis_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`addthis_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`img_prev` text NOT NULL,
`lang` varchar(10) NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    $q_i = $db->query("INSERT INTO `$t` (`creation_date`,`last_mod_date`,`title`,`description`,`css`,`js`,`content_t`,`content_l`,`content_r`,`content_b`,`content_l_width`,`img_prev`,`lang`) VALUES
('$datetime','$datetime','$index_title','$index_title','','','','$index_content_l','$index_content_r','',25,'{$domain}img/tagra_share.jpg','ru');");
    if (!$q_c) {
        die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";
    if (!$q_i) {
        die("<div class='TUI_notice-error'>Не удалось записать начальные данные в таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Начальные данные успешно записаны в таблицу <q>$t</q></div>";

    // contact_pages
    $t = $db_tabl_prefix . 'contact_pages';
    $q_c = $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`title` varchar(250) NOT NULL,
`description` varchar(250) NOT NULL,
`robots` varchar(45) NOT NULL DEFAULT 'all',
`css` text NOT NULL,
`js` text NOT NULL,
`content_t` text NOT NULL,
`content_l` text NOT NULL,
`content_r` text NOT NULL,
`content_b` text NOT NULL,
`content_l_width` int(4) UNSIGNED NOT NULL,
`contacts` text NOT NULL,
`addthis_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`addthis_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`img_prev` text NOT NULL,
`contact_form` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`lang` varchar(10) NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
    $q_i = $db->query("INSERT INTO `$t` (`creation_date`,`last_mod_date`,`title`,`description`,`css`,`js`,`content_t`,`content_l`,`content_r`,`content_b`,`content_l_width`,`contacts`,`img_prev`,`lang`) VALUES
('$datetime','$datetime','$contact_title','$contact_title','','','','','','',60,'','{$domain}img/tagra_share.jpg','ru');");
    if (!$q_c) {
        die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";
    if (!$q_i) {
        die("<div class='TUI_notice-error'>Не удалось записать начальные данные в таблицу <q>$t</q>: $db->error</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Начальные данные успешно записаны в таблицу <q>$t</q></div>";

    // pages
    $t = $db_tabl_prefix . 'pages';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` varchar(250) NOT NULL,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`title` varchar(250) NOT NULL,
`description` varchar(250) NOT NULL,
`robots` varchar(45) NOT NULL DEFAULT 'all',
`css` text NOT NULL,
`js` text NOT NULL,
`content_t` text NOT NULL,
`content_l` text NOT NULL,
`content_r` text NOT NULL,
`content_b` text NOT NULL,
`content_l_width` int(4) UNSIGNED NOT NULL,
`section` varchar(250) NOT NULL,
`addthis_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`addthis_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`img_prev` text NOT NULL,
`comments` varchar(20) NOT NULL DEFAULT 'off',
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`lang` varchar(10) NOT NULL,
`versions` text NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    // galleries
    $t = $db_tabl_prefix . 'galleries';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` varchar(250) NOT NULL,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`title` varchar(250) NOT NULL,
`description` varchar(250) NOT NULL,
`robots` varchar(45) NOT NULL DEFAULT 'all',
`css` text NOT NULL,
`js` text NOT NULL,
`content_t` text NOT NULL,
`content_b` text NOT NULL,
`content_l` text NOT NULL,
`content_r` text NOT NULL,
`content_l_width` int(4) UNSIGNED NOT NULL,
`section` varchar(250) NOT NULL,
`gallery_type` varchar(20) NOT NULL,
`gallery_opt` text NOT NULL,
`addthis_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`addthis_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`img_prev` text NOT NULL,
`comments` varchar(20) NOT NULL DEFAULT 'off',
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`lang` varchar(10) NOT NULL,
`versions` text NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    // sections
    $t = $db_tabl_prefix . 'sections';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` varchar(250) NOT NULL,
`creation_date` datetime NOT NULL,
`last_mod_date` datetime NOT NULL,
`title` varchar(250) NOT NULL,
`description` varchar(250) NOT NULL,
`robots` varchar(45) NOT NULL DEFAULT 'all',
`css` text NOT NULL,
`js` text NOT NULL,
`content_t` text NOT NULL,
`content_b` text NOT NULL,
`content_l` text NOT NULL,
`content_r` text NOT NULL,
`content_l_width` int(4) UNSIGNED NOT NULL,
`section` varchar(250) NOT NULL,
`addthis_share` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`addthis_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`img_prev` text NOT NULL,
`comments` varchar(20) NOT NULL DEFAULT 'off',
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`lang` varchar(10) NOT NULL,
`versions` text NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    // menu
    $t = $db_tabl_prefix . 'menu';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
`pid` int UNSIGNED NOT NULL,
`order` int UNSIGNED NOT NULL,
`title` varchar(250) NOT NULL,
`url` text NOT NULL,
`target` varchar(10) NOT NULL DEFAULT '_self',
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
`lang` varchar(10) NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    // comments
    $t = $db_tabl_prefix . 'comments';
    $db->query("CREATE TABLE IF NOT EXISTS `$t`(
`id` bigint UNSIGNED NOT NULL,
`pid` bigint UNSIGNED NOT NULL,
`premod_code` text NOT NULL,
`ip` varchar(45) NOT NULL,
`url` text NOT NULL,
`creation_date` datetime NOT NULL,
`name` varchar(100) NOT NULL,
`comment` text NOT NULL,
`rating` text NOT NULL,
`feedback` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
`public` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
`lang` varchar(10) NOT NULL,
PRIMARY KEY (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;")
    or die("<div class='TUI_notice-error'>Не удалось создать таблицу <q>$t</q>: $db->error</div>$bad_msg");
    echo "<div class='TUI_notice-success'>Таблица <q>$t</q> успешно создана</div>";

    $db->close();

    /**
     * Создать каталоги и файлы
     */

    // абсолютный путь к CMS
    $cms_path = str_replace('/instal', '', dirname(__FILE__));

    // config.php
    $conf = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL
        . '$config["app"] = [];' . PHP_EOL
        . '$config["tagra_version"] = "4.5";' . PHP_EOL
        . '$config["tagra_instal_date"] = "' . $datetime . '";' . PHP_EOL
        . '$config["base_url"] = "' . $domain . '";' . PHP_EOL
        . '$config["index_page"] = "";' . PHP_EOL
        . '$config["uri_protocol"] = "REQUEST_URI";' . PHP_EOL
        . '$config["url_suffix"] = "";' . PHP_EOL
        . '$config["language"] = "english";' . PHP_EOL
        . '$config["charset"] = "UTF-8";' . PHP_EOL
        . '$config["enable_hooks"] = false;' . PHP_EOL
        . '$config["subclass_prefix"] = "MY_";' . PHP_EOL
        . '$config["composer_autoload"] = false;' . PHP_EOL
        . '$config["permitted_uri_chars"] = "a-z 0-9~%.:_\-";' . PHP_EOL
        . '$config["allow_get_array"] = true;' . PHP_EOL
        . '$config["enable_query_strings"] = false;' . PHP_EOL
        . '$config["controller_trigger"] = "c";' . PHP_EOL
        . '$config["function_trigger"] = "m";' . PHP_EOL
        . '$config["directory_trigger"] = "d";' . PHP_EOL
        . '$config["log_threshold"] = 0;' . PHP_EOL
        . '$config["log_path"] = "";' . PHP_EOL
        . '$config["log_file_extension"] = "";' . PHP_EOL
        . '$config["log_file_permissions"] = 0644;' . PHP_EOL
        . '$config["log_date_format"] = "Y-m-d H:i:s";' . PHP_EOL
        . '$config["error_views_path"] = "";' . PHP_EOL
        . '$config["cache_path"] = "";' . PHP_EOL
        . '$config["cache_query_string"] = false;' . PHP_EOL
        . '$config["encryption_key"] = "' . uniqid('tagra_') . '";' . PHP_EOL
        . '$config["sess_driver"] = "database";' . PHP_EOL
        . '$config["sess_save_path"] = "' . $db_tabl_prefix . 'sessions";' . PHP_EOL
        . '$config["sess_cookie_name"] = "tagra_session";' . PHP_EOL
        . '$config["sess_expiration"] = 0;' . PHP_EOL
        . '$config["sess_match_ip"] = false;' . PHP_EOL
        . '$config["sess_time_to_update"] = 300;' . PHP_EOL
        . '$config["sess_regenerate_destroy"] = false;' . PHP_EOL
        . '$config["cookie_prefix"] = "";' . PHP_EOL
        . '$config["cookie_domain"] = "";' . PHP_EOL
        . '$config["cookie_path"] = "/";' . PHP_EOL
        . '$config["cookie_secure"] = false;' . PHP_EOL
        . '$config["cookie_httponly"] = false;' . PHP_EOL
        . '$config["global_xss_filtering"] = false;' . PHP_EOL
        . '$config["csrf_protection"] = false;' . PHP_EOL
        . '$config["csrf_token_name"] = "csrf_test_name";' . PHP_EOL
        . '$config["csrf_cookie_name"] = "csrf_cookie_name";' . PHP_EOL
        . '$config["csrf_expire"] = 7200;' . PHP_EOL
        . '$config["csrf_expire"] = true;' . PHP_EOL
        . '$config["csrf_exclude_uris"] = [];' . PHP_EOL
        . '$config["compress_output"] = false;' . PHP_EOL
        . '$config["time_reference"] = "local";' . PHP_EOL
        . '$config["rewrite_short_tags"] = true;' . PHP_EOL
        . '$config["proxy_ips"] = "";';

    // database.php
    $conf_db = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL
        . '$active_group = "default";' . PHP_EOL
        . '$query_builder = true;' . PHP_EOL
        . '$db["default"]["dsn"] = "";' . PHP_EOL
        . '$db["default"]["hostname"] = "' . $db_host . '";' . PHP_EOL
        . '$db["default"]["username"] = "' . $db_user . '";' . PHP_EOL
        . '$db["default"]["password"] = "' . $db_pass . '";' . PHP_EOL
        . '$db["default"]["database"] = "' . $db_name . '";' . PHP_EOL
        . '$db["default"]["dbprefix"] = "' . $db_tabl_prefix . '";' . PHP_EOL
        . '$db["default"]["dbdriver"] = "mysqli";' . PHP_EOL
        . '$db["default"]["pconnect"] = false;' . PHP_EOL
        . '$db["default"]["db_debug"] = true;' . PHP_EOL
        . '$db["default"]["cache_on"] = false;' . PHP_EOL
        . '$db["default"]["cachedir"] = "";' . PHP_EOL
        . '$db["default"]["char_set"] = "utf8";' . PHP_EOL
        . '$db["default"]["dbcollat"] = "utf8_bin";' . PHP_EOL
        . '$db["default"]["swap_pre"] = "";' . PHP_EOL
        . '$db["default"]["encrypt"] = false;' . PHP_EOL
        . '$db["default"]["compress"] = false;' . PHP_EOL
        . '$db["default"]["stricton"] = false;' . PHP_EOL
        . '$db["default"]["failover"] = [];' . PHP_EOL
        . '$db["default"]["save_queries"] = true;';

    // sitemap.xml
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
        . '<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->' . PHP_EOL
        . '<!-- Generator: Tagra CMS -->' . PHP_EOL
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL
        . '<!-- Home page -->' . PHP_EOL
        . '<url><loc>' . $domain . '</loc></url>' . PHP_EOL
        . '<!-- Contact page -->' . PHP_EOL
        . '<url><loc>' . $domain . 'contact</loc></url>' . PHP_EOL
        . '<!-- Pages -->' . PHP_EOL
        . '<!-- Sections -->' . PHP_EOL
        . '<!-- Galleries -->' . PHP_EOL
        . '</urlset>';

    // каталог для загрузки медиа
    if (!mkdir($cms_path . '/upload', 0755)) {
        die("<div class='TUI_notice-error'>Не удалось создать каталог <q>/upload</q> для загрузки медиа</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Каталог <q>/upload</q> для загрузки медиа успешно создан</div>";

    // каталог для языка ru (по умолчанию)
    if (!mkdir($cms_path . '/upload/ru', 0755)) {
        die("<div class='TUI_notice-error'>Не удалось создать каталог <q>/upload/ru</q> загрузки медиа языка по умолчанию</div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Каталог <q>/upload/ru</q> загрузки медиа языка по умолчанию успешно создан</div>";

    // файл конфигурации CMS
    if (!file_put_contents($cms_path . '/application/config/config.php', $conf)) {
        die("<div class='TUI_notice-error'>Не удалось создать файл конфигурации CMS <q>/application/config/config.php</q></div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Файл конфигурации CMS <q>/application/config/config.php</q> успешно создан</div>";

    // файл конфигурации базы данных CMS
    if (!file_put_contents($cms_path . '/application/config/database.php', $conf_db)) {
        die("<div class='TUI_notice-error'>Не удалось создать файл конфигурации базы данных CMS "
            . "<q>/application/config/database.php</q></div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Файл конфигурации базы данных CMS <q>/application/config/database.php</q> успешно создан</div>";

    // файл карты сайта
    if (!file_put_contents($cms_path . '/sitemap.xml', $sitemap)) {
        die("<div class='TUI_notice-error'>Не удалось создать файл карты сайта <q>/sitemap.xml</q></div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Файл карты сайта <q>/sitemap.xml</q> успешно создан</div>";

    // файл index.php для корня
    if (!copy($cms_path . '/instal/root_index.php', $cms_path . '/index.php')) {
        die("<div class='TUI_notice-error'>Не удалось скопировать содержимое файла "
            . "<q>/instal/root_index.php</q> в файл <q>/index.php</q></div>$bad_msg");
    }
    echo "<div class='TUI_notice-success'>Содержимое для файла индекса CMS <q>/index.php</q> успешно скопировано</div>";

    /**
     * Установка завершена
     */

    echo $good_msg;
    ?>

    <!--
    ########### Footer
    -->

    <div id="copy">
        Веб-разработка и дизайн
        <a href="mailto:kroloburet@gmail.com">
            <img src="/img/i.jpg" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com
        </a><br>
        <img src="/img/tagra.svg" alt="Tagra CMS"> Tagra CMS
    </div>
</div>
</body>
</html>
