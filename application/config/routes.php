<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Зарезервированные роуты должны определяться выше любого шаблона или регулярного выражения
 */
$route["default_controller"] = "front_home_control";
$route["404_override"] = "errors/error_404";
$route["translate_uri_dashes"] = false;
/**
 * Роуты для фронтальной части
 */
$route["contact"] = "front_contact_control";
$route["page/(.+)"] = "front_page_control/get_page/$1";
$route["section/(.+)"] = "front_section_control/get_section/$1";
$route["gallery/(.+)"] = "front_gallery_control/get_gallery/$1";
/**
 * Роуты для админки
 */
$route["admin"] = "back_basic_control";
$route["admin/setting/(.+)"] = "back_setting_control/$1";
$route["admin/language/(.+)"] = "back_language_control/$1";
$route["admin/menu/(.+)"] = "back_menu_control/$1";
$route["admin/section/(.+)"] = "back_section_control/$1";
$route["admin/gallery/(.+)"] = "back_gallery_control/$1";
$route["admin/page/(.+)"] = "back_page_control/$1";
$route["admin/home/(.+)"] = "back_home_control/$1";
$route["admin/contact/(.+)"] = "back_contact_control/$1";
$route["admin/comment/(.+)"] = "back_comment_control/$1";
$route["admin/(.+)"] = "back_basic_control/$1";
/**
 * Роуты действий
 */
$route["do/change_login"] = "change_login";
$route["do/change_lang/(.+)"] = "front_basic_control/change_lang/$1";
$route["do/send_mail"] = "front_contact_control/send_mail";
$route["do/add_comment"] = "front_comment_control/add_comment";
$route["do/comment_action/(.+)"] = "front_comment_control/comment_action/$1";
$route["do/comment_unfeedback"] = "front_comment_control/comment_unfeedback";
$route["do/comment_rating"] = "front_comment_control/comment_rating";
