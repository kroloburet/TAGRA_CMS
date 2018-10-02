<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Front_comment_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_comment($data=array()){
  return $this->db->insert($this->_prefix().'comments',$data)?TRUE:FALSE;
 }

 function add_comment_rating($id,$rating=''){
  return $this->db->where('id',$id)->update($this->_prefix().'comments',array('rating'=>$rating))?TRUE:FALSE;
 }

}
