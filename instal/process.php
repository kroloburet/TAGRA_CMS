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

$_POST=array_map('trim',$_POST);//убираем пробелы в начале и в конце
$moment=date('Y-m-d H:i:s');//текущая дата и время
$ip=$_SERVER["REMOTE_ADDR"];//текущий IP

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
$salt_pref='$2a$';//префикс соли по умолчанию
$salt_pref=(version_compare(PHP_VERSION, '5.3.7')>= 0)?'$2y$':'$2a$';//если версия не ниже 5.3.7

//админ
$admin_salt=$salt_pref.'10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22).'$';
$admin_name=crypt($_POST['admin_name'],$admin_salt);
$admin_pass=crypt($_POST['admin_pass'],$admin_salt);

//модератор
$moder_salt=$salt_pref.'10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22).'$';
$moder_name=crypt($_POST['moder_name'],$moder_salt);
$moder_pass=crypt($_POST['moder_pass'],$moder_salt);

//сообщения пользователю
$good_msg='<h2 style="margin-top:1.5em">Отлично, вы установили систему!</h2>Теперь, с целью безопасности системы, удалите папку <b>instal/</b> со всем ее содержимым,<br>установите права (755) на папки:<ul><li><b>application/</b></li><li><b>application/config/</b></li></ul>права (644) на файлы:<ul><li><b>application/config/config.php</b></li><li><b>application/config/database.php</b></li></ul> Для работы файлового менеджера установите права на папку <b>upload/ (777)</b>, а для генерирования карты сайта — права на файл <b>sitemap.xml (777)</b><br>Вот и все. Скорее жмите кнопку «На главную страницу»..)<p>Да, вот еще что! Часто бывает, что систему нужно расширять, модифицировать под ваши персональные нужды для повышения эффективности и конверсии сайта. Поэтому, и в целях скромной саморекламы, я, как разработчик системы, внизу страниц сайта оставил свой email, по которому со мной всегда можно связаться — пожалуйта, не удаляйте его. Приятного использования!</p><a href="'.$domen.'" class="btn">На главную страницу</a>';
$bad_msg='<h2 style="margin-top:1.5em">Упс! Что-то пошло не так..(</h2><p>Вы можете повторить попытку, нажав кнопку «Попробовать снова», или обратиться к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p><a href="index.php" class="btn">Попробовать снова</a>';
$index_promo='<div class="row"><div class="col2 algn_c"><img src="'.$domen.'img/logo_tagra.svg" alt="Tagra CMS" title="Tagra CMS"></div><div class="col7"><p>Добро пожаловать в систему управления контентом &laquo;Tagra&raquo;!<br> Итак. Для быстрого старта вашего сайта &mdash; <a href="'.$domen.'admin/">зайдите в админку</a>, используя логин и пароль, созданный вами при установке системы, и начинайте творить..) Но прежде, чтобы облегчить работу с самой системой и верстку контента для вашего сайта, я предлагаю <a href="'.$domen.'UI_fraimwork/info.html" target="_blank">краткое знакомство с системой</a></p></div></div>
';

//подключаемся к серверу
$db=@mysql_connect($db_host, $db_user, $db_pass)
or die("<div class='notific_r'>Не удалось соединиться с сервером: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Соединение с сервером успешно установлено</div>";

//подключаемся к базе
@mysql_select_db($db_name, $db)
or die("<div class='notific_r'>Не удалось соединиться с базой: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Соединение с базой « $db_name » успешно установлено</div>";

//создаем таблицы:

///////////////////////////////////////////////////////////////////////////////////////////////////_sessions
$t=$db_tabl_prefix.'sessions';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int (10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_comments
$t=$db_tabl_prefix.'comments';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `premod_code` text NOT NULL,
  `url` text NOT NULL,
  `date` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL,
  `comment` longtext NOT NULL,
  `public` varchar(20) NOT NULL DEFAULT 'off',
  PRIMARY KEY (`id`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_gallerys
$t=$db_tabl_prefix.'gallerys';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `type` varchar(20) NOT NULL,
  `opt` longtext NOT NULL,  
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `img_prev` text NOT NULL,
  `comments` varchar(20) NOT NULL DEFAULT 'off',
  `public` varchar(20) NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_sections
$t=$db_tabl_prefix.'sections';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_pages
$t=$db_tabl_prefix.'pages';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_menu
$t=$db_tabl_prefix.'menu';
@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `url` text NOT NULL,
  `target` varchar(20) NOT NULL DEFAULT '_self',
  `order` int(20) NOT NULL,
  `public` varchar(20) NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`));")
or die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_index_page
$t=$db_tabl_prefix.'index_page';
$q_c=@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`));");
$q_i=@mysql_query("INSERT INTO `$t` (`title`, `description`, `robots`, `css`, `js`, `layout_t`, `layout_l`, `layout_r`, `layout_b`, `layout_l_width`, `links`, `addthis_share`, `addthis_follow`) VALUES
('Привет, Мир!', 'Привет, Мир!', 'all', '', '', '$index_promo', '', '', '', 60, '',  'off', 'off');");
if(!$q_c && !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
}
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_contact_page
$t=$db_tabl_prefix.'contact_page';
$q_c=@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `robots` varchar(100) NOT NULL DEFAULT 'all',
  `css` text NOT NULL,
  `js` text NOT NULL,
  `layout_t` longtext NOT NULL,
  `mail` text NOT NULL,
  `tel` text NOT NULL,
  `skype` text NOT NULL,
  `qr` text NOT NULL,
  `address` text NOT NULL,
  `addthis_share` varchar(20) NOT NULL DEFAULT 'off',
  `addthis_follow` varchar(20) NOT NULL DEFAULT 'off',
  `contact_form` varchar(20) NOT NULL DEFAULT 'on',
  PRIMARY KEY (`id`));");
$q_i=@mysql_query("INSERT INTO `$t` (`title`, `description`, `robots`, `css`, `js`, `layout_t`, `mail`, `tel`, `skype`, `qr`, `address`, `addthis_share`, `addthis_follow`, `contact_form`) VALUES
('Контакты', 'Мои контакты', 'all', '', '', '', '$admin_mail', '', '', '', '', 'off', 'off', 'on');");
if(!$q_c && !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
}
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_my_config
$t=$db_tabl_prefix.'my_config';
$q_c=@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`));");
$q_i=@mysql_query("INSERT INTO `$t` (`name`, `value`) VALUES
('conf_site_access', 'on'),
('conf_jq', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'),
('conf_site_name', '$site_name'),
('conf_site_mail', '$admin_mail'),
('conf_body_width', '1000'),
('conf_layout_l_width', '60'),
('conf_addthis_js', ''),
('conf_addthis_share', ''),
('conf_addthis_follow', ''),
('conf_addthis_share_def', 'off'),
('conf_addthis_follow_def', 'off'),
('conf_breadcrumbs_public', 'on'),
('conf_breadcrumbs_home', 'Home'),
('conf_emmet', 'off'),
('conf_comment_notific', 'off');");
if(!$q_c && !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
}
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_sitemap_config
$t=$db_tabl_prefix.'sitemap_config';
$q_c=@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`));");
$q_i=@mysql_query("INSERT INTO `$t` (`name`, `value`) VALUES
('allowed', 'all'),
('generate', 'auto');");
if(!$q_c && !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
}
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////_back_users
$t=$db_tabl_prefix.'back_users';
$q_c=@mysql_query("CREATE TABLE IF NOT EXISTS `$t` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `email` text NOT NULL,
  `register_date` varchar(20) NOT NULL,
  `last_mod_date` varchar(20) NOT NULL,
  `last_login_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`));");
$q_i=@mysql_query("INSERT INTO `$t` (`ip`, `status`, `login`, `password`, `salt`, `email`, `register_date`) VALUES
('$ip', 'administrator', '$admin_name', '$admin_pass', '$admin_salt', '$admin_mail', '$moment'),
('$ip', 'moderator', '$moder_name', '$moder_pass', '$moder_salt', '$moder_mail', '$moment');");
if(!$q_c && !$q_i){
die("<div class='notific_r'>Не удалось создать таблицу « $t »: ".mysql_error()."</div>".$bad_msg);
}
echo "<div class='notific_g'>Таблица « $t » успешно создана</div>";

//перезаписываем файлы
//открываем файл в режиме чтения
$cms_path=str_replace('/instal', '', dirname(__FILE__));//абсолютный путь к директории с CMS
$f1='/application/config/config.php';
$f2='/application/config/database.php';
$fp1 = fopen($cms_path.$f1, 'a');
$fp2 = fopen($cms_path.$f2, 'a');

ftruncate($fp1, 0);//очищаем файл config.php
ftruncate($fp2, 0);//очищаем файл database.php

///////////////////////////////////////////////////////////////////////////////////////////////////config.php
$conf = '<?php defined("BASEPATH") OR exit("No direct script access allowed");'.PHP_EOL
        . '$config["tagra_version"] = "1.2";'.PHP_EOL
        . '$config["tagra_instal_date"] = \''.$moment.'\';'.PHP_EOL
        . '$config["base_url"] = \''.$domen.'\';'.PHP_EOL
        . '$config["db_tabl_prefix"] = \''.$db_tabl_prefix.'\';'.PHP_EOL
        . '$config["index_page"] = "";'.PHP_EOL
        . '$config["uri_protocol"] = "REQUEST_URI";'.PHP_EOL
        . '$config["url_suffix"] = "";'.PHP_EOL
        . '$config["language"] = "russian";'.PHP_EOL
        . '$config["charset"] = "UTF-8";'.PHP_EOL
        . '$config["enable_hooks"] = FALSE;'.PHP_EOL
        . '$config["subclass_prefix"] = "MY_";'.PHP_EOL
        . '$config["composer_autoload"] = FALSE;'.PHP_EOL
        . '$config["permitted_uri_chars"] = "a-z 0-9~%.:_\-";'.PHP_EOL
        . '$config["allow_get_array"]	= TRUE;'.PHP_EOL
        . '$config["enable_query_strings"] = FALSE;'.PHP_EOL
        . '$config["controller_trigger"] = "c";'.PHP_EOL
        . '$config["function_trigger"] = "m";'.PHP_EOL
        . '$config["directory_trigger"]	= "d";'.PHP_EOL
        . '$config["log_threshold"] = 0;'.PHP_EOL
        . '$config["log_path"] = "";'.PHP_EOL
        . '$config["log_file_extension"] = "";'.PHP_EOL
        . '$config["log_file_permissions"] = 0644;'.PHP_EOL
        . '$config["log_date_format"] = "Y-m-d H:i:s";'.PHP_EOL
        . '$config["error_views_path"] = "";'.PHP_EOL
        . '$config["cache_path"] = "";'.PHP_EOL
        . '$config["cache_query_string"] = FALSE;'.PHP_EOL
        . '$config["encryption_key"] = "'.uniqid('Kroloburet_').'";'.PHP_EOL
        . '$config["sess_driver"] = "database";'.PHP_EOL
        . '$config["sess_save_path"] = \''.$db_tabl_prefix.'sessions\';'.PHP_EOL
        . '$config["sess_cookie_name"] = "tagra_session";'.PHP_EOL
        . '$config["sess_expiration"] = 0;'.PHP_EOL
        . '$config["sess_match_ip"]	= FALSE;'.PHP_EOL
        . '$config["sess_time_to_update"]	= 300;'.PHP_EOL
        . '$config["sess_regenerate_destroy"]	= FALSE;'.PHP_EOL
        . '$config["cookie_prefix"]	= "";'.PHP_EOL
        . '$config["cookie_domain"]	= "";'.PHP_EOL
        . '$config["cookie_path"]	= "/";'.PHP_EOL
        . '$config["cookie_secure"]	= FALSE;'.PHP_EOL
        . '$config["cookie_httponly"]	= FALSE;'.PHP_EOL
        . '$config["standardize_newlines"]	= FALSE;'.PHP_EOL
        . '$config["global_xss_filtering"] = FALSE;'.PHP_EOL
        . '$config["csrf_protection"] = FALSE;'.PHP_EOL
        . '$config["csrf_token_name"] = "csrf_test_name";'.PHP_EOL
        . '$config["csrf_cookie_name"] = "csrf_cookie_name";'.PHP_EOL
        . '$config["csrf_expire"] = 7200;'.PHP_EOL
        . '$config["csrf_expire"] = TRUE;'.PHP_EOL
        . '$config["csrf_exclude_uris"] = array();'.PHP_EOL
        . '$config["compress_output"] = FALSE;'.PHP_EOL
        . '$config["time_reference"] = "local";'.PHP_EOL
        . '$config["rewrite_short_tags"] = TRUE;'.PHP_EOL
        . '$config["proxy_ips"] = "";';

///////////////////////////////////////////////////////////////////////////////////////////////////database.php
$conf_db = '<?php defined("BASEPATH") OR exit("No direct script access allowed");'.PHP_EOL
        . '$active_group = "default";'.PHP_EOL
        . '$query_builder = TRUE;'.PHP_EOL
        . '$db["default"]["dsn"] = "";'.PHP_EOL
        . '$db["default"]["hostname"] = \''.$db_host.'\';'.PHP_EOL
        . '$db["default"]["username"] = \''.$db_user.'\';'.PHP_EOL
        . '$db["default"]["password"] = \''.$db_pass.'\';'.PHP_EOL
        . '$db["default"]["database"] = \''.$db_name.'\';'.PHP_EOL
        . '$db["default"]["dbdriver"] = "mysqli";'.PHP_EOL
        . '$db["default"]["dbprefix"] = "";'.PHP_EOL
        . '$db["default"]["pconnect"] = FALSE;'.PHP_EOL
        . '$db["default"]["db_debug"] = TRUE;'.PHP_EOL
        . '$db["default"]["cache_on"] = FALSE;'.PHP_EOL
        . '$db["default"]["cachedir"] = "";'.PHP_EOL
        . '$db["default"]["char_set"] = "utf8";'.PHP_EOL
        . '$db["default"]["dbcollat"] = "utf8_general_ci";'.PHP_EOL
        . '$db["default"]["swap_pre"] = "";'.PHP_EOL
        . '$db["default"]["encrypt"] = FALSE;'.PHP_EOL
        . '$db["default"]["compress"] = FALSE;'.PHP_EOL
        . '$db["default"]["stricton"] = FALSE;'.PHP_EOL
        . '$db["default"]["failover"] = array();'.PHP_EOL
        . '$db["default"]["save_queries"] = TRUE;';

if (!fwrite($fp1, $conf)){
die("<div class='notific_r'>Не удалось записать $f1</div>".$bad_msg);
}
echo "<div class='notific_g'>Файл $f1 успешно записан</div>";
fclose($fp1);
if (!fwrite($fp2, $conf_db)){
die("<div class='notific_r'>Не удалось записать $f2</div>".$bad_msg);
}
echo "<div class='notific_g'>Файл $f2 успешно записан</div>";
fclose($fp2);
echo $good_msg;
?>
   
   <div id="copy">Веб-разработка и дизайн<a href="mailto:kroloburet@gmail.com" class="js"> <img src="/UI_fraimwork/img/kroloburet_18_18.jpeg" width="18" height="18" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com</a><br>
 <img src="/UI_fraimwork/img/logo_tagra_18_18.svg" alt="Tagra CMS"> Tagra CMS<br></div>
  </div>
 </body>
</html>
