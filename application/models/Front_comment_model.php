<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Front_comment_model extends Front_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_comment($data=[]){
  return $this->db->insert('comments',$data)?TRUE:FALSE;
 }

 function add_comment_rating($id,$rating=''){
  return $this->db->where('id',$id)->update('comments',['rating'=>$rating])?TRUE:FALSE;
 }

 function public_new($code){
  return $this->db->where(['premod_code'=>$code,'public'=>'off'])->update('comments',['public'=>'on','date'=>date('Y-m-d'),'premod_code'=>''])?TRUE:FALSE;
 }

 function del_new($code){
  return $this->db->where(['premod_code'=>$code,'public'=>'off'])->delete('comments')?TRUE:FALSE;
 }

 function del_branch($id,$url){
  global $ids;
  $q=$this->db->where('url',$url)->get('comments')->result_array();
  $ids[]=$id;
  function get_branch_ids($arr,$id){
   global $ids;
   foreach($arr as $v){
    if($id===$v['pid']){
     $ids[]=$v['id'];
     get_branch_ids($arr,$v['id']);
    }
   }
  }
  get_branch_ids($q,$id);
  return $this->db->where_in('id',$ids)->delete('comments')?$ids:FALSE;
 }

}
