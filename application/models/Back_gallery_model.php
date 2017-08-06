<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Back_gallery_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function edit_gallery(/* изменения по id */$id,/* значения полей */$post_arr,/* алиас раздела */$alias){
  if($post_arr['alias']!==$alias){//алиас изменился
   $this->db->where('id',$id)->update($this->_prefix().'_gallerys',$post_arr);
   $url='gallery/'.$alias;
   //перезапись url комментариев этой галереи
   $this->db->where('url',$url)->update($this->_prefix().'_comments',array('url'=>'gallery/'.$post_arr['alias']));
   //перезапись url пунктов меню на эту галерею
   $this->db->where('url','/'.$url)->update($this->_prefix().'_menu',array('url'=>'/gallery/'.$post_arr['alias']));
  }else{//алиас не менялся
   $this->db->where('id',$id)->update($this->_prefix().'_gallerys',$post_arr);
  }
 }

 function del_gallery(/* alias галереи */$alias){//удаление галереи и комментариев к ней
  $this->db->where('alias',$alias)->delete($this->_prefix().'_gallerys');
  //удаление комментариев этого раздела
  $url='gallery/'.$alias;
  $this->db->where('url',$url)->delete($this->_prefix().'_comments');
 }

}
