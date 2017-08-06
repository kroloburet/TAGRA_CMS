<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с главной страницей
///////////////////////////////////

class Back_home_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_home_model');
 }

 function edit_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_merge($this->conf,$this->back_home_model->get_home_page());//соединение массивов
  $data['conf_title']='Страница «Главная»';
  $this->_viewer('back/home_edit_form_view',$data);
 }

 function edit() {//изменение главной страницы
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_home_model->edit_home_page(array_map('trim',$this->input->post()));
  redirect('admin/home/edit_form');
 }
 
}