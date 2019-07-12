<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Front_section_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_section($alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get($this->_prefix().'sections')->result_array();
  if(!empty($q)){//если в базе есть запись с таким алиасом
   foreach($q as $data){//получить и сделать массив
    foreach($data as $k=>$v){$data[$k]=$v;}
   }
   return $data;
  }else{//в базе нет такого алиаса
   return FALSE;
  }
 }

 function get_sub_sections($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get($this->_prefix().'sections')->result_array();
  if(!empty($q)){//если в базе есть запись с таким алиасом
   return $q;
  }else{
   return FALSE;
  }
 }

 function get_sub_gallerys($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get($this->_prefix().'gallerys')->result_array();
  if(!empty($q)){//если в базе есть запись с таким алиасом
   return $q;
  }else{
   return FALSE;
  }
 }

 function get_sub_pages($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get($this->_prefix().'pages')->result_array();
  if(!empty($q)){//если в базе есть запись с таким алиасом
   return $q;
  }else{
   return FALSE;
  }
 }

}
