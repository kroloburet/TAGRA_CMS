<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с меню
///////////////////////////////////

class Back_menu_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

 function get_menu($lang){
  $q=$this->db->where('lang',$lang)->order_by('order')->get('menu')->result_array();
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

 function add_item($data){//добавить пункт, изменить порядок пунктов
  $q=$this->db->where(['lang'=>$data['lang'],'pid'=>$data['pid'],'order >='=>$data['order']])->get('menu')->result_array();
  $ids=[];//массив id с новым порядком для изменения
  if($q){//есть пункты того же родителя, порядок которых больше или равен добавляемого пункта
   foreach($q as $v){
    $ids[]=['id'=>$v['id'],'order'=>$v['order']+1];
   }
  }
  return (
   $this->db->insert('menu',$data)&&
   empty($ids)?TRUE:$this->db->update_batch('menu',$ids,'id')
  );
 }

 function edit_item($data){//изменить пункт
  $q=$this->db->where('lang',$data['lang'])->get('menu')->result_array();//выборка всех пунктов языка
  foreach($q as $k=>$v){$q[$v['id']]=$v;unset($q[$k]);}//формат выборки
  $id=$data['id'];//пункт который изменяется
  $old_ord=$q[$id]['order'];//старое место
  $old_pid=$q[$id]['pid'];//старый родитель
  $new_ord=$data['order'];//новое место
  $new_pid=$data['pid'];//новый родитель
  if($new_ord!==$old_ord || $new_pid!==$old_pid){//пункт перемещается - удалить из старого места, вставить в новое
   foreach($q as $k=>$v){//пересчитать порядок (удаление)
    if($v['id']===$id){continue;}//не обрабатывать изменяемый пункт
    if($v['pid']===$old_pid && $v['order']>$old_ord){$q[$k]['order']=$v['order']-1;}//в группе старого родителя, изменить порядок
   }
   foreach($q as $k=>$v){//пересчитать порядок (вставка)
    if($v['id']===$id){continue;}//не обрабатывать изменяемый пункт
    if($v['pid']===$new_pid && $v['order']>=$new_ord){$q[$k]['order']=$v['order']+1;}//в группе нового родителя, изменить порядок
   }
  }
  $q[$id]=$data;//перезаписать данные изменяемого пункта
  return $this->db->update_batch('menu',$q,'id');
 }

 function del_item($data){//удалить ветку пунктов, изменить порядок пунктов
  global $ids;
  $q=$this->db->where('lang',$data['lang'])->get('menu')->result_array();
  $ids['del'][]=$data['id'];//массив id для удаления
  $ids['decrement']=[];//массив id с новым порядком для изменения
  function get_del_ids($arr,$id){//рекурсивный сбор id пунктов ветки
   global $ids;
   foreach($arr as $v){
    if($id===$v['pid']){
     $ids['del'][]=$v['id'];
     get_del_ids($arr,$v['id']);
    }
   }
  }
  get_del_ids($q,$data['id']);
  foreach($q as $v){//сбор id с новым порядком
   if($data['pid']===$v['pid']&&$data['order']<$v['order']){//того же родителя, порядок которых больше удаляемого пункта
    $ids['decrement'][]=['id'=>$v['id'],'order'=>$v['order']-1];
   }
  }
  return (
   $this->db->where_in('id',$ids['del'])->delete('menu')&&
   empty($ids['decrement'])?TRUE:$this->db->update_batch('menu',$ids['decrement'],'id')
  );
 }

 function public_item($data){
  return $this->db->where('id',$data['id'])->update('menu',['public'=>$data['public']==='on'?'off':'on']);
 }

}