<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Front_gallery_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }
 
 function get_gallery($alias){
  $q=$this->db->where(array('public'=>'on','alias'=>$alias))->get($this->_prefix().'_gallerys')->result_array();
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
