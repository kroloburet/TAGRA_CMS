<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с главной страницей
///////////////////////////////////

class Front_home_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_home_model');
 }

 function index(){
  ($this->conf['conf_site_access']==='off')?redirect('plug.html'):TRUE;//если сайт закрыт в конфигурации - напрвляю на страницу-заглушку
  $data=array_merge($this->conf,$this->front_home_model->get_home_page());//соединение массивов
  $this->_viewer('front/home_view',$data,'off');
 }

}