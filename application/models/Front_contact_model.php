<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Front_contact_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_contact_page(){
  foreach($this->db->get($this->_prefix().'_contact_page')->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

}
