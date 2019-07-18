<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Front_page_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_page_model');
 }

 function is_page($alias){
  $data=$this->front_page_model->is_page($alias);
  $data?$this->_viewer('front/page_view',$data):redirect('404_override');
 }

}
