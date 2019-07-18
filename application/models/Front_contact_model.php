<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Front_contact_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_contact_page(){
  $q=$this->db->where('lang',$this->app('conf.user_lang'))->get('contact_pages')->result_array();
  return isset($q[0])?$q[0]:[];
 }

}
