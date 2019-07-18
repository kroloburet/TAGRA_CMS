<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа со страницами "Контакты"
///////////////////////////////////

class Back_contact_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_contact_model');
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
  $contact_pages=$this->back_basic_model->get_result_list('contact_pages',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$contact_pages['count_result']);
  $data['contact_pages']=$contact_pages['result'];
  $data['view_title']='Управление страницами «Контакты»';
  $this->_viewer('back/contact_pages/contact_list_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('contact_pages',$id);
  $data['view_title']='Редактировать страницу «Контакты»';
  $this->_viewer('back/contact_pages/contact_edit_view',$data);
 }

 function edit($id){
  $this->back_contact_model->edit_contact_page($id,$this->_format_data($this->input->post(),TRUE,'comma'));
  redirect('admin/contact/get_list');
 }

}
