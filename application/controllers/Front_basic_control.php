<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Front_basic_control extends CI_Controller{
 function __construct(){
  parent::__construct();
  $this->load->model('front_basic_model');
  $this->_is_site_access();
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

///////////////////////////////////
//работа с выводом и доступом
///////////////////////////////////

 function _is_site_access(){//доступ к пользовательской части системы
  $this->app('conf.site_access')==='off'&&!$this->app('conf.back_user')?redirect('plug.html','location',302):TRUE;
 }

 function _viewer($path,$data){
  if(isset($data['lang'])&&$this->app('conf.user_lang')!==$data['lang']){//установить языком пользователя язык материала
   $this->input->set_cookie('user_lang',$data['lang'],0);
   $this->set_app([
   'conf.user_lang'=>$data['lang'],
   'lexic'=>$this->lang->load('front_template',$data['lang'],TRUE)
   ]);
  }
  $this->set_app([
  'data'=>$data,
  'data.front_menu_list'=>$this->front_basic_model->get_menu()
  ]);
  $this->load->view('front/blocks/header_view',$this->app());//header шаблона
  $this->load->view($path,$this->app());//тело шаблона
  $this->load->view('front/blocks/footer_view',$this->app());//footer шаблона
 }

///////////////////////////////////
//работа с языком
///////////////////////////////////

 function change_lang($tag,$url='/'){
  if(!in_array($tag,array_column($this->app('conf.langs'),'tag'))){redirect('404_override');return FALSE;}
  $this->input->set_cookie('user_lang',$tag,0);
  redirect($url);
 }

}