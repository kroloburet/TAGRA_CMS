<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с страницами
///////////////////////////////////

class Back_page_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_page($data){
  if($this->db->insert('pages',$data)&&$data['versions']){
   $this->set_versions('pages',$data);//добавить связи с материалом в версиях
  }
 }

 function edit_page($id,$data){
  $q=$this->db->where('id',$id)->get('pages')->result_array();//изменяемый материал
  if($q[0]['alias']!==$data['alias']){//алиас изменился
   $url=$q[0]['alias'];
   $this->db->where('url',$url)->update('comments',['url'=>$data['alias']]);//перезаписать url комментариев
   $this->db->where('url','/'.$url)->update('menu',['url'=>'/'.$data['alias']]);//перезаписать url пунктов меню
  }
  if($q[0]['alias']!==$data['alias']||$q[0]['versions']!==$data['versions']){//алиас или версии изменились
   $this->set_versions('pages',$data,$q[0]);//добавить/обновить связи с материалом в версиях
  }
  $this->db->where('id',$id)->update('pages',$data);
 }

 function del_page($alias){
  $this->db->where('alias',$alias)->delete('pages');
  $url=$alias;
  $this->db->where('url',$url)->delete('comments');//удалить комментарии к материалу
  $this->del_versions('pages','/'.$url);//удалить связи с материалом в версиях
 }

}
