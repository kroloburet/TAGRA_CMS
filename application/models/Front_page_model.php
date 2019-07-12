<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Front_page_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function is_page($alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get($this->_prefix().'pages')->result_array();
  if(!empty($q)){//если в базе есть запись с таким алиасом
   foreach($q as $data){//получить и сделать массив
    foreach($data as $k=>$v){$data[$k]=$v;}
   }
   return $data;
  }else{//в базе нет такого алиаса
   return FALSE;
  }
 }

}
