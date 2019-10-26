<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Back_page_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_page_model');
 }

 function get_list(){
  //разобрать get-данные если они есть, если нет - установить по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='title';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->pag_per_page;
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получить выборку для страницы результата и количество всех записей
  $pages=$this->back_basic_model->get_result_list('pages',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$pages['count_result']);
  $data['pages']=$pages['result'];
  $data['view_title']='Управление страницами';
  $this->_viewer('back/pages/pages_list_view',$data);
 }

 function add_form(){
  $data['view_title']='Добавить страницу';
  if($this->_lang_selection($data)){return false;}
  $this->_viewer('back/pages/pages_add_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('pages',$id);
  $data['view_title']='Редактировать страницу';
  $this->_viewer('back/pages/pages_edit_view',$data);
 }

 function add(){
  $this->back_page_model->add_page(array_map('trim',$this->input->post()));
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  redirect('admin/page/get_list');
 }

 function edit($id){
  $this->back_page_model->edit_page($id,array_map('trim',$this->input->post()));
  redirect('admin/page/get_list');
 }

 function del(){
  $p=$this->input->post();
  if(!$p['id']){exit('error');}
  $this->back_page_model->del_page($p['id']);
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  exit('ok');
 }

}