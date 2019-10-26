<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
class Breadcrumbs{//вывод "хлебных крошек"
 private $CI,$conf,$data,$lexic,$list;
 function __construct(){
  $this->CI=&get_instance();
  $this->conf=$this->CI->app('conf.breadcrumbs');
  $this->data=$this->CI->app('data');
  $this->lexic=$this->CI->app('lexic');
  $this->print_list();//вывод
 }

 private function print_list(){//вывод всей цепи
  if($this->conf['public']!=='on'){return FALSE;}
  $home=isset($this->conf['home'])&&$this->conf['home']=='on'?'<li><a href="/" class="bc_home">'.$this->lexic['breadcrumbs']['home'].'</a>'.PHP_EOL:'';//главная в цепи
  $this->list='<ul id="breadcrumbs" class="noprint">'.PHP_EOL.$home;//начало цепи+главная
  if(@$this->data['section']){$this->get_sub_sections_list($this->data['section']);}//етсть раздел
  echo '<!--####### Breadcrumbs #######-->'.PHP_EOL;
  echo $this->list.'<li class="bc_end">'.$this->data['title'].PHP_EOL.'</ul>'.PHP_EOL;//вывод всей цепи
 }

 private function get_sub_sections_list($id){//дополнить лист цепочкой подразделов
  //$id-id родительского раздела в цепи
  $q=$this->get_data('sections',$id);
  if(!empty($q)){//такой id есть
   if($q['section']){$this->get_sub_sections_list($q['section']);}//есть родитель - рекурсия;
   $this->list.='<li><a href="/section/'.$q['id'].'">'.$q['title'].'</a>'.PHP_EOL;
  }
 }

 private function get_data($table,$id){//получить материал
  //$table-таблица материала в БД
  //$id-id материала в БД
  $this->CI->db->where(['public'=>'on','id'=>$id]);
  $this->CI->db->select('title,id,section');
  $q=$this->CI->db->get($table)->result_array();
  return isset($q[0])?$q[0]:[];
 }

}