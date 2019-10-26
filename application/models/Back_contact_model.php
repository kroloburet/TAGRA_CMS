<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа со страницами "Контакты"
///////////////////////////////////

class Back_contact_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_contact_page($id,$data){
  $this->db->where('id',$id)->update('contact_pages',$data);
 }

}