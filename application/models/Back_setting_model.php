<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

class Back_setting_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function set_config($post_arr=array()){
  foreach($post_arr as $name=>$value){
   $this->db->where('name',$name)->update($this->_prefix().'config',array('name'=>$name,'value'=>$value));
  }
 }

}
