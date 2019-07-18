<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//обработка ошибок
///////////////////////////////////

class Errors extends Front_basic_control{
 function __construct(){
  parent::__construct();
 }

 function error_404(){
  $data['robots']='none';
  $data['addthis_follow']='off';
  $data['addthis_share']='off';
  $data['lang']=$this->app('conf.user_lang');
  $data['description']=htmlspecialchars($this->app('lexic.404.title'));
  $data['title']=$data['description'];
  $this->output->set_status_header('404');
  $this->_viewer('404_view',$data);
 }

}