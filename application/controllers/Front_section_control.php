<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Front_section_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_section_model');
 }

 function section($alias){
  ($this->conf['conf_site_access']==='off')?redirect('plug.html'):TRUE;//если сайт закрыт в конфигурации - напрвляю на страницу-заглушку
  if($g=$this->front_section_model->get_section($alias)){//eсли есть такой алиас в разделах
   $data=array_merge($this->conf,$g);//соединение массивов
   $data['sub_sections']=$this->front_section_model->get_sub_sections($alias);
   $data['sub_gallerys']=$this->front_section_model->get_sub_gallerys($alias);
   $data['sub_pages']=$this->front_section_model->get_sub_pages($alias);
   $this->_viewer('front/section_view',$data,$data['comments']);
  }else{//нет такого алиаса
   include(APPPATH.'controllers/Error.php');
   $error=new Error();
   $error->error_404();
  }
 }

}