<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Front_page_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function is_page($alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get('pages')->result_array();
  return isset($q[0])?$q[0]:FALSE;
   }

}
