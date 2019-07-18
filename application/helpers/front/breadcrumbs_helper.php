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

 private function get_sub_sections_list($alias){//дополнить лист цепочкой подразделов
  //$alias-алиас родительского раздела в цепи
  $q=$this->get_data('sections',$alias);
  if(!empty($q)){//если такой алиас есть
   if($q['section']){$this->get_sub_sections_list($q['section']);}//если есть родитель - рекурсия;
   $this->list.='<li><a href="/section/'.$q['alias'].'">'.$q['title'].'</a>'.PHP_EOL;
  }
 }

 private function get_data($table,$alias){//получить материал
  //$table-таблица материала в БД
  //$alias-алиас материала в БД
  $this->CI->db->where(['public'=>'on','alias'=>$alias]);
  $this->CI->db->select('id,alias,title,section');
  $q=$this->CI->db->get($table)->result_array();
  return isset($q[0])?$q[0]:[];
 }

}
