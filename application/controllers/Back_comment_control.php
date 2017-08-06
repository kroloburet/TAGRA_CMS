<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Back_comment_control extends Back_basic_control {
 function __construct(){
  parent::__construct();
  $this->load->model('back_comment_model');
 }

 function get_list(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['new_comments']=$this->back_comment_model->get_new_comments();
  //разбераю get-данные если они есть, если нет - устанавливаю по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='comment';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->pag_per_page;
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получаю выборку для страницы результата и количество всех записей
  $comments=$this->back_basic_model->get_result_list('comments',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$comments['count_result']);
  $data['comments']=$comments['result'];
  $data['conf_title'] = 'Управление комментариями';
  $this->_viewer('back/comments/comments_list_view',$data);
 }

 function del_new($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_comment_model->del_new_comment($id);
  redirect('admin/comment/get_list');
 }

 function public_new($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_comment_model->public_new_comment($id);
  redirect('admin/comment/get_list');
 }

 function get_count_new(){
  echo count($this->back_comment_model->get_new_comments());
 }
 
}