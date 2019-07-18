<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
class Section_alias_to_title{
 protected $sections=[];
 function __construct(){
  $CI=&get_instance();
  $q=$CI->db->select('title,alias')->get('sections')->result_array();
  if(!empty($q)){
   foreach($q as $v){
    $this->sections[$v['alias']]=$v['title'];
   }
  }
 }

 function get_title($alias){
  return isset($this->sections[$alias])?mb_strimwidth($this->sections[$alias],0,20,'...'):'';
 }

}