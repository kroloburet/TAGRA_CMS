<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Front_page_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_page_model');
 }

 function is_page($alias){
  ($this->conf['conf_site_access']==='off')?redirect('plug.html'):TRUE;//если сайт закрыт в конфигурации - напрвляю на страницу-заглушку
  if($this->front_page_model->is_page($alias)){//если в базе есть такой алиас
  $data=array_merge($this->conf,$this->front_page_model->is_page($alias));//соединение массивов
  $this->_viewer('front/page_view',$data,$data['comments']);
  }else{//если нет алиаса
   include(APPPATH.'controllers/Error.php');
   $error=new Error();
   $error->error_404();
  }
 }

}