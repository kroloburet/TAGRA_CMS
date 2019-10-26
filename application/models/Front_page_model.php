<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Front_page_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_page($id){
  $q=$this->db->where(['public'=>'on','id'=>$id])->get('pages')->result_array();
  return isset($q[0])?$q[0]:FALSE;
   }

}