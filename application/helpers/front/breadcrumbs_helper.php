<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('breadcrumbs')){

 function sctn_path(/*алиас самого дочернего раздела в цепи*/$sect) {//функция выводит цепочку разделов
  $CI=&get_instance();
  $prefix=$CI->config->item('db_tabl_prefix');
  if($d=$CI->front_basic_model->get_where_alias($prefix.'_sections',$sect)){//если такой алиас есть
   $d['section']?sctn_path($d['section']):FALSE;//если есть родитель - рекурсия;
  }
  echo '<li><a href="'.base_url('section/'.$d['alias']).'">'.$d['title'].'</a>'.PHP_EOL;//вывод звена
 }

 function breadcrumbs($home=''){//вывод "хлебных крошек"
  if(current_url()!==base_url()){//если текущая страница не главная
   $CI=&get_instance();
   $prefix=$CI->config->item('db_tabl_prefix');
   $seg1=$CI->uri->segment(1);//первый сегмент урл после домена
   $seg2=$CI->uri->segment(2);//второй сегмент урл после домена
   echo PHP_EOL.'<!--####### Навигация "хлебные крошки" #######-->'.PHP_EOL;
   $home!==''?$home=PHP_EOL.'<li><a href="'.base_url().'" class="bc_home">'.$home.'</a>'.PHP_EOL:FALSE;//отображение главной в цепи
   ////////////////////////если раздел
   if($seg1==='section'){
    $q=$CI->front_basic_model->get_where_alias($prefix.'_sections',$seg2);//получаю выборку по алиасу
    if(!$q['section']){//если родителя нет
     echo '<ul id="breadcrumbs" class="noprint">'.$home.'<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }else{//если есть родитель
     echo '<ul id="breadcrumbs" class="noprint">'.$home;
     sctn_path($q['section']);
     echo '<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }
   }
   ////////////////////////если галерея
   if($seg1==='gallery'){
    $q=$CI->front_basic_model->get_where_alias($prefix.'_gallerys',$seg2);//получаю выборку по алиасу
    if(!$q['section']){//если родителя нет
     echo '<ul id="breadcrumbs" class="noprint">'.$home.'<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }else{//если есть родитель
     echo '<ul id="breadcrumbs" class="noprint">'.$home;
     sctn_path($q['section']);
     echo '<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }
   }
   ////////////////////////если страница
   if($seg1!=='section'&&$seg1!=='gallery'){
    $q=$CI->front_basic_model->get_where_alias($prefix.'_pages',$seg1);//получаю выборку по алиасу
    if(!$q['section']){//если родителя нет
     echo '<ul id="breadcrumbs" class="noprint">'.$home.'<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }else{//если есть родитель
     echo '<ul id="breadcrumbs" class="noprint">'.$home;
     sctn_path($q['section']);
     echo '<li class="bc_end">'.$q['title'].PHP_EOL.'</ul>'.PHP_EOL;
    }
   }
  }
 }

}
