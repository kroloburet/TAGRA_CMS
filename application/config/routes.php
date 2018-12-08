<?php defined('BASEPATH') OR exit('No direct script access allowed');
//Зарезервированные роуты должны определяться выше любого шаблона или регулярного выражения
$route["default_controller"]="front_home_control";
$route["404_override"]="errors/error_404";
$route["translate_uri_dashes"]=FALSE;
//Роуты для админки
$route["admin"]="back_basic_control";
$route["admin/setting/(.+)"]="back_setting_control/$1";
$route["admin/menu/(.+)"]="back_menu_control/$1";
$route["admin/section/(.+)"]="back_section_control/$1";
$route["admin/gallery/(.+)"]="back_gallery_control/$1";
$route["admin/page/(.+)"]="back_page_control/$1";
$route["admin/home/(.+)"]="back_home_control/$1";
$route["admin/contact/(.+)"]="back_contact_control/$1";
$route["admin/comment/(.+)"]="back_comment_control/$1";
$route["admin/(.+)"]="back_basic_control/$1";
//Роуты для фронтальной части
$route["contact"]="front_contact_control/contact";
$route["section/(.+)"]="front_section_control/section/$1";
$route["gallery/(.+)"]="front_gallery_control/gallery/$1";
//Роуты действий
$route["do/change_login"]="Change_login";
$route["do/send_mail"]="front_contact_control/send_mail";
$route["do/add_comment"]="front_comment_control/add_comment";
$route["do/comment_action/(.+)"]="front_comment_control/comment_action/$1";
$route["do/comment_unfeedback"]="front_comment_control/comment_unfeedback";
$route["do/comment_rating"]="front_comment_control/comment_rating";
//Роут на универсальный метод (если url - страница - отбразить ее) !!!ДОЛЖЕН НАХОДИТЬСЯ В КОНЦЕ МАССИВА!!!
$route["(.+)"]="front_page_control/is_page/$1";
