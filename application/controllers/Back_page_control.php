<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Back_page_control extends Back_basic_control {
 function __construct(){
  parent::__construct();
  $this->load->model('back_page_model');
 }

 function get_list(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  //разбераю get-данные если они есть, если нет - устанавливаю по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='title';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->pag_per_page;//
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получаю выборку для страницы результата и количество всех записей
  $pages=$this->back_basic_model->get_result_list('pages',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$pages['count_result']);
  $data['pages']=$pages['result'];
  $data['conf_title'] = "Управление страницами";
  $this->_viewer('back/pages/pages_list_view',$data);
 }

 function add_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['conf_title']="Добавить страницу";
  $this->_viewer('back/pages/pages_add_form_view',$data);
 }

 function edit_form($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_merge($this->conf,$this->back_basic_model->get_where_id($this->_prefix().'_pages',$id));//соединение массивов
  $data['conf_title'] = "Редактировать страницу";
  $this->_viewer('back/pages/pages_edit_form_view',$data);
 }

 function add(){//добавление страницы
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_basic_model->add(array_map('trim',$this->input->post()),$this->_prefix().'_pages');//записываю материал
  ($this->conf['sitemap']['generate']==='auto')?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/page/get_list');
 }

 function edit($id,$alias){//изменение страницы по $id, перзапись url в комментариях, url в меню
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_page_model->edit_page($id,array_map('trim',$this->input->post()),$alias);//записываю материал
  ($this->conf['sitemap']['generate']==='auto')?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/page/get_list');
 }

 function del(){//удаление аяксом страницы и комментарии к ней
  if($this->_is_login()){//если админ, модератор — логика
   $post=$this->input->post();
   $this->back_page_model->del_page($post['alias']);
   ($this->conf['sitemap']['generate']==='auto')?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  }
  echo '';
 }
 
}