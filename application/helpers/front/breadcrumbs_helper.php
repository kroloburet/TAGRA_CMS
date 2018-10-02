<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('breadcrumbs')){
 function breadcrumbs($home=FALSE){//вывод "хлебных крошек"
  global $CI,$prefix,$list;
  $CI=&get_instance();
  $prefix=$CI->config->item('db_tabl_prefix');
  $seg1=$CI->uri->segment(1);//первый сегмент урл после домена
  $seg2=$CI->uri->segment(2);//второй сегмент урл после домена
  $q=array();//будет хранить выборку
  $home=$home?'<li><a href="'.base_url().'" class="bc_home">'.$home.'</a>'.PHP_EOL:'';//главная в цепи
  $list='<ul id="breadcrumbs" class="noprint">'.PHP_EOL.$home;//начало цепи+главная

  /////////////////////////////////дополнить лист цепочкой подразделов
  function sub_sections_list(/*алиас родительского раздела в цепи*/$sect){
   global $CI,$prefix,$list;
   if($d=$CI->front_basic_model->get_where_alias($prefix.'sections',$sect)){//если такой алиас есть
    if($d['section']){sub_sections_list($d['section']);}//если есть родитель - рекурсия;
    $list.='<li><a href="'.base_url('section/'.$d['alias']).'">'.$d['title'].'</a>'.PHP_EOL;
   }
  }

  /////////////////////////////////вывод всей цепи до материала
  switch($seg1){//это:
   case'section':$q=$CI->front_basic_model->get_where_alias($prefix.'sections',$seg2);break;//раздел
   case'gallery':$q=$CI->front_basic_model->get_where_alias($prefix.'gallerys',$seg2);break;//галерея
   case'contact':$q=array('title'=>'Контакты');break;//контакты
   case'search':$q=array('title'=>'Поиск по сайту');break;//поиск
   default:$q=$CI->front_basic_model->get_where_alias($prefix.'pages',$seg1);//страница
  }
  if(!empty($q)){//выборка не пуста
   if(@$q['section']){sub_sections_list($q['section']);}//етсть раздел
   echo $list.'<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;//вывод всей цепи
  }
 }
}
