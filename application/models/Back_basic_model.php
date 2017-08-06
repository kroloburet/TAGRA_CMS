<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Back_basic_model extends CI_Model{
 function __construct(){
  parent::__construct();
 }

 function _prefix(){//получение префикса таблиц базы данных из конфигурационного файла
  return $this->config->item('db_tabl_prefix');
 }

 function add(/* значения полей */$post_arr,/* добавить в таблицу */$tabl){
  $this->db->insert($tabl,$post_arr);
 }

 function edit(/* изменения по id */$id,/* значения полей */$post_arr,/* изменения в таблице */$tabl){
  $this->db->where('id',$id)->update($tabl,$post_arr);
 }

 function del(/* в таблице */$tab,/* id страницы */$id){//удаление из таблицы $tab по $id
  $this->db->where('id',$id)->delete($tab);
 }

 function toggle_public(/* id страницы */$id,/* в таблице */$tab,/* on/off */$pub){
  if($pub==='off'){
   $this->db->where('id',$id)->update($tab,array('public'=>'on'));
   return 'on';
  }elseif($pub==='on'){
   $this->db->where('id',$id)->update($tab,array('public'=>'off'));
   return 'off';
  }
 }

///////////////////////////////////
//получение, проверка, фильтр данных
///////////////////////////////////
 

 function get_tabl($tabl){
  $q=$this->db->get($tabl)->result_array();
  return empty($q)?FALSE:$q;
 }
 
 function get_where_id($tabl,$id){
  foreach($this->db->where('id',$id)->get($tabl)->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

 function get_val(
         /* таблица */$tab,
         /* поле */$field,
         /* с значением (находим запись */$field_val,
         /* получить значение (из найденной записи) */$res_field
         ){
  foreach ($this->db->get_where($tab,array($field=>$field_val))->result() as $row){
   return $row->$res_field;
  }
 }

 function check_title($title,$id,$tab){//проверка на уникальность title в таблице БД
  $where=$id?array('title'=>$title,'id !='=>$id):array('title'=>$title);
  $q=$this->db->where($where)->get($this->_prefix().'_'.$tab)->result_array();
  return empty($q)?FALSE:TRUE;
 }
 
 function get_result_list($table,$get_arr=array()){//получаю выборку, сортирую, фильтрую, поиск
 //$table=таблица для запроса "pages", "sections"...
 //$get_arr=get-массив формы фильтра (отсутствующие значения должны быть установлены по умолчанию)
 //$context_search=массив имен полей в которых будет поиск значения из $get_arr['search']
  if(empty($get_arr)){return array();}
  //комментарии только опубликованные
  $table==='comments'?$this->db->where('public','on'):TRUE;
  //если сортировка по id, сортирую результат - последняя запись сверху
  ($get_arr['order']=='id')?$this->db->order_by('id','DESC'):$this->db->order_by($get_arr['order'],'ASC');
  //поиск $get_arr['search'] в поле $get_arr['context_search']
  if($get_arr['search']!==''){
   $like=$get_arr['context_search']==='content'?array('layout_t'=>$get_arr['search'],'layout_b'=>$get_arr['search'],'layout_l'=>$get_arr['search'],'layout_r'=>$get_arr['search']):array($get_arr['context_search']=>$get_arr['search']);
   $this->db->or_like($like);
  }  
  $q['count_result']=$this->db->count_all_results($this->_prefix().'_'.$table,FALSE);
  $this->db->limit($get_arr['pag_per_page'],$get_arr['per_page']);
  $q['result']=$this->db->get()->result_array();
  return $q;
 }
 
///////////////////////////////////
//конфигурация и пользователи админки
///////////////////////////////////

 function get_back_users($email=FALSE){
  if($email){$this->db->where('email',$email);}
  $q=$this->db->get($this->_prefix().'_back_users')->result_array();
  return empty($q)?FALSE:$q;
 }

 function set_user($id,$post_arr=array()){
   $this->db->where('id',$id)->update($this->_prefix().'_back_users',$post_arr);
 }

 function my_config_data(){//таблицу _my_config в масив $data['name']='value'
  foreach($this->db->get($this->_prefix().'_my_config')->result_array() as $v){$data[$v['name']]=$v['value'];}
  return $data;
 }

///////////////////////////////////
//работа с картой сайта
///////////////////////////////////

 function sitemap_config_data(){//таблицу _my_config в масив $data['name']='value'
  foreach($this->db->get($this->_prefix().'_sitemap_config')->result_array() as $v){$data[$v['name']]=$v['value'];}
  return $data;
 }

 function set_sitemap_config($post_arr=array()){
  foreach($post_arr as $name=>$value){
   $this->db->where('name',$name)->update($this->_prefix().'_sitemap_config',array('name'=>$name,'value'=>$value));
  }
 }

}
