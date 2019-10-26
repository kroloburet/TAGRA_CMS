<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с языками
///////////////////////////////////

class Back_language_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function reset_def_lang(){
  return $this->db->where('def','on')->update('languages',['def'=>''])?TRUE:FALSE;
 }

 function del_lang($tag){
  $tables=['index_pages','contact_pages','pages','sections','gallerys','comments','menu'];
  $this->db->delete($tables,['lang'=>$tag]);//удалить все материалы на удаляемом языке
  foreach($tables as $table){//поиск и удаление связей материалов удаляемого языка в версиях
   if(!in_array($table,['pages','sections','gallerys'])){continue;}//искать только в этих материалах или выход
   $q=$this->db->select('id,versions')->like('versions','"'.$tag.'"')->get($table)->result_array();//выбрать записи с удаляемым языком в версиях
   if(empty($q)){continue;}//если выборка пуста - выход
   foreach($q as $k=>$v){//проход по выборке
    $vers=json_decode($v['versions'],TRUE);//версии в массив
    if(isset($vers[$tag])){//найден удаляемый язык
     unset($vers[$tag]);//удалить из версий
     $q[$k]['versions']=empty($vers)?'':json_encode($vers,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);//версии в json, если не пусто
    }
   }
   !empty($q)?$this->db->update_batch($table,$q,'id'):FALSE;//перезаписать измененные версии в таблице
  }
  $this->db->delete('languages',['tag'=>$tag]);//удалить язык
 }

}