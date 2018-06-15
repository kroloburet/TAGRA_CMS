<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с главной страницей
///////////////////////////////////

class Front_home_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_home_model');
 }

 function index(){
  $data=array_merge($this->conf,$this->front_home_model->get_home_page());//соединение массивов
  $this->_viewer('front/home_view',$data);
 }

}