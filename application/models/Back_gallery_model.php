<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с галереями
///////////////////////////////////

class Back_gallery_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function add_gallery($data){
  if($this->db->insert('gallerys',$data)&&$data['versions']){
   $this->set_versions('gallerys',$data);//добавить связи с материалом в версиях
  }
 }

 function edit_gallery($id,$data){
  $q=$this->db->where('id',$id)->get('gallerys')->result_array();//изменяемый материал
  if($q[0]['versions']!==$data['versions']){//версии изменились
   $this->set_versions('gallerys',$data,$q[0]);//добавить/обновить связи с материалом в версиях
  }
  $this->db->where('id',$id)->update('gallerys',$data);
 }

 function del_gallery($id){
  $this->db->where('id',$id)->delete('gallerys');
  $url='gallery/'.$id;
  $this->db->where('url',$url)->delete('comments');//удалить комментарии к материалу
  $this->del_versions('gallerys','/'.$url);//удалить связи с материалом в версиях
 }

}