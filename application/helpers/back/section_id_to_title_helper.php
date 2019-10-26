<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
class Section_id_to_title{
 protected $sections=[];
 function __construct(){
  $CI=&get_instance();
  $q=$CI->db->select('title,id')->get('sections')->result_array();
  if(!empty($q)){
   foreach($q as $v){
    $this->sections[$v['id']]=$v['title'];
   }
  }
 }

 function get_title($id){
  return isset($this->sections[$id])?mb_strimwidth($this->sections[$id],0,20,'...'):'';
 }

}