<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Front_basic_model extends CI_Model{
 function __construct(){
  parent::__construct();
 }

 function _prefix(){//получение префикса таблиц базы данных из конфигурационного файла
  return $this->config->item('db_tabl_prefix');
 }

 function get_val(
         /* таблица */$tab,
         /* поле */$field,
         /* с значением (находим запись */$field_val,
         /* получить значение (из найденной записи) */$res_field
         ){
  foreach($this->db->get_where($tab,[$field=>$field_val])->result() as $row){
   return $row->$res_field;
  }
 }

 function get_where_id($tabl,$id){
  foreach($this->db->where('id',$id)->get($tabl)->result_array() as $data){
   foreach($data as $k=>$v){$data[$k]=$v;}
  }
  return $data;
 }

 function get_where_alias($tabl,$alias){
  $q=$this->db->where(['public'=>'on','alias'=>$alias])->get($tabl)->result_array();
  if(!empty($q)){
   foreach($q as $data){
    foreach($data as $k=>$v){$data[$k]=$v;}
   }
   return $data;
  }else{
   return FALSE;
  }
 }

 function get_tabl($tabl){
  $q=$this->db->get($tabl)->result_array();
  return empty($q)?FALSE:$q;
 }

 function add(/* значения полей */$post_arr,/* добавить в таблицу */$tabl){
  return $this->db->insert($tabl,$post_arr)?TRUE:FALSE;
 }

 function edit(/* изменения по id */$id,/* значения полей */$post_arr,/* изменения в таблице */$tabl) {
  return $this->db->where('id',$id)->update($tabl,$post_arr)?TRUE:FALSE;
 }

 function del(/* в таблице */$tab,/* id страницы */$id){//удаление из таблицы $tab по $id
  return $this->db->where('id',$id)->delete($tab)?TRUE:FALSE;
 }

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

 function get_config(){
  //таблицу config в масив $data['name']='value'
  foreach($this->db->get($this->_prefix().'config')->result_array() as $v){
   $json=@json_decode($v['value'],TRUE);
   $data[$v['name']]=$json===NULL?$v['value']:$json;//если значение - json - преобразовать в массив
  }
  $m=[];//массив будет хранить emailы всех модераторов
  $ip=$this->input->server('REMOTE_ADDR');//текущий ip
  foreach($this->db->get($this->_prefix().'back_users')->result_array() as $v){
   $data['back_user']=$v['ip']===$ip&&$v['access']==='on'?TRUE:FALSE;//это админ/разрешенный модератор или обычный смертный
   switch($v['status']){
    case'administrator':$data['conf_admin_mail']=$v['email'];break;
    case'moderator':if($v['access']==='on'){array_push($m,$v['email']);}break;
   }
  }
  $data['conf_moderator_mail']=implode(',',$m);//emailы всех разрешенных модераторов в строку через запятую
  return $data;
 }

///////////////////////////////////
//работа с меню
///////////////////////////////////

 function get_menu(){
  /*
   * /////на входе
   * array('id'=>'1', 'pid'=>'0', 'name'=>'главная ветка');
   * array('id'=>'2', 'pid'=>'1', 'name'=>'дочерняя ветка1');
   * array('id'=>'3', 'pid'=>'1', 'name'=>'дочерняя ветка2');
   * /////на выходе
   * array('id'=>'1', 'pid'=>'0', 'name'=>'главная ветка', 'nodes'=>
   *   array(
   *      array('id'=>'2', 'pid'=>'1', 'name'=>'дочерняя ветка1'),
   *     array('id'=>'3', 'pid'=>'1', 'name'=>'дочерняя ветка2')
   *   )
   * );
   */
  $m_tree=$m_nodes=$m_keys=[];
  $this->db->order_by('order','ASC');//порядок пунктов
  $m_query=$this->db->where('public','on')->get($this->_prefix().'menu')->result_array();
  if(empty($m_query)){return $m_tree;}
  foreach($m_query as $m_node){
   $m_nodes[$m_node['id']]=&$m_node; //заполняем список веток записями из БД
   $m_keys[]=$m_node['id']; //заполняем список ключей(ID)
   unset($m_node);
  }
  foreach($m_keys as $m_key){//если нашли главную ветку(или одну из главных), то добавляе меё в дерево
   if($m_nodes[$m_key]['pid']==='0'){
    $m_tree[]=&$m_nodes[$m_key];
   }else{//находим родительскую ветку и добавляем текущую ветку к дочерним элементам родит.ветки.
    if(isset($m_nodes[$m_nodes[$m_key]['pid']])){//на всякий случай, вдруг в базе есть потерянные ветки
     if(!isset($m_nodes[$m_nodes[$m_key]['pid']]['nodes'])){ //если нет поля определяющего наличие дочерних веток
      $m_nodes[$m_nodes[$m_key]['pid']]['nodes']=[]; //то добавляем к записи узел (массив дочерних веток) на данном этапе
     }
     $m_nodes[$m_nodes[$m_key]['pid']]['nodes'][]=&$m_nodes[$m_key];
    }
   }
  }
  return $m_tree;
 }

}
