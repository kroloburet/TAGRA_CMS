<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с главной страницей
///////////////////////////////////

class Back_home_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_home_page(){
  foreach($this->db->get($this->_prefix().'_index_page')->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

 function edit_home_page(/* значения полей */$post_arr=array()){
  $this->db->update($this->_prefix().'_index_page',$post_arr);
 }

}
