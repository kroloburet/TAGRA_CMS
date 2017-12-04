<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с главной страницей
///////////////////////////////////

class Front_home_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_home_page(){
  foreach($this->db->get($this->_prefix().'index_page')->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

}
