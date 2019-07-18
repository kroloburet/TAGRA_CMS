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

 function _set_data($lang){
  $data['lang']=$lang;
  $data['view_title']='Главное меню сайта';
  $data['menu']=$this->back_menu_model->get_menu($lang);
  $p=$this->db->where('lang',$lang)->select('title,alias,section')->order_by('title')->get('pages')->result_array();
  $s=$this->db->where('lang',$lang)->select('title,alias,section')->order_by('title')->get('sections')->result_array();
  $g=$this->db->where('lang',$lang)->select('title,alias,section')->order_by('title')->get('gallerys')->result_array();
  $data['materials']=['pages'=>$p,'sections'=>$s,'gallerys'=>$g];
  return $data;
 }

 function edit_form(){
  if($this->_lang_selection(['view_title'=>'Главное меню сайта'])){return false;}
  $this->_viewer('back/menu_view',$this->_set_data($this->app('data.lang')));
 }

 function add_item(){
  if(!$this->input->post()){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $p=array_map('trim',$this->input->post());
  if(!$this->back_menu_model->add_item($p)){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $resp=$this->_set_data($p['lang']);
  $resp['html']=$this->load->view('back/menu_view',['data'=>$resp],TRUE);
  $resp['status']='ok';
  exit(json_encode($resp,JSON_FORCE_OBJECT));
 }

 function edit_item(){
  if(!$this->input->post()){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $p=array_map('trim',$this->input->post());
  if(!$this->back_menu_model->edit_item($p)){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $resp=$this->_set_data($p['lang']);
  $resp['html']=$this->load->view('back/menu_view',['data'=>$resp],TRUE);
  $resp['status']='ok';
  exit(json_encode($resp,JSON_FORCE_OBJECT));
 }

 function del_item(){
  if(!$this->input->post()){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $p=$this->input->post();
  if(!$this->back_menu_model->del_item($p)){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $resp=$this->_set_data($p['lang']);
  $resp['html']=$this->load->view('back/menu_view',['data'=>$resp],TRUE);
  $resp['status']='ok';
  exit(json_encode($resp,JSON_FORCE_OBJECT));
 }

 function public_item(){
  if(!$this->input->post()){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $p=$this->input->post();
  if(!$this->back_menu_model->public_item($p)){exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));}
  $resp=$this->_set_data($p['lang']);
  $resp['html']=$this->load->view('back/menu_view',['data'=>$resp],TRUE);
  $resp['status']='ok';
  exit(json_encode($resp,JSON_FORCE_OBJECT));
  }

}