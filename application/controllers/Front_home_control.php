<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с страницей "Главная"
///////////////////////////////////

class Front_home_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_home_model');
 }

 function index(){
  $data=$this->front_home_model->get_home_page();
  $this->_viewer('front/home_view',$data);
 }

}