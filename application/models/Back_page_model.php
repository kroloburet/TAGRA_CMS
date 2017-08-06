<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Back_page_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_page(/* изменения по id */$id,/* значения полей */$post_arr,/* алиас раздела */$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'_pages',$post_arr);
   $url=$alias;
   //перезапись url комментариев этой страницы
   $this->db->where('url',$url)->update($this->_prefix().'_comments',array('url'=>$post_arr['alias']));
   //перезапись url пунктов меню на эту страницу
   $this->db->where('url','/'.$url)->update($this->_prefix().'_menu',array('url'=>'/'.$post_arr['alias']));
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'_pages',$post_arr);
  }
 }

 function del_page(/* alias галереи */$alias){//удаление страницы и комментариев к ней
  $this->db->where('alias',$alias)->delete($this->_prefix().'_pages');
  //удаление комментариев этого раздела
  $url=$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'_comments');
 }

}
