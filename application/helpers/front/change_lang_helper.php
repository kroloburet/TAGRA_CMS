<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('print_lang_switch')){//вывод меню сайта
 function print_lang_switch(){
  $CI=&get_instance();
  $conf=$CI->app('conf');
  $data=$CI->app('data');
  $segment=$CI->uri->segment(1,'/');
  $versions=isset($data['versions'])&&!empty($data['versions'])?json_decode($data['versions'],TRUE):[];
  if(count($conf['langs'])<2){return FALSE;}
  echo '<span class="l_s">'.PHP_EOL;
  foreach($conf['langs'] as $v){//проход по языкам системы
   if($v['tag']!==$conf['user_lang']&&$v['public']=='on'){//язык опубликован, язык не текущего материала
    if(isset($versions[$v['tag']])){//есть языковые версии материала
     echo '<a href="'.$versions[$v['tag']]['url'].'" title="'.$versions[$v['tag']]['title'].'">'.$v['title'].'</a>';
    }
    if(in_array($segment,['/','contact'])){//текущая страница: главная или контакты
     echo '<a href="/do/change_lang/'.$v['tag'].'/'.$segment.'">'.$v['title'].'</a>';
    }
   }
  }
  echo '</span>'.PHP_EOL;
}}