<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Back_page_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_page(/*изменения по id*/$id,/*значения полей*/$post_arr,$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'pages',$post_arr);
   $url=$alias;
   //перезапись url комментариев
   $this->db->where('url',$url)->update($this->_prefix().'comments',array('url'=>$post_arr['alias']));
   //перезаписать url пунктов меню
   $this->db->where('url','/'.$url)->update($this->_prefix().'menu',array('url'=>'/'.$post_arr['alias']));
   //перезаписать связанные ссылки
   $this->links_url_replace('/'.$alias,'/'.$post_arr['alias']);
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'pages',$post_arr);
  }
 }

 function del_page($alias){
  $this->db->where('alias',$alias)->delete($this->_prefix().'pages');
  //удаление комментариев
  $url=$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'comments');
  //удаление связанных ссылок
  $this->links_url_del('/'.$alias);
 }

}
