<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Front_gallery_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_gallery_model');
 }

 function get_gallery($id){
  $data=$this->front_gallery_model->get_gallery($id);
  $data?$this->_viewer('front/gallery_view',$data):redirect('404_override');
 }

}