<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа со страницами "Главная"
///////////////////////////////////

class Back_home_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_home_model');
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
  $home_pages=$this->back_basic_model->get_result_list('index_pages',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$home_pages['count_result']);
  $data['home_pages']=$home_pages['result'];
  $data['view_title']='Управление страницами «Главная»';
  $this->_viewer('back/home_pages/home_list_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('index_pages',$id);
  $data['view_title']='Редактировать страницу «Главная»';
  $this->_viewer('back/home_pages/home_edit_view',$data);
 }

 function edit($id){
  $this->back_home_model->edit_home_page($id,array_map('trim',$this->input->post()));
  redirect('admin/home/get_list');
}

}