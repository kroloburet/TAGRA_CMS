<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с меню
///////////////////////////////////

class Back_menu_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_menu_model');
 }

 function edit_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['conf_title']='Главное меню сайта';
  $data['menu']=$this->back_menu_model->get_menu();
  $data['pages']=$this->db->select('title,alias,section')->order_by('title','ASC')->get($this->_prefix().'pages')->result_array();
  $data['sections']=$this->db->select('title,alias,section')->order_by('title','ASC')->get($this->_prefix().'sections')->result_array();
  $data['gallerys']=$this->db->select('title,alias,section')->order_by('title','ASC')->get($this->_prefix().'gallerys')->result_array();
  $this->_viewer('back/menu_edit_form_view',$data);
 }

 function add_item(){
  $this->_is_login()?TRUE:redirect('admin/login');
  array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $data['pid']=$this->input->post('pid');
  $data['order']=$this->input->post('order');
  $data['title']=$this->input->post('title');
  $data['url']=$this->input->post('url');
  $data['target']=$this->input->post('target');
  $data['public']=$this->input->post('public');
  $this->back_menu_model->add_menu_item($data);
  redirect('admin/menu/edit_form');
 }

 function edit_item($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $data['pid']=$this->input->post('pid');
  $data['order']=$this->input->post('order');
  $data['title']=$this->input->post('title');
  $data['url']=$this->input->post('url');
  $data['target']=$this->input->post('target');
  $this->back_basic_model->edit($id,$data,$this->_prefix().'menu');
  redirect('admin/menu/edit_form');
 }

 function del_item($id,$pid,$order){
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_menu_model->del_menu_item($id,$pid,$order);
  redirect('admin/menu/edit_form');
 }

 function public_item(){//побликовать\не публиковать аяксом
  $this->_is_login()?TRUE:redirect('admin/login');
  $p=$this->input->post();
  $this->back_menu_model->public_menu_item($p['id'],$p['pub']);
  if($p['pub']==='on'){
   echo'<a href="#" onclick="public_item(this,\''.$p['id'].'\',\'off\');return false" class="fa-eye-slash red" title="Опубликовать/не опубликовывать"></a>';
  }elseif($p['pub']==='off'){
   echo'<a href="#" onclick="public_item(this,\''.$p['id'].'\',\'on\');return false" class="fa-eye green" title="Опубликовать/не опубликовывать"></a>';
  }
 }
 
}