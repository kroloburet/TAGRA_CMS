<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Back_contact_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_contact_page(){
  foreach($this->db->get($this->_prefix().'contact_page')->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

 function edit_contact_page(/* значения полей */$post_arr=[]){
  $this->db->update($this->_prefix().'contact_page',$post_arr);
 }

}
