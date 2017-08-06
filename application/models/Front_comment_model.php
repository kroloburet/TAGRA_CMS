<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Front_comment_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_comment($data=array()){
  return $this->db->insert($this->_prefix().'_comments',$data)?TRUE:FALSE;
 }

}
