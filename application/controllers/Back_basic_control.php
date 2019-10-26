<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Back_basic_control extends CI_Controller{
 function __construct(){
  parent::__construct();
  $this->load->library('session');
  $this->load->model('back_basic_model');
  //////////////////проверка авторизации при обращении к контроллеру
  $this->login();
  //////////////////задать значения количества вывода полей из БД на страницу
  if(!$this->session->pag_per_page&&!$this->input->get('pag_per_page')){//значение не передано и в сессии его еще нет
   $this->session->set_userdata('pag_per_page','100');//запись в сессию сколько выводить полей по умолчанию
  }elseif($this->input->get('pag_per_page')){//передано значение
   $this->session->set_userdata('pag_per_page',$this->input->get('pag_per_page'));//запись в сессию переданое значение
  }
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
//вход\выход
///////////////////////////////////

 function login(){
  $d=[];
  $q=$this->back_basic_model->get_back_users();
  //////////////////////////пришли данные для входа
  if($this->input->post('lgn') && $this->input->post('pswd')){
   $p=array_map('trim',$this->input->post());
   foreach($q as $v){//сверить данные с данными каждого пользователя
    if(password_verify($p['lgn'],$v['login']) && password_verify($p['pswd'],$v['password'])){//логин и пароль совпали
     if($v['status']==='moderator' && $v['access']!=='on'){//если запрещенный модератор
      $d['msg']='<p class="notific_r mini full">Упс! Администратор запретил вам вход и все действия от имени модератора.</p>';
      break;
     }//запретов нет - пропустить
     $this->session->set_userdata($v['status'], $v['password'].$v['login']);//стартует сессия
     $this->back_basic_model->edit_back_user($v['id'],['last_login_date'=>date('Y-m-d H:i:s'),'ip'=>$this->input->server('REMOTE_ADDR')]);//фиксирую дату авторизации, ip
     break;
    }//данные неверны
    $d['msg']='<p class="notific_r mini full">Нет пользователя с такими данными!</p>';
   }
  }
  //////////////////////////админ или модератор залогинен
  if(isset($this->session->administrator) || isset($this->session->moderator)){
   foreach($q as $v){//сверить данные с данными каждого пользователя
    if($this->session->administrator===$v['password'].$v['login'] || $this->session->moderator===$v['password'].$v['login']){
     if($v['status']==='moderator' && $v['access']!=='on'){//если запрещенный модератор
      $d['msg']='<p class="notific_r mini full">Упс! Администратор запретил вам вход и все действия от имени модератора.</p>';
      break;
     }
     $data=$this->back_basic_model->get_config();
     $data['status']=$v['status']==='administrator'?'administrator':'moderator';
     $data['admin_mail']=$this->_get_admin_param('email');
     $data['moderator_mail']=implode(',',array_column(array_filter($q,function($i){return $i['status']=='moderator'&&$i['access']=='on';}),'email'));
     $data['langs']=$this->back_basic_model->get_langs();
     foreach($data['langs'] as $i){if($i['def']=='on'){$data['lang_def']=$i;break;}}
     $this->set_app(['conf'=>$data]);//записать конфигурацию
     return TRUE;
    }
   }
  }
  //////////////////////////все не то! прекратить работу контроллера и вывести форму входа
  exit($this->load->view('back/login_view',$d,TRUE));
 }

 function logout(){
  $this->session->unset_userdata(['administrator','moderator']);
  redirect('admin');
 }

///////////////////////////////////
//вспомагательные методы
///////////////////////////////////

 function _get_admin_param($param){
  $q=$this->db->where('status','administrator')->get('back_users')->result_array();
  return $q[0][$param];
 }

 function _get_moderator_param($id_mail,$param){
 //$id_mail-(строка) email или id модератора
  $mail=(!ctype_digit($id_mail))?$id_mail:FALSE;
  $id=(ctype_digit($id_mail))?$id_mail:FALSE;
  //mail или id
  if($mail){
   $this->db->where(['email'=>$mail]);
  }elseif($id){
   $this->db->where(['id'=>$id]);
  }else{return FALSE;}
  $q=$this->db->get('back_users')->result_array();
  //получение и возврат данных
  if($q){
   return $q[0][$param];
  }else{return FALSE;}
 }

 function _format_data($data=[],$trim=TRUE,$arr_to=FALSE){//форматирование post\get данных перед записью в базу
  //$data-массив post\get
  //$trim-использовать ли функцию trim() для значений
  //$arr_to-обработка вложенных массивов false или: 'comma'-значения в строку через запятую; 'json'- в строку формата json
  if(!is_array($data)||empty($data)){return FALSE;}
  foreach($data as $k=>$v){
   if(is_array($v)&&$arr_to){
    $result[$k]=$arr_to=='comma'?implode(',',$v):json_encode($v);
   }else{
    $trim?$result[$k]=trim($v):$result[$k]=$v;
   }
  }
  return $result;
 }

 function _set_pagination($url,$total_rows){//установка постраничной навигации
  $this->load->library('pagination');
  $per_page=($this->session->pag_per_page==='all')?$total_rows:$this->session->pag_per_page;//количество полей для отображения из сессии
  $config['base_url']=$url;//урл после которого идет номерация станиц
  $config['total_rows']=$total_rows;//всего записей в запросе
  $config['per_page']=$per_page;//количество полей для отображения
  $config['reuse_query_string']=TRUE;
  $config['page_query_string']=TRUE;
  $this->pagination->initialize($config);
 }

 function _viewer($path,$data){
  $this->set_app(['data'=>is_array($this->app('data'))?$this->app('data')+$data:$data]);//для использования в хелперах и view-шаблонах
  $this->load->view('back/blocks/header_view',$this->app());//header шаблон
  $this->load->view($path,$this->app());//тело шаблон
  $this->load->view('back/blocks/footer_view',$this->app());//footer шаблон
 }

 function _lang_selection($data){//выбор языка перед добавлением материала
  $langs=$this->app('conf.langs');//языки в системе
  $lang=$this->input->get('lang');//выбраный язык из get
  if(count($langs)===1){//в системе один язык
   $this->set_app(['data.lang'=>$langs[0]['tag']]);
   return FALSE;
  }elseif(isset($lang)&&in_array($lang,array_column($langs,'tag'))){//язык выбран, существует в системе
   $this->set_app(['data.lang'=>$lang]);
   return FALSE;
  }
  $this->_viewer('back/language_selection_view',$data);//язык не выбран - отдать шаблон выбора языка
  return TRUE;
 }

 function check_title(){//проверка на уникальность title в таблице БД
   $p=$this->input->post();
   $this->back_basic_model->check_title($p['title'],$p['id'],$p['tab'])?exit('found'):exit('ok');
  }

 function toggle_public(){//переключение значения публикации материала
  $p=$this->input->post();
  $res=$this->back_basic_model->toggle_public($p['id'],$p['tab']);
  $this->app('conf.sitemap.generate')==='auto'?$this->sitemap_generator():FALSE;
  echo $res;
  }

///////////////////////////////////
//работа с картой сайта
///////////////////////////////////

 function sitemap(){
  $data['view_title']='Генератор карты сайта';
  $this->_viewer('back/sitemap/sitemap_view',$data);
 }

 function set_sitemap_config(){
  $conf=json_encode(array_map('trim',$this->input->post()));
  $this->db->where('name','sitemap')->update('config',['value'=>$conf]);
  $this->sitemap_generator();//обновить карту сайта
  redirect('admin/sitemap');
 }

 function sitemap_generator(){
  if(!is_writable(getcwd().'/sitemap.xml')){return FALSE;}//sitemap.xml не доступен для записи
  $pages=$sections=$gallerys='';
  $where=['robots !='=>'none','robots !='=>'noindex'];//только индексируемые
  $this->app('conf.sitemap.allowed')==='public'?$where['public']='on':FALSE;//если включать только опубликованные материалы
  $select='id';//только нужные поля
  $pgs=$this->db->where($where)->select($select)->get('pages')->result_array();
  $sctns=$this->db->where($where)->select($select)->get('sections')->result_array();
  $glrs=$this->db->where($where)->select($select)->get('gallerys')->result_array();
  //разметка <url>
  if(!empty($pgs)){//есть страницы
   foreach($pgs as $i){$pages.='<url><loc>'.base_url('page/'.$i['id']).'</loc></url>'.PHP_EOL;}
  }
  if(!empty($sctns)){//есть разделы
   foreach($sctns as $i){$sections.='<url><loc>'.base_url('section/'.$i['id']).'</loc></url>'.PHP_EOL;}
  }
  if(!empty($glrs)){//есть галереи
   foreach($glrs as $i){$gallerys.='<url><loc>'.base_url('gallery/'.$i['id']).'</loc></url>'.PHP_EOL;}
  }
  //формировать sitemap.xml
  $f=fopen(getcwd().'/sitemap.xml','a');//открыть файл в режиме чтения
  ftruncate($f,0);//очистить файл
  $sitemap='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
          .'<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->'.PHP_EOL
          .'<!-- Generator: Tagra CMS '.$this->config->item('tagra_version').' -->'.PHP_EOL
          .'<!-- Latest update: '.date('Y-m-d H:i:s').' -->'.PHP_EOL
          .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL
          .'<!-- Home page -->'.PHP_EOL
          .'<url><loc>'.base_url().'</loc></url>'.PHP_EOL
          .'<!-- Contact page -->'.PHP_EOL
          .'<url><loc>'.base_url('contact').'</loc></url>'.PHP_EOL
          .'<!-- Pages -->'.PHP_EOL
          .$pages
          .'<!-- Sections -->'.PHP_EOL
          .$sections
          .'<!-- Gallerys -->'.PHP_EOL
          .$gallerys
          .'</urlset>'.PHP_EOL;
  fwrite($f,$sitemap);//записать sitemap.xml
  fclose($f);//закрыть файл
  return TRUE;
 }

///////////////////////////////////
//на страницу конфигурации
///////////////////////////////////

 function index(){
  $data['view_title']='Конфигурация';
  $this->_viewer('back/config_view',$data);
 }

}