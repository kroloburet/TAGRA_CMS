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
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  //разбераю get-данные если они есть, если нет - устанавливаю по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='title';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->userdata('pag_per_page');//
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получаю выборку для страницы результата и количество всех записей
  $gallerys=$this->back_basic_model->get_result_list('gallerys',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$gallerys['count_result']);
  $data['gallerys']=$gallerys['result'];
  $data['conf_title']="Управление галереями";
  $this->_viewer('back/gallerys/gallerys_list_view',$data);
 }

 function add_form(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['conf_title']='Добавить галерею';
  $this->_viewer('back/gallerys/gallerys_add_view',$data);
 }

 function edit_form($id){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_merge($this->conf,$this->back_basic_model->get_where_id($this->_prefix().'gallerys',$id));//соединение массивов
  $data['conf_title']='Редактировать галерею';
  $this->_viewer('back/gallerys/gallerys_edit_view',$data);
 }

 function add(){//добавление галереи
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_basic_model->add(array_map('trim', $this->input->post()),$this->_prefix().'gallerys');//убираем пробелы в начале и в конце
  $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/gallery/get_list');
 }

 function edit($id,$alias){//изменение галереи по $id, перзапись url в комментариях, url в меню
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->back_gallery_model->edit_gallery($id,array_map('trim', $this->input->post()),$alias);
  $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  redirect('admin/gallery/get_list');
 }

 function del(){//удаление аяксом галлерею и комментарии к ней
  if($this->_is_login()){//если админ, модератор — логика
   $post=$this->input->post();
   $this->back_gallery_model->del_gallery($post['alias']);
   $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
  }
  echo '';
 }

}