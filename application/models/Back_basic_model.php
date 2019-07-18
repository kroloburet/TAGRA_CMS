<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Back_basic_model extends CI_Model{
 function __construct(){
  parent::__construct();
 }

 function add($data,$table){
  return $this->db->insert($table,$data)?TRUE:FALSE;
 }

 function edit($id,$data,$table){
  return $this->db->where('id',$id)->update($table,$data)?TRUE:FALSE;
 }

 function del($table,$id){
  return $this->db->where('id',$id)->delete($table)?TRUE:FALSE;
 }

 function toggle_public($id,$table){
  //$id-(строка или число) id материала
  //$table-(строка) таблица материала в БД
  $q=$this->db->where('id',$id)->get($table)->result_array();//получить материал
  if(!isset($q[0]['public'])){return FALSE;}
  $this->db->where('id',$id)->update($table,['public'=>$q[0]['public']=='off'?'on':'off']);
  return $q[0]['public']=='off'?'on':'off';
 }

 function links_url_replace($search,$replace){//перзаписать связанные ссылки
  //$search-(строка) искомый url
  //$replace-(строка) замена
  $tables=['index_pages','pages','sections','gallerys'];
  foreach($tables as $table){//проход по таблицам
   $q=$this->db->select('id,links')->like('links','"'.$search.'"')->get($table)->result_array();//вернуть записи с искомой
   if(empty($q)){continue;}//в таблице нет записей
   foreach($q as $k=>$v){$q[$k]['links']=str_replace('"'.$search.'"','"'.$replace.'"',$v['links']);}//перезаписать искомые url в массиве
   $this->db->update_batch($table,$q,'id');//изменить в базе
  }
 }

 function links_url_del($search){//удалить связанные ссылки
  //$search-(строка) искомый url
  $tables=['index_pages','pages','sections','gallerys'];
  foreach($tables as $table){//проход по таблицам с полями id,links
   $q=$this->db->select('id,links')->like('links','"'.$search.'"')->get($table)->result_array();//вернуть записи с искомой
   if(empty($q)){continue;}//в таблице нет записей
   foreach($q as $k=>$v){//проход по записям
    $links=json_decode($v['links'],true);//json опций в массив
    foreach($links as $id=>$opt){if(is_array($opt)&&in_array($search,$opt)){unset($links[$id]);}}//проход по массиву опций, удалить искомое
    $q[$k]['links']=count($links)<=1?'':json_encode($links,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);//массив опций в json, для отправки
   }
   $this->db->update_batch($table,$q,'id');//изменить в базе записи с искомым
  }
 }

 function set_versions($table,$new=[],$old=[]){//добавить/редактировать версии материала
  //$table-(строка)таблица БД материала "pages", "sections"...
  //$new-массив новых данных материала (будут записаны в БД)
  //$old-массив старых данных материала (уже записаны в БД)
  if(!$table||empty($new)){return false;}
  global $db;
  $db=$this->db;
  function get_versions($table,$id){//получить в массив версии материала
   global $db;
   $q=$db->where('id',$id)->select('versions')->get($table)->result_array();
   return empty($q[0]['versions'])?[]:json_decode($q[0]['versions'],TRUE);
  }
  //удалить все связи с собой
  if(!empty($old['versions'])){//версии материала уже есть в БД
   foreach(json_decode($old['versions'],TRUE) as $k=>$v){//проход по версиям
    $q=get_versions($table,$v['id']);//получить в массив версии каждой из версий
    if(isset($q[$new['lang']])){//есть связи с материалами связываемого языка
     unset($q[$new['lang']]);//удалить связь
     $db->where('id',$v['id'])->update($table,['versions'=>empty($q)?'':json_encode($q,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);
    }
   }
  }
  //добавить связь с собой
  if(!empty($new['versions'])){
   $preurl='/';
   switch($table){
    case'sections':$preurl='/section/';break;
    case'gallerys':$preurl='/gallery/';break;
   }
   foreach(json_decode($new['versions'],TRUE) as $k=>$v){//пройти по новым связанным материалам
    $q=get_versions($table,$v['id']);//получить в массив версии каждой из версий
    if(isset($q[$new['lang']])){//есть связи с материалами связываемого языка
     $q2=get_versions($table,$q[$new['lang']]['id']);
     unset($q2[$k]);//удалить связь
     $db->where('id',$q[$new['lang']]['id'])->update($table,['versions'=>empty($q2)?'':json_encode($q2,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);
    }
    $db->where('id',$v['id'])->update($table,['versions'=>json_encode([$new['lang']=>['id'=>$new['id'],'title'=>$new['title'],'url'=>$preurl.$new['alias']]]+$q,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);
   }
  }
 }

 function del_versions($table,$url){//удалить связи с метериалом в версиях
  $q=$this->db->select('id,versions')->like('versions','"'.$url.'"')->get($table)->result_array();//вернуть записи с искомой
  if(empty($q)){return FALSE;}//в таблице нет записей
  foreach($q as $k=>$v){//проход по записям
   $vers=json_decode($v['versions'],true);
   foreach($vers as $lang=>$opt){if($opt['url']===$url){unset($vers[$lang]);}}//проход по массиву опций, удалить искомое
   $q[$k]['versions']=empty($vers)?'':json_encode($vers,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);//массив опций в json, для отправки
  }
  $this->db->update_batch($table,$q,'id');//изменить в базе записи с искомым
 }

///////////////////////////////////
//языки
///////////////////////////////////

 function get_langs(){
  return $this->db->get('languages')->result_array();
 }

///////////////////////////////////
//получение, проверка, фильтр данных
///////////////////////////////////


 function get_where_id($table,$id){
  $q=$this->db->where('id',$id)->get($table)->result_array();
  if(empty($q)){return FALSE;}
  foreach($q as $data){foreach($data as $k=>$v){$data[$k]=$v;}}
  return $data;
 }

 function get_val($table,$field,$field_val,$res_field){
  //$table-(строка) таблица
  //$field-(строка) поле
  //$field_val-(строка) значение в поле
  //$res_field-(строка) получить значение (из найденной записи)
  $q=$this->db->get_where($table,[$field=>$field_val])->result_array();
  if(empty($q)){return FALSE;}
  foreach ($q as $v){return $v[$res_field];}
 }

 function check_title($title,$id,$table){//проверка на уникальность title в таблице БД
  $where=$id?['title'=>$title,'id !='=>$id]:['title'=>$title];
  $q=$this->db->where($where)->get($table)->result_array();
  return empty($q)?FALSE:TRUE;
 }

 function get_result_list($table,$get=[]){//получает выборку, сортирует, фильтрует, поиск
 //$table-таблица для запроса "pages", "sections"...
 //$get_arr-get-массив формы фильтра (отсутствующие значения должны быть установлены по умолчанию)
 //$context_search-массив имен полей в которых будет поиск значения из $get_arr['search']
  if(empty($get)){return [];}
  //комментарии только опубликованные
  $table==='comments'?$this->db->where('public','on'):TRUE;
  //если сортировка по id, сортировать результат - последняя запись сверху
  ($get['order']=='id')?$this->db->order_by('id','DESC'):$this->db->order_by($get['order'],'ASC');
  //поиск $get_arr['search'] в поле $get_arr['context_search']
  if($get['search']!==''){
   $like=$get['context_search']==='content'?['layout_t'=>$get['search'],'layout_b'=>$get['search'],'layout_l'=>$get['search'],'layout_r'=>$get['search']]:[$get['context_search']=>$get['search']];
   $table==='comments'?$this->db->like($like):$this->db->or_like($like);
  }
  $q['count_result']=$this->db->count_all_results($table,FALSE);
  $this->db->limit($get['pag_per_page'],$get['per_page']);
  $q['result']=$this->db->get()->result_array();
  return $q;
 }

///////////////////////////////////
//конфигурация и пользователи админки
///////////////////////////////////

 function get_back_users($email=FALSE){
  if($email){$this->db->where('email',$email);}
  $q=$this->db->get('back_users')->result_array();
  return empty($q)?FALSE:$q;
 }

 function edit_back_user($id,$data=[]){
   return $this->db->where('id',$id)->update('back_users',$data)?TRUE:FALSE;
 }

 function get_config(){//таблицу config в масив $data['name']='value'
  foreach($this->db->get('config')->result_array() as $v){
   $json=@json_decode($v['value'],TRUE);
   $data[$v['name']]=$json===NULL?$v['value']:$json;//если значение - json - преобразовать в массив
  }
  return $data;
 }

}
