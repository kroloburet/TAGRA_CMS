<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Front_basic_model extends CI_Model{
 function __construct(){
  parent::__construct();
 }

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

 function app($path=NULL){//получить массив или значение конфигурации и вспомагательных данных ресурса
  //$path-(строка) путь к значению или NULL чтобы получить весь массив app. $this->app('conf.langs')=$app['conf']['langs']
  if(empty($this->config->item('app'))){$this->config->set_item('app',$this->front_basic_model->get_config());}//заполнить если пуст
  if(!$path||!is_string($path)){return $this->config->item('app');}//вернуть весь массив если путь не передан
  return array_reduce(explode('.',$path),function($i,$k){//обработать путь и вернуть значение массива
    return isset($i[$k])?$i[$k]:NULL;
  },$this->config->item('app'));
 }

 function set_app($data=[]){//изменение\добавление значений в массиве конфигурации
  //$data-(массив) путь=>значение ['conf.langs.ru.title'=>'RU','lexic.basic.home'=>'Домой']
  if(empty($data)){return false;}
  foreach($data as $path=>$val){
   $level=&$this->config->config['app'];
   foreach(explode('.',$path) as $k){
    if(!key_exists($k,$level)||!is_array($level[$k])){$level[$k]=[];}
    $level=&$level[$k];
   }
   $level=$val;
  }
 }

 function get_config(){
  foreach($this->db->get('config')->result_array() as $v){
   $json=@json_decode($v['value'],TRUE);
   $data['conf'][$v['name']]=$json===NULL?$v['value']:$json;//если значение - json - преобразовать в массив
  }
  //модераторы системы
  $m=[];//массив будет хранить emailы всех модераторов
  $ip=$this->input->server('REMOTE_ADDR');//текущий ip
  foreach($this->db->get('back_users')->result_array() as $v){
   $data['conf']['back_user']=$v['ip']===$ip&&$v['access']==='on'?TRUE:FALSE;//это админ/разрешенный модератор или обычный смертный
   switch($v['status']){
    case'administrator':$data['conf']['admin_mail']=$v['email'];break;
    case'moderator':if($v['access']==='on'){array_push($m,$v['email']);}break;
   }
  }
  $data['conf']['moderator_mail']=implode(',',$m);//emailы всех разрешенных модераторов в строку через запятую
  //языки в системе и язык пользователя
  $data['conf']['langs']=$this->db->get('languages')->result_array();//языки системы
  foreach($data['conf']['langs'] as $i){if($i['def']=='on'){$data['conf']['lang_def']=$i;break;}}//язык по умолчанию
  $tags=array_column($data['conf']['langs'],'tag');//массив тегов языков системы
  $hal=substr($this->input->server('HTTP_ACCEPT_LANGUAGE'),0,2);//тег языка браузера пользователя
  $ulc=$this->input->cookie('user_lang');//куки с языком пользоватля
  $data['conf']['user_lang']=$ulc&&in_array($ulc,$tags)?$ulc:(in_array($hal,$tags)?$hal:$data['conf']['lang_def']['tag']);
  $data['lexic']=$this->lang->load('front_template',$data['conf']['user_lang'],TRUE);
  //возврат
  return $data;
 }

///////////////////////////////////
//добавить/редактировать/удалить
///////////////////////////////////

 function add($data,$tabl){
  return $this->db->insert($tabl,$data)?TRUE:FALSE;
 }

 function edit($id,$data,$tabl) {
  return $this->db->where('id',$id)->update($tabl,$data)?TRUE:FALSE;
 }

 function del($tab,$id){
  return $this->db->where('id',$id)->delete($tab)?TRUE:FALSE;
 }

///////////////////////////////////
//работа с меню
///////////////////////////////////

 function get_menu(){
  $q=$this->db->where(['public'=>'on','lang'=>$this->app('conf.user_lang')])->order_by('order')->get('menu')->result_array();
  if(empty($q)){return [];}
  function maketree($input,$pid=0){//выборку в многомерный массив
   $output=[];//будет содержать результирующий массив
   foreach($input as $n=>$v){//обход входного массива
    if($v['pid']==$pid){//родитель равен запрашиваемому
     $bufer=$v;//записать в буфер
     unset($input[$n]);//удалить записанный элемент из входного массива
     $nodes=maketree($input,$v['id']);//рекурсивно выбрать дочерние элементы
     if(count($nodes)>0){$bufer['nodes']=$nodes;}//есть дочерние - записать в буфер
     $output[]=$bufer;//записать буфер в результирующий массив
    }
   }
   return $output;
  }
  return maketree($q);
 }

}
