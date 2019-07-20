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
  if($q[0]['alias']!==$data['alias']){//алиас изменился
   $this->db->where('section',$q[0]['alias'])->update('sections',['section'=>$data['alias']]);
   $this->db->where('section',$q[0]['alias'])->update('pages',['section'=>$data['alias']]);
   $this->db->where('section',$q[0]['alias'])->update('gallerys',['section'=>$data['alias']]);
   $url='section/'.$q[0]['alias'];
   $this->db->where('url',$url)->update('comments',['url'=>'section/'.$data['alias']]);//перезаписать url комментариев
   $this->db->where('url','/'.$url)->update('menu',['url'=>'/section/'.$data['alias']]);//перезаписать url пунктов меню
  }
  if($q[0]['alias']!==$data['alias']||$q[0]['versions']!==$data['versions']){//алиас или версии изменились
   $this->set_versions('sections',$data,$q[0]);//добавить/обновить связи с материалом в версиях
  }
  $this->db->where('id',$id)->update('sections',$data);
 }

 function del_section($alias){
  $this->db->where('alias',$alias)->delete('sections');
  $this->db->where('section',$alias)->update('sections',['section'=>'']);
  $this->db->where('section',$alias)->update('pages',['section'=>'']);
  $this->db->where('section',$alias)->update('gallerys',['section'=>'']);
  $url='section/'.$alias;
  $this->db->where('url',$url)->delete('comments');//удалить комментарии к материалу
  $this->del_versions('sections','/'.$url);//удалить связи с материалом в версиях
 }

}
