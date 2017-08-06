<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Front_gallery_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_gallery_model');
 }

 function gallery($alias){
  ($this->conf['conf_site_access']==='off')?redirect('plug.html'):TRUE;//если сайт закрыт в конфигурации - напрвляю на страницу-заглушку
  if($g=$this->front_gallery_model->get_gallery($alias)){//eсли есть такой алиас в галереях
   $data=array_merge($this->conf,$g);//соединение массивов
   $this->_viewer('front/gallery_view',$data,$data['comments']);
  }else{//нет такого алиаса
   include(APPPATH.'controllers/Error.php');
   $error=new Error();
   $error->error_404();
  }
 }

}