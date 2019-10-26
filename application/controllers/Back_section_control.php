<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Back_section_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_section_model');
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
  $sections=$this->back_basic_model->get_result_list('sections',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$sections['count_result']);
  $data['sections']=$sections['result'];
  $data['view_title']='Управление разделами';
  $this->_viewer('back/sections/sections_list_view',$data);
 }

 function add_form(){
  $data['view_title']='Добавить раздел';
  if($this->_lang_selection($data)){return false;}
  $this->_viewer('back/sections/sections_add_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('sections',$id);
  $data['view_title']='Редактировать раздел';
  $this->_viewer('back/sections/sections_edit_view',$data);
 }

 function add(){
  $this->back_section_model->add_section(array_map('trim',$this->input->post()));
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  redirect('admin/section/get_list');
 }

 function edit($id){
  $this->back_section_model->edit_section($id,array_map('trim',$this->input->post()));
  redirect('admin/section/get_list');
 }

 function del(){
  $p=$this->input->post();
  if(!$p['id']){exit('error');}
  $this->back_section_model->del_section($p['id']);
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  exit('ok');
 }

}