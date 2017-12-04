<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Back_contact_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_contact_model');
 }

 function edit_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_merge($this->conf,$this->back_contact_model->get_contact_page());//соединение массивов
  $data['conf_title'] = 'Страница «Контакты»';
  $this->_viewer('back/contact_edit_form_view',$data);
 }

 function edit(){//изменение страницы
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_contact_model->edit_contact_page($this->_format_data($this->input->post()));//
  redirect('admin/contact/edit_form');
 }
 
}