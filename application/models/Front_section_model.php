<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Front_section_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_section($id){
  $q=$this->db->where(['public'=>'on','id'=>$id])->get('sections')->result_array();
  return isset($q[0])?$q[0]:FALSE;
   }

 function get_sub_sections($id){
  $q=$this->db->where(['public'=>'on','section'=>$id])->get('sections')->result_array();
  return !empty($q)?$q:FALSE;
  }

 function get_sub_gallerys($id){
  $q=$this->db->where(['public'=>'on','section'=>$id])->get('gallerys')->result_array();
  return !empty($q)?$q:FALSE;
  }

 function get_sub_pages($id){
  $q=$this->db->where(['public'=>'on','section'=>$id])->get('pages')->result_array();
  return !empty($q)?$q:FALSE;
  }

}