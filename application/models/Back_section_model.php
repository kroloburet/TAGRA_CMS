<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Back_section_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_section(/* изменения по id */$id,/* значения полей */$post_arr,/* алиас раздела */$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'_sections',$post_arr);
   $this->db->where('section',$alias)->update($this->_prefix().'_sections',array('section'=>$post_arr['alias']));
   $this->db->where('section',$alias)->update($this->_prefix().'_pages',array('section'=>$post_arr['alias']));
   $this->db->where('section',$alias)->update($this->_prefix().'_gallerys',array('section'=>$post_arr['alias']));
   $url='section/'.$alias;
   //перезапись url комментариев этого раздела
   $this->db->where('url',$url)->update($this->_prefix().'_comments',array('url'=>'section/'.$post_arr['alias']));
   //перезапись url пунктов меню на этот раздел
   $this->db->where('url','/'.$url)->update($this->_prefix().'_menu',array('url'=>'/section/'.$post_arr['alias']));
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'_sections',$post_arr);
  }
 }

 function del_section(/* alias раздела */$alias){//удаление раздела и назначение дочерним вместо родительского - пустую строку
  $this->db->where('alias',$alias)->delete($this->_prefix().'_sections');
  $this->db->where('section',$alias)->update($this->_prefix().'_sections',array('section'=>''));
  $this->db->where('section',$alias)->update($this->_prefix().'_pages',array('section'=>''));
  $this->db->where('section',$alias)->update($this->_prefix().'_gallerys',array('section'=>''));
  //удаление комментариев этого раздела
  $url='section/'.$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'_comments');
 }

}
