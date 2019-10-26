<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Front_section_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_section_model');
 }

 function get_section($id){
  $data=$this->front_section_model->get_section($id);
  if($data){
   $data['sub_sections']=$this->front_section_model->get_sub_sections($id);
   $data['sub_gallerys']=$this->front_section_model->get_sub_gallerys($id);
   $data['sub_pages']=$this->front_section_model->get_sub_pages($id);
   $this->_viewer('front/section_view',$data);
  }else{
   redirect('404_override');
  }
 }

}