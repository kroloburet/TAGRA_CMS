<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Back_section_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_section(/*изменения по id*/$id,/*значения полей*/$post_arr,$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'sections',$post_arr);
   $this->db->where('section',$alias)->update($this->_prefix().'sections',['section'=>$post_arr['alias']]);
   $this->db->where('section',$alias)->update($this->_prefix().'pages',['section'=>$post_arr['alias']]);
   $this->db->where('section',$alias)->update($this->_prefix().'gallerys',['section'=>$post_arr['alias']]);
   $url='section/'.$alias;
   //перезаписать url комментариев
   $this->db->where('url',$url)->update($this->_prefix().'comments',['url'=>'section/'.$post_arr['alias']]);
   //перезаписать url пунктов меню
   $this->db->where('url','/'.$url)->update($this->_prefix().'menu',['url'=>'/section/'.$post_arr['alias']]);
   //перезаписать связанные ссылки
   $this->links_url_replace('/section/'.$alias,'/section/'.$post_arr['alias']);
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'sections',$post_arr);
  }
 }

 function del_section($alias){
  $this->db->where('alias',$alias)->delete($this->_prefix().'sections');
  $this->db->where('section',$alias)->update($this->_prefix().'sections',['section'=>'']);
  $this->db->where('section',$alias)->update($this->_prefix().'pages',['section'=>'']);
  $this->db->where('section',$alias)->update($this->_prefix().'gallerys',['section'=>'']);
  //удаление комментариев
  $url='section/'.$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'comments');
  //удаление связанных ссылок
  $this->links_url_del('/section/'.$alias);
 }

}
