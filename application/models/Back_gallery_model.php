<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Back_gallery_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_gallery(/*изменения по id*/$id,/*значения полей*/$post_arr,$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'gallerys',$post_arr);
   $url='gallery/'.$alias;
   //перезаписать url комментариев
   $this->db->where('url',$url)->update($this->_prefix().'comments',['url'=>'gallery/'.$post_arr['alias']]);
   //перезаписать url пунктов меню
   $this->db->where('url','/'.$url)->update($this->_prefix().'menu',['url'=>'/gallery/'.$post_arr['alias']]);
   //перезаписать связанные ссылки
   $this->links_url_replace('/gallery/'.$alias,'/gallery/'.$post_arr['alias']);
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'gallerys',$post_arr);
  }
 }

 function del_gallery($alias){
  $this->db->where('alias',$alias)->delete($this->_prefix().'gallerys');
  //удаление комментариев
  $url='gallery/'.$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'comments');
  //удаление связанных ссылок
  $this->links_url_del('/gallery/'.$alias);
 }

}
