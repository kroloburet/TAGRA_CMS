<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('section_alias_to_title')){
 
 function get_sections($tabl='sections'){//получить таблицу разделов
  $CI=& get_instance();
  $prefix=$CI->config->item('db_tabl_prefix');
  return $CI->db->select('title,alias')->get($prefix.'_'.$tabl)->result_array();
 }
 
 function section_alias_to_title($alias='',$tabl='sections'){//получить заголовок раздела по алиасу
  if($alias==''){return FALSE;}
  foreach(get_sections($tabl) as $v){
   echo $v['alias']==$alias?$v['title']:'';
  }
 }
}

