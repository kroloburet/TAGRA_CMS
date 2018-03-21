<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('select_sections')){//вывод дерева разделов в выпадающем списке
 function select_sections(/*раздел страницы*/$sect='',/*алиас страницы*/$ali='',/*исключить из списка раздел если его алиас == $ali*/$cut=FALSE){
  $tree=array();
  $CI=&get_instance();
  $prefix=$CI->config->item('db_tabl_prefix');
  $query=$CI->db->select('title, alias,section')->get($prefix.'sections')->result_array();
  if(empty($query)){
   echo '<br><label class="select inline width90" style="vertical-align:baseline;"><select name="section">';
   echo '<option value="">Нет</option>';
   echo '</select></label><a href="'.base_url('admin/section/add_form').'"><i class="fa-plus-circle fa-lg green" title="Добавить раздел"></i></a><br>';
   return FALSE;
  }
  $nodes=array();
  $keys=array();
  foreach($query as $node){
   $nodes[$node['alias']]=&$node; //заполняем список веток записями из БД
   $keys[]=$node['alias'];//заполняем список ключей(ID)
   unset($node);
  }
  foreach($keys as $key){
   if($nodes[$key]['section']===''){//если нашли главную ветку(или одну из главных), то добавляем её в дерево
    $tree[]=&$nodes[$key];
   }else{//else находим родительскую ветку и добавляем текущую ветку к дочерним элементам родит.ветки.
    if(isset($nodes[$nodes[$key]['section']])){ //на всякий случай, вдруг в базе есть потерянные ветки
     if(!isset($nodes[$nodes[$key]['section']]['chields'])) //если нет поля определяющего наличие дочерних веток
      $nodes[$nodes[$key]['section']]['chields']=array(); //то добавляем к записи узел (массив дочерних веток) на данном этапе
     $nodes[$nodes[$key]['section']]['chields'][]=&$nodes[$key];
    }
   }
  }
  //вывод выпадающего списка
  $nbspCnt=0;
  $curPos=0;
  $count=count($tree);
  $stateArray=array();
  $repeat='&#183; ';
  if($sect==''){$selected='selected';}else{$selected='';}
  echo '<br><label class="select inline width90" style="vertical-align:baseline;"><select name="section">';
  echo '<option value="" '.$selected.'>Нет</option>';
  do{
   while($curPos<$count){
    $selected='';
    $disabled='';
    if($sect==$tree[$curPos]['alias']){$selected='selected';}
    if($cut===TRUE){if($tree[$curPos]['alias']==$ali){$disabled='disabled';}}
    echo '<option value="'.$tree[$curPos]['alias'].'" '.$selected.' '.$disabled.'>'.str_repeat($repeat,$nbspCnt).$tree[$curPos]['title'].'</option>';
    if(isset($tree[$curPos]['chields'])){
     array_push($stateArray,array('tree'=>&$tree,'count'=>$count,'curPos'=>$curPos+1));
     $tree=&$tree[$curPos]['chields'];
     $count=count($tree);
     $curPos=0;
     $nbspCnt++;
    }else{
     $curPos++;
    }
   }
   if(($a=array_pop($stateArray))!==null){
    $curPos=$a['curPos'];
    $count=$a['count'];
    $tree=$a['tree'];
    $nbspCnt--;
   }
  }while($a!=null);
  echo '</select></label><a href="'.base_url('admin/section/add_form').'"><i class="fa-plus-circle fa-lg green" title="Добавить раздел"></i></a><br>';
 }

}
