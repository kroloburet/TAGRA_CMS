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
  $sections=$this->back_basic_model->get_result_list('sections',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$sections['count_result']);
  $data['sections']=$sections['result'];
  $data['conf_title']="Управление разделами";
  $this->_viewer('back/sections/sections_list_view',$data);
 }

 function add_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['conf_title']='Добавить раздел';
  $this->_viewer('back/sections/sections_add_form_view',$data);
 }

 function edit_form($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_merge($this->conf,$this->back_basic_model->get_where_id($this->_prefix().'sections',$id));//соединение массивов
  $data['conf_title']='Редактировать раздел';
  $this->_viewer('back/sections/sections_edit_form_view',$data);
 }

 function add(){//добавление раздела
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_basic_model->add(array_map('trim',$this->input->post()),$this->_prefix().'sections');//убираем пробелы в начале и в конце
  $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/section/get_list');
 }

 function edit($id,$alias){//изменение раздела по $id, перезапись родительского раздела в подразделах, перзапись url в комментариях, url меню
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_section_model->edit_section($id,array_map('trim',$this->input->post()),$alias);
  $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/section/get_list');
 }

 function del(){//удаление аяксом раздела, комментарии к нему и назначение дочерним вместо родительского - пустую строку
  if($this->_is_login()){//если админ, модератор — логика
   $post=$this->input->post();
   $this->back_section_model->del_section($post['alias']);
   $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  }
  echo '';
 }

}