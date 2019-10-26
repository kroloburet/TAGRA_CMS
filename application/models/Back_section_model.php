<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с разделами
///////////////////////////////////

class Back_section_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_section($data){
  if($this->db->insert('sections',$data)&&$data['versions']){
   $this->set_versions('sections',$data);//добавить связи с материалом в версиях
  }
 }

 function edit_section($id,$data){
  $q=$this->db->where('id',$id)->get('sections')->result_array();//изменяемый материал
  if($q[0]['versions']!==$data['versions']){//версии изменились
   $this->set_versions('sections',$data,$q[0]);//добавить/обновить связи с материалом в версиях
  }
  $this->db->where('id',$id)->update('sections',$data);
 }

 function del_section($id){
  $this->db->where('id',$id)->delete('sections');
  $this->db->where('section',$id)->update('sections',['section'=>'']);
  $this->db->where('section',$id)->update('pages',['section'=>'']);
  $this->db->where('section',$id)->update('gallerys',['section'=>'']);
  $url='section/'.$id;
  $this->db->where('url',$url)->delete('comments');//удалить комментарии к материалу
  $this->del_versions('sections','/'.$url);//удалить связи с материалом в версиях
 }

}