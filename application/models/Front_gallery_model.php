<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Front_gallery_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_gallery($alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get('gallerys')->result_array();
  return isset($q[0])?$q[0]:FALSE;
 }

}
