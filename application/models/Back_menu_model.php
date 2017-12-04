<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//работа с меню
///////////////////////////////////

class Back_menu_model extends Back_basic_model{
 function __construct(){
  parent::__construct();
 }

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
  $m_tree=array();
  $this->db->order_by('order','ASC');//порядок пунктов
  $m_query=$this->db->get($this->_prefix().'menu')->result_array();
  if(!$m_query){return $m_tree;}
  $m_nodes=array();
  $m_keys=array();
  foreach($m_query as $m_node){
   $m_nodes[$m_node['id']]=&$m_node;//заполняем список веток записями из БД
   $m_keys[]=$m_node['id'];//заполняем список ключей(ID)
   unset($m_node);
  }
  foreach($m_keys as $m_key){//если нашли главную ветку(или одну из главных), то добавляе меё в дерево
   if($m_nodes[$m_key]['pid']==='0'){
    $m_tree[]=&$m_nodes[$m_key];
   }else{//находим родительскую ветку и добавляем текущую ветку к дочерним элементам родит.ветки.
    if(isset($m_nodes[$m_nodes[$m_key]['pid']])){//на всякий случай, вдруг в базе есть потерянные ветки
     if(!isset($m_nodes[$m_nodes[$m_key]['pid']]['nodes'])){//если нет поля определяющего наличие дочерних веток
      $m_nodes[$m_nodes[$m_key]['pid']]['nodes']=array();//то добавляем к записи узел (массив дочерних веток) на данном этапе
     }
     $m_nodes[$m_nodes[$m_key]['pid']]['nodes'][]=&$m_nodes[$m_key];
    }
   }
  }
  return $m_tree;
 }

 function add_menu_item($post_arr=array()){//добавление пункта меню
  $this->db->where(array('pid'=>$post_arr['pid'],'order >='=>$post_arr['order']));
  $q=$this->db->get($this->_prefix().'menu')->result_array();
  if($q){//если в базе есть пункты того же родителя, порядок которых больше или ровно добавляемого пункта
   foreach($q as $k){//обход массива
    $this->db->where('id',$k['id'])->update($this->_prefix().'menu',array('order'=>$k['order']+1));//порядковый номер каждого увеличить на 1
   }
  }
  $this->db->insert($this->_prefix().'menu',$post_arr);//добавить пункт
 }

 function del_menu_item($id,$pid='',$order=''){//удаление веток меню
  if($pid!==''&&$order!==''){//чтобы в случае рекурссии не выполнять запрос к базе
   $this->db->where(array('pid'=>$pid,'order >'=>$order));
   $q=$this->db->get($this->_prefix().'menu')->result_array();
   if($q){//если в базе есть пункты того же родителя, порядок которых больше удаляемого пункта
    foreach($q as $k){//обход массива
     $this->db->where('id',$k['id'])->update($this->_prefix().'menu',array('order'=>$k['order']-1));//порядковый номер каждого уменьшить на 1
    }
   }
  }
  $this->db->where('id',$id)->delete($this->_prefix().'menu');//нахожу по id, удаляю
  $w=$this->db->where('pid',$id)->get($this->_prefix().'menu')->result_array();//поиск дочерних веток
  if($w){//если есть дочерние ветки - рекрссия
   foreach($w as $pids){$this->del_menu_item($pids['id']);}
  }
 }

 function public_menu_item($id,$pub){
  if($pub==='off'){
   return $this->db->where('id',$id)->update($this->_prefix().'menu',array('public'=>'on'));
  }elseif($pub==='on'){
   return $this->db->where('id',$id)->update($this->_prefix().'menu',array('public'=>'off'));
  }
 }

}
