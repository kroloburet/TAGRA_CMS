<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Back_comment_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_new_comments(){
  return $this->db->where('public','off')->get($this->_prefix().'_comments')->result_array();
 }

 function del_new_comment($id){
  $this->db->where('id',$id)->delete($this->_prefix().'_comments');
 }

 function public_new_comment($id){
  $this->db->where('id',$id)->update($this->_prefix().'_comments',array('public'=>'on','premod_code'=>''));
 }

}
