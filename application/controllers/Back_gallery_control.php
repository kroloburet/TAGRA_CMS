<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Back_gallery_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_gallery_model');
 }

 function get_list(){
  //разобрать get-данные если они есть, если нет - установить по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='title';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->userdata('pag_per_page');
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получить выборку для страницы результата и количество всех записей
  $gallerys=$this->back_basic_model->get_result_list('gallerys',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$gallerys['count_result']);
  $data['gallerys']=$gallerys['result'];
  $data['view_title']='Управление галереями';
  $this->_viewer('back/gallerys/gallerys_list_view',$data);
 }

 function add_form(){
  $data['view_title']='Добавить галерею';
  if($this->_lang_selection($data)){return false;}
  $this->_viewer('back/gallerys/gallerys_add_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('gallerys',$id);
  $data['view_title']='Редактировать галерею';
  $this->_viewer('back/gallerys/gallerys_edit_view',$data);
 }

 function add(){
  $this->back_gallery_model->add_gallery(array_map('trim',$this->input->post()));
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  redirect('admin/gallery/get_list');
 }

 function edit($id){
  $this->back_gallery_model->edit_gallery($id,array_map('trim',$this->input->post()));
  redirect('admin/gallery/get_list');
 }

 function del(){
  $p=$this->input->post();
  if(!$p['id']){exit('error');}
  $this->back_gallery_model->del_gallery($p['id']);
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  exit('ok');
 }

}