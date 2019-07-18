<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Front_section_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_section($alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get('sections')->result_array();
  return isset($q[0])?$q[0]:FALSE;
   }

 function get_sub_sections($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get('sections')->result_array();
  return !empty($q)?$q:FALSE;
  }

 function get_sub_gallerys($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get('gallerys')->result_array();
  return !empty($q)?$q:FALSE;
  }

 function get_sub_pages($alias){
  $q=$this->db->where(['public'=>'on','section'=>$alias])->get('pages')->result_array();
  return !empty($q)?$q:FALSE;
  }

}
