<!DOCTYPE html>
<html>
 <head>
  <meta name="generator" content="Powered by «Tagra CMS» Development and design by kroloburet@gmail.com">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="none">
  <link href="/UI_fraimwork/css.css" rel="stylesheet">
  <link href="/UI_fraimwork/fonts/FontAwesome/style.css" rel="stylesheet">
  <link href="css.css" rel="stylesheet">
  <!--[if lt IE 8]>
  <link href="/UI_fraimwork/fonts/FontAwesome/ie7/ie7.css" rel="stylesheet">
  <!--<![endif]-->
  <title>Установка системы управления контентом</title>
 </head>
 <body>
  <div id="content">
   <h1>Статус выполнения установки системы</h1>
<?php

///////////////////////////////////////////////////////////////////////////
//подготовка данных
///////////////////////////////////////////////////////////////////////////

$_POST=array_map('trim',$_POST);//убираем пробелы в начале и в конце
$date=date('Y-m-d');//текущая дата
$moment=date('Y-m-d H:i:s');//текущая дата и время
$ip=$_SERVER["REMOTE_ADDR"];//текущий IP
$id=round(microtime(true)*1000);//уникальный id

//база данных
$db_name=$_POST['db_name'];
$db_host=$_POST['db_host'];
$db_user=$_POST['db_user'];
$db_pass=$_POST['db_pass'];
$db_tabl_prefix=$_POST['db_tabl_prefix'].'_';

//Начальные установки системы
$site_name=$_POST['site_name'];
$domen=$_POST['domen'];
$admin_mail=$_POST['admin_mail'];
$moder_mail=$_POST['moder_mail'];

//шифрование логина\пароля

//админ
$admin_name=password_hash($_POST['admin_name'],PASSWORD_BCRYPT);
$admin_pass=password_hash($_POST['admin_pass'],PASSWORD_BCRYPT);

//модератор
$moder_name=password_hash($_POST['moder_name'],PASSWORD_BCRYPT);
$moder_pass=password_hash($_POST['moder_pass'],PASSWORD_BCRYPT);

//сообщения пользователю о статусе установки
$good_msg='<h2 style="margin-top:1.5em">Отлично, вы установили систему!</h2>Инсталлятор сделал свою работу и теперь, для безопасности системы, его необходимо удалить. Нажмите на «Удалить инсталлятор» или удалите вручную папку <b>/instal</b> со всем ее содержимым.<p>Да, вот еще что! Часто бывает, что систему нужно расширять, модифицировать под ваши персональные нужды для повышения эффективности и конверсии сайта. Поэтому, и в целях скромной саморекламы, я, как разработчик системы, внизу страниц сайта оставил свой email, по которому со мной всегда можно связаться — пожалуйта, не удаляйте его. Приятного использования!</p><div class="button"><a href="/instal/destructor.php" class="btn">Удалить инсталлятор</a></div>';
$bad_msg='<h2 style="margin-top:1.5em">Упс! Что-то пошло не так..(</h2><p>Возможно Вы допустили ошибку при заполнении полей с данными доступа к базе или недостаточно прав доступа к файлу или папке, а может это какие-то проблемы с сервером. Вы можете вернуться назад и повторить попытку или обратиться к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p><div class="button"><a href="#" class="btn" onclick="window.history.back();return false">Вернуться назад</a></div>';

//промо-данные для записи в БД
$index_title='Привет, Мир!';
$index_layout_l='<img src="/img/tagra.svg" alt="Tagra CMS">';
$index_layout_r='Добро пожаловать в систему управления контентом &laquo;Tagra&raquo;!<br> Итак. Для быстрого старта вашего сайта &mdash; <a href="/admin">зайдите в админку</a>, используя логин и пароль, созданный вами при установке системы, и начинайте творить..) Но прежде, чтобы облегчить работу с самой системой и верстку контента для вашего сайта, я предлагаю <a href="/UI_fraimwork/info.html" target="_blank">краткое знакомство с системой</a>';
$contact_title='Контакты';

///////////////////////////////////////////////////////////////////////////
//создать таблицы базы данных
///////////////////////////////////////////////////////////////////////////

//подключаемся к серверу и базе
$db=new mysqli($db_host,$db_user,$db_pass,$db_name);
$db->set_charset('utf8');
if(mysqli_connect_error()){die("<div class='notific_r'>Не удалось соединиться с сервером: ".mysqli_connect_error()."</div>".$bad_msg);}
echo"<div class='notific_g'>Соединение с сервером успешно установлено</div>";

//sessions
$t=$db_tabl_prefix.'sessions';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int (10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//languages
$t=$db_tabl_prefix.'languages';
$q_c=$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL,
  `tag` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `def` varchar(20) NOT NULL,
  `public` varchar(20) NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$q_i=$db->query("INSERT INTO `$t` (`id`, `tag`, `title`, `def`) VALUES
($id, 'ru', 'RU', 'on');");
if(!$q_c || !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
}
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//config
$t=$db_tabl_prefix.'config';
$q_c=$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$q_i=$db->query("INSERT INTO `$t` (`name`, `value`) VALUES
('site_access', 'on'),
('site_name', '$site_name'),
('site_mail', '$admin_mail'),
('jq', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'),
('emmet', 'off'),
('gapi_key', ''),
('body_width', '1000'),
('layout_l_width', '60'),
('addthis_js', ''),
('addthis_share', ''),
('addthis_follow', ''),
('addthis_share_def', 'off'),
('addthis_follow_def', 'off'),
('img_prev_def', ''),
('breadcrumbs', '{\"public\":\"on\",\"home\":\"on\"}'),
('markup_data', 'on'),
('sitemap', '{\"generate\":\"auto\",\"allowed\":\"public\"}'),
('comments', '{\"form\":\"on\",\"reserved_names\":\"\",\"rating\":\"on\",\"name_limit\":\"50\",\"text_limit\":\"500\",\"show\":\"3\",\"notific\":\"off\",\"feedback\":\"on\"}');");
if(!$q_c || !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
}
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//back_users
$t=$db_tabl_prefix.'back_users';
$q_c=$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `register_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `last_login_date` varchar(20) NOT NULL,
  `access` varchar(20) NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$q_i=$db->query("INSERT INTO `$t` (`ip`, `status`, `login`, `password`, `email`, `register_date`) VALUES
('$ip', 'administrator', '$admin_name', '$admin_pass', '$admin_mail', '$moment'),
('$ip', 'moderator', '$moder_name', '$moder_pass', '$moder_mail', '$moment');");
if(!$q_c || !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
}
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//index_pages
$t=$db_tabl_prefix.'index_pages';
$q_c=$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `creation_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `layout_l` longtext NOT NULL,
  `layout_r` longtext NOT NULL,
  `layout_b` longtext NOT NULL,
  `layout_l_width` int(4) NOT NULL,
  `links` text NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `lang` varchar(20) NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$q_i=$db->query("INSERT INTO `$t` (`creation_date`, `last_mod_date`, `title`, `description`, `layout_l`, `layout_r`, `layout_l_width`, `lang`) VALUES
('$date', '$date', '$index_title', '$index_title', '$index_layout_l', '$index_layout_r', 15, 'ru');");
if(!$q_c || !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
}
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//contact_pages
$t=$db_tabl_prefix.'contact_pages';
$q_c=$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `creation_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `layout_l` longtext NOT NULL,
  `layout_r` longtext NOT NULL,
  `layout_b` longtext NOT NULL,
  `layout_l_width` int(4) NOT NULL,
  `contacts` text NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `contact_form` varchar(20) NOT NULL DEFAULT 'on',
  `lang` varchar(20) NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$q_i=$db->query("INSERT INTO `$t` (`creation_date`, `last_mod_date`, `title`, `description`, `layout_l_width`, `lang`) VALUES
('$date', '$date', '$contact_title', '$contact_title', 60, 'ru');");
if(!$q_c || !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
}
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//pages
$t=$db_tabl_prefix.'pages';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL,
  `creation_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `alias` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `layout_l` longtext NOT NULL,
  `layout_r` longtext NOT NULL,
  `layout_b` longtext NOT NULL,
  `layout_l_width` int(4) NOT NULL,
  `links` text NOT NULL,
  `section` text NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `comments` varchar(20) NOT NULL DEFAULT 'off',
  `public` varchar(20) NOT NULL DEFAULT 'on',
  `lang` varchar(20) NOT NULL,
  `versions` text NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//gallerys
$t=$db_tabl_prefix.'gallerys';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL,
  `creation_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `alias` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `layout_b` longtext NOT NULL,
  `layout_l` longtext NOT NULL,
  `layout_r` longtext NOT NULL,
  `layout_l_width` int(4) NOT NULL,
  `links` text NOT NULL,
  `section` text NOT NULL,
  `gallery_type` varchar(20) NOT NULL,
  `gallery_opt` longtext NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `comments` varchar(20) NOT NULL DEFAULT 'off',
  `public` varchar(20) NOT NULL DEFAULT 'on',
  `lang` varchar(20) NOT NULL,
  `versions` text NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//sections
$t=$db_tabl_prefix.'sections';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL,
  `creation_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `alias` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `layout_b` longtext NOT NULL,
  `layout_l` longtext NOT NULL,
  `layout_r` longtext NOT NULL,
  `layout_l_width` int(4) NOT NULL,
  `links` text NOT NULL,
  `section` text NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `comments` varchar(20) NOT NULL DEFAULT 'off',
  `public` varchar(20) NOT NULL DEFAULT 'on',
  `lang` varchar(20) NOT NULL,
  `versions` text NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//menu
$t=$db_tabl_prefix.'menu';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `order` int(20) NOT NULL,
  `title` text NOT NULL,
  `url` text NOT NULL,
  `target` varchar(20) NOT NULL DEFAULT '_self',
  `public` varchar(20) NOT NULL DEFAULT 'on',
  `lang` varchar(20) NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

//comments
$t=$db_tabl_prefix.'comments';
$db->query("CREATE TABLE IF NOT EXISTS `$t`(
  `id` bigint(20) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `premod_code` text NOT NULL,
  `ip` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `date` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL,
  `comment` longtext NOT NULL,
  `rating` text NOT NULL,
  `feedback` varchar(20) NOT NULL DEFAULT 'off',
  `public` varchar(20) NOT NULL DEFAULT 'off',
  `lang` varchar(20) NOT NULL,
  PRIMARY KEY (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".$db->error."</div>".$bad_msg);
echo"<div class='notific_g'>Таблица « $t » успешно создана</div>";

$db->close();

///////////////////////////////////////////////////////////////////////////
//создать папки и файлы
///////////////////////////////////////////////////////////////////////////

$cms_path=str_replace('/instal','',dirname(__FILE__));//абсолютный путь к CMS

//config.php
$conf='<?php defined("BASEPATH") OR exit("No direct script access allowed");'.PHP_EOL
      . '$config["app"]=[];'.PHP_EOL
      . '$config["tagra_version"]="3.0";'.PHP_EOL
      . '$config["tagra_instal_date"]=\''.$moment.'\';'.PHP_EOL
      . '$config["base_url"]=\''.$domen.'\';'.PHP_EOL
      . '$config["index_page"]="";'.PHP_EOL
      . '$config["uri_protocol"]="REQUEST_URI";'.PHP_EOL
      . '$config["url_suffix"]="";'.PHP_EOL
      . '$config["language"]="english";'.PHP_EOL
      . '$config["charset"]="UTF-8";'.PHP_EOL
      . '$config["enable_hooks"]=FALSE;'.PHP_EOL
      . '$config["subclass_prefix"]="MY_";'.PHP_EOL
      . '$config["composer_autoload"]=FALSE;'.PHP_EOL
      . '$config["permitted_uri_chars"]="a-z 0-9~%.:_\-";'.PHP_EOL
      . '$config["allow_get_array"]=TRUE;'.PHP_EOL
      . '$config["enable_query_strings"]=FALSE;'.PHP_EOL
      . '$config["controller_trigger"]="c";'.PHP_EOL
      . '$config["function_trigger"]="m";'.PHP_EOL
      . '$config["directory_trigger"]="d";'.PHP_EOL
      . '$config["log_threshold"]=0;'.PHP_EOL
      . '$config["log_path"]="";'.PHP_EOL
      . '$config["log_file_extension"]="";'.PHP_EOL
      . '$config["log_file_permissions"]=0644;'.PHP_EOL
      . '$config["log_date_format"]="Y-m-d H:i:s";'.PHP_EOL
      . '$config["error_views_path"]="";'.PHP_EOL
      . '$config["cache_path"]="";'.PHP_EOL
      . '$config["cache_query_string"]=FALSE;'.PHP_EOL
      . '$config["encryption_key"]="'.uniqid('tagra_').'";'.PHP_EOL
      . '$config["sess_driver"]="database";'.PHP_EOL
      . '$config["sess_save_path"]=\''.$db_tabl_prefix.'sessions\';'.PHP_EOL
      . '$config["sess_cookie_name"]="tagra_session";'.PHP_EOL
      . '$config["sess_expiration"]=0;'.PHP_EOL
      . '$config["sess_match_ip"]=FALSE;'.PHP_EOL
      . '$config["sess_time_to_update"]=300;'.PHP_EOL
      . '$config["sess_regenerate_destroy"]=FALSE;'.PHP_EOL
      . '$config["cookie_prefix"]="";'.PHP_EOL
      . '$config["cookie_domain"]="";'.PHP_EOL
      . '$config["cookie_path"]="/";'.PHP_EOL
      . '$config["cookie_secure"]=FALSE;'.PHP_EOL
      . '$config["cookie_httponly"]=FALSE;'.PHP_EOL
      . '$config["global_xss_filtering"]=FALSE;'.PHP_EOL
      . '$config["csrf_protection"]=FALSE;'.PHP_EOL
      . '$config["csrf_token_name"]="csrf_test_name";'.PHP_EOL
      . '$config["csrf_cookie_name"]="csrf_cookie_name";'.PHP_EOL
      . '$config["csrf_expire"]=7200;'.PHP_EOL
      . '$config["csrf_expire"]=TRUE;'.PHP_EOL
      . '$config["csrf_exclude_uris"]=[];'.PHP_EOL
      . '$config["compress_output"]=FALSE;'.PHP_EOL
      . '$config["time_reference"]="local";'.PHP_EOL
      . '$config["rewrite_short_tags"]=TRUE;'.PHP_EOL
      . '$config["proxy_ips"]="";';

//database.php
$conf_db='<?php defined("BASEPATH") OR exit("No direct script access allowed");'.PHP_EOL
         . '$active_group="default";'.PHP_EOL
         . '$query_builder=TRUE;'.PHP_EOL
         . '$db["default"]["dsn"]="";'.PHP_EOL
         . '$db["default"]["hostname"]=\''.$db_host.'\';'.PHP_EOL
         . '$db["default"]["username"]=\''.$db_user.'\';'.PHP_EOL
         . '$db["default"]["password"]=\''.$db_pass.'\';'.PHP_EOL
         . '$db["default"]["database"]=\''.$db_name.'\';'.PHP_EOL
         . '$db["default"]["dbprefix"]=\''.$db_tabl_prefix.'\';'.PHP_EOL
         . '$db["default"]["dbdriver"]="mysqli";'.PHP_EOL
         . '$db["default"]["pconnect"]=FALSE;'.PHP_EOL
         . '$db["default"]["db_debug"]=TRUE;'.PHP_EOL
         . '$db["default"]["cache_on"]=FALSE;'.PHP_EOL
         . '$db["default"]["cachedir"]="";'.PHP_EOL
         . '$db["default"]["char_set"]="utf8";'.PHP_EOL
         . '$db["default"]["dbcollat"]="utf8_general_ci";'.PHP_EOL
         . '$db["default"]["swap_pre"]="";'.PHP_EOL
         . '$db["default"]["encrypt"]=FALSE;'.PHP_EOL
         . '$db["default"]["compress"]=FALSE;'.PHP_EOL
         . '$db["default"]["stricton"]=FALSE;'.PHP_EOL
         . '$db["default"]["failover"]=[];'.PHP_EOL
         . '$db["default"]["save_queries"]=TRUE;';

//sitemap.xml
$sitemap='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
          .'<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->'.PHP_EOL
          .'<!-- Generator: Tagra CMS -->';

//директория для загрузки медиа
if(!mkdir($cms_path.'/upload',0750)){
die("<div class='notific_r'>Не удалось создать директорию « /upload » для загрузки медиа</div>".$bad_msg);
}
echo"<div class='notific_g'>Директория « /upload » для загрузки медиа успешно создана</div>";

//директория для языка ru (по умолчанию)
if(!mkdir($cms_path.'/upload/ru',0750)){
die("<div class='notific_r'>Не удалось создать директорию « /upload/ru » загрузки медиа языка по умолчанию</div>".$bad_msg);
}
echo"<div class='notific_g'>Директория « /upload/ru » загрузки медиа языка по умолчанию успешно создана</div>";

//файл конфигурации CMS
if(!file_put_contents($cms_path.'/application/config/config.php',$conf)){
die("<div class='notific_r'>Не удалось создать файл конфигурации CMS « /application/config/config.php »</div>".$bad_msg);
}
echo"<div class='notific_g'>Файл конфигурации CMS « /application/config/config.php » успешно создан</div>";

//файл конфигурации базы данных CMS
if(!file_put_contents($cms_path.'/application/config/database.php',$conf_db)){
die("<div class='notific_r'>Не удалось создать файл конфигурации базы данных CMS « /application/config/database.php »</div>".$bad_msg);
}
echo"<div class='notific_g'>Файл конфигурации базы данных CMS « /application/config/database.php » успешно создан</div>";

//файл карты сайта
if(!file_put_contents($cms_path.'/sitemap.xml',$sitemap)){
die("<div class='notific_r'>Не удалось создать файл карты сайта « /sitemap.xml »</div>".$bad_msg);
}
echo"<div class='notific_g'>Файл карты сайта « /sitemap.xml » успешно создан</div>";

//файл index.php для корня
if(!copy($cms_path.'/instal/root_index.php',$cms_path.'/index.php')){
die("<div class='notific_r'>Не удалось создать файл индекса CMS « /index.php »</div>".$bad_msg);
}
echo"<div class='notific_g'>Файл индекса CMS « /index.php » успешно создан</div>";

///////////////////////////////////////////////////////////////////////////
//установка завершена
///////////////////////////////////////////////////////////////////////////

echo$good_msg;
?>

   <div id="copy">Веб-разработка и дизайн<a href="mailto:kroloburet@gmail.com"> <img src="/img/i.jpg" alt="kroloburet"> kroloburet@gmail.com</a><br>
 <img src="/img/tagra_min.svg" alt="Tagra CMS"> Tagra CMS</div>
  </div>
 </body>
</html>