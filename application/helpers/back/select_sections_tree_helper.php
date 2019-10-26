<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
class Select_section{//вывод дерева разделов в выпадающем списке
 private $CI,$data,$q,$i,$s;
 function __construct(){
  $this->CI=&get_instance();
  $this->data=$this->CI->app('data');
  $this->i=isset($this->data['id'])?$this->data['id']:FALSE;
  $this->s=isset($this->data['section'])?$this->data['section']:FALSE;
  $lang=$this->data['lang'];
  $this->q=$this->CI->db->where('lang',$lang)->select('title,id,section')->get('sections')->result_array();
  $this->get_select();//вывод
 }

 private function get_select(){
  echo '<label class="select"><select name="section">'.PHP_EOL;
  echo '<option value="">Нет</option>'.PHP_EOL;
  echo $this->get_options($this->q);//вывод дерева опций
  echo '</select></label>'.PHP_EOL;
 }

 private function get_options(&$input,$section='',$level=0){
  if(empty($input)){return FALSE;}
  $options='';//будет содержать дерево опций списка
  foreach($input as $k=>$v){//обход входного массива
   if($v['section']==$section){//начать заполнять с корня
    $bufer='<option value="'.$v['id'].'" '
           .($this->s&&$this->s==$v['id']?'selected':FALSE).' '
           .($this->i&&$this->i==$v['id']?'disabled':FALSE).'>'
           .str_repeat('&#183; ',$level).$v['title']
           .'</potion>'.PHP_EOL;//записать в буфер
    unset($input[$k]);//удалить записанный элемент из входного массива
    $sublevel=$this->get_options($input,$v['id'],$level+1);//рекурсивно выбрать дочерние элементы
    if($sublevel){$bufer.=$sublevel;}//есть дочерние - записать в буфер
    $options.=$bufer;//записать буфер в дерево опций списка
   }
  }
  return $options;
 }

}