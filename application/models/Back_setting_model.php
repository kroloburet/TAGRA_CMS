<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

class Back_setting_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function set_config($data){
  foreach($data as $name=>$value){
   $this->db->where('name',$name)->update('config',['name'=>$name,'value'=>$value]);
  }
 }

}