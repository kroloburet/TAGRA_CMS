<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа со страницами "Главная"
///////////////////////////////////

class Back_home_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_home_page(/*id страницы*/$id,/*значения полей*/$data){
  $this->db->where('id',$id)->update('index_pages',$data);
 }

}
