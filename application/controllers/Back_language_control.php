<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с языками
///////////////////////////////////

class Back_language_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_language_model');
 }

 function _set_lang($data,$action){
  //метод изменяет язык по умолчанию если языком по умолчанию назначен другой язык, добавляет или перезвписывает данные в БД
  //$data-данные
  //$action-'add'||'edit' действие с данными в БД
  $def_tag=$this->app('conf.lang_def.tag');//текущий язык по умолчанию
  if(isset($data['def'])&&$data['def']=='on'&&$data['tag']!==$def_tag){//если переопределен
   $this->back_language_model->reset_def_lang();//сбросить язык по умолчанию в БД
  }
  if($action=='add'){//добавить в БД
   $this->back_basic_model->add($data,'languages');
   $data=[
   'creation_date'=>date('Y-m-d'),
   'last_mod_date'=>date('Y-m-d'),
   'layout_l_width'=>$this->app('conf.layout_l_width'),
   'addthis_share'=>$this->app('conf.addthis_share_def'),
   'addthis_follow'=>$this->app('conf.addthis_follow_def'),
   'img_prev'=>$this->app('conf.img_prev_def'),
   'lang'=>$data['tag']
   ];
   $this->back_basic_model->add($data+['title'=>'Привет, Мир!','description'=>'Привет, Мир!'],'index_pages');
   $this->back_basic_model->add($data+['title'=>'Контакты','description'=>'Контакты'],'contact_pages');
  }elseif($action=='edit'){//перезаписать в БД
   $this->back_basic_model->edit($data['id'],$data,'languages');
  }else{//неизвестное действие
   return FALSE;
  }
 }

 function _add_lang_dir($tag){
  //создать каталог в /application/language
  $def_tag=$this->app('conf.lang_def.tag');
  $def_lang_dir=APPPATH."language/$def_tag/";
  $new_lang_dir=APPPATH."language/$tag/";
  $d=opendir($def_lang_dir);
  mkdir($new_lang_dir);//создать новый каталог языка
  while($f=readdir($d)){//копировать все файлы в новый каталог языка
   if($f!='.'&&$f!='..'){
    if(!file_exists($new_lang_dir.$f)){
     copy($def_lang_dir.$f,$new_lang_dir.$f);
    }
   }
  }
  closedir($d);
  //создать каталог в /upload
  mkdir(getcwd()."/upload/$tag");
 }

 function _del_lang_dir($tag){
  function _rrd($dir){//рекурсивное удаление каталога со всем содержимым
   $objs=glob("$dir/*");
   if($objs){
    foreach($objs as $v){is_dir($v)?_rrd($v):unlink($v);}
   }
   @rmdir($dir);
  }
  _rrd(APPPATH."language/$tag");//удалить каталог языка
  _rrd(getcwd()."/upload/$tag");//удалить каталог файлов в /upload
 }

 function get_localization_file(){
  $p=array_map('trim',$this->input->post());
  echo !file_exists($p['path'])?'error':file_get_contents($p['path']);
 }

 function save_localization_file(){
  $res['status']='error';
  $p=$this->input->post();
  $T_allow=['T_INLINE_HTML','T_OPEN_TAG','T_COMMENT','T_WHITESPACE','T_VARIABLE','T_CONSTANT_ENCAPSED_STRING','T_DOUBLE_ARROW','T_ARRAY','T_ENCAPSED_AND_WHITESPACE','T_CURLY_OPEN','T_DOLLAR_OPEN_CURLY_BRACES','T_STRING_VARNAME','T_NUM_STRING','T_LNUMBER','T_DNUMBER'];//разрешенные лексемы
  $str_allow=[';','.',',','"','\'','[',']','(',')','{','}','='];//разрешенные символы
  $i=1;//счетчик строк
  !$p['path']||!$p['text']?exit(json_encode($res)):TRUE;
  ///////////////////////////валидация строки
  foreach(token_get_all($p['text']) as $v){
   if(is_array($v)){
    $t=token_name($v[0]);
    //не пробельный символ до <?php или запрещенная лексема
    if(($t==='T_INLINE_HTML'&&preg_match('/\S/',$v[1]))||!in_array($t,$T_allow)){
     $res['msg']="Недопустимый символ <mark>$v[1]</mark> в строке $i";
     exit(json_encode($res));
    }
    //номер следующей строки
    $i+=($t!=='T_ENCAPSED_AND_WHITESPACE'||$t!=='T_CONSTANT_ENCAPSED_STRING')?substr_count($v[1],"\n"):1;
    //запрещенный символ
   }elseif(!in_array($v,$str_allow)){
     $res['msg']="Недопустимый символ <mark>$v</mark> в строке $i";
     exit(json_encode($res));
   }
  }
  ///////////////////////////перезаписать файл локализации
  if(!file_exists($p['path'])||!file_put_contents($p['path'],$p['text'],LOCK_EX)){
   $res['msg']="Файл не существует или не может быть перезаписан";
   exit(json_encode($res));
  }
  $res['status']='ok';
  exit(json_encode($res));
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
  $langs=$this->back_basic_model->get_result_list('languages',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$langs['count_result']);
  $data['langs']=$langs['result'];
  $data['view_title']='Управление языками';
  $this->_viewer('back/languages/languages_list_view',$data);
 }

 function add_form(){
  $data['added_tags']=json_encode(array_column($this->app('conf.langs'),'tag'));
  $data['view_title']='Добавить язык';
  $this->_viewer('back/languages/languages_add_view',$data);
 }

 function edit_form($id){
  $data=$this->back_basic_model->get_where_id('languages',$id);
  $data['view_title']='Редактировать язык';
  $this->_viewer('back/languages/languages_edit_view',$data);
 }

 function add(){
  $p=array_map('trim',$this->input->post());
  $this->_add_lang_dir($p['tag']);//создать каталог в /application/language и /upload
  $this->_set_lang($p,'add');//добавить язык
  redirect('admin/language/edit_form/'.$p['id']);//направить редактировать файлы локализации
 }

 function edit(){
  $p=array_map('trim',$this->input->post());
  $this->_set_lang($p,'edit');//перезаписать язык
  redirect('admin/language/get_list');
 }

 function del(){
  $p=$this->input->post();
  if(!$p['tag']||!$this->db->get_where('languages',['tag'=>$p['tag'],'def !='=>'on'])->result()){exit('error');}
  $this->_del_lang_dir($p['tag']);
  $this->back_language_model->del_lang($p['tag']);
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  exit('ok');
 }

}
