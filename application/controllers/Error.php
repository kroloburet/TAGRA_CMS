<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
// function __construct(){
//  parent::__construct();
// }

 function error_404() {
  $this->load->model('Front_basic_model');
  $data=$this->Front_basic_model->my_config_data();
  $data['front_menu_list']=$this->Front_basic_model->get_menu();
  $data['robots']='none';
  $data['addthis_follow']='on';
  $data['addthis_share']='on';
  $data['description']='';
  $data['title']='Упс! Страница не найдена';
  $this->output->set_status_header('404');
  $this->load->view('front/blocks/header_view', $data);
  $this->load->view('404_view', $data);
  $this->load->view('front/blocks/footer_view', $data);
 }

}
