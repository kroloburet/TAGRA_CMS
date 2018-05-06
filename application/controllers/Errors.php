<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//обработка ошибок
///////////////////////////////////

class Errors extends Front_basic_control{
 function __construct(){
  parent::__construct();
 }

 function error_404() {
  $data=$this->conf;
  $data['robots']='none';
  $data['addthis_follow']='off';
  $data['addthis_share']='off';
  $data['description']='Упс! Страница не найдена';
  $data['title']='Упс! Страница не найдена';
  $this->output->set_status_header('404');
  $this->_viewer('404_view',$data,'off');
 }

}