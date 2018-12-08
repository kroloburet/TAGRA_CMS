<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Back_basic_control extends CI_Controller {
 protected $conf=array();//массив, куда будут записана конфигурация сайта и другие данные в случае успешной авторизации
 function __construct(){
  parent::__construct();
  //////////////////подгружаем скрипты для работы класса
  $this->load->library('session');
  $this->load->model('back_basic_model');
  //////////////////проверка авторизации при обращении к контроллеру
  $this->login();
  //////////////////задаем значение количества вывода полей из БД на страницу
  if(!$this->session->pag_per_page&&!$this->input->get('pag_per_page')){//значение не передано и в сессии его еще нет
   $this->session->set_userdata('pag_per_page','100');//запись в сессию сколько выводить полей по умолчанию
  }elseif($this->input->get('pag_per_page')){//передано значение
   $this->session->set_userdata('pag_per_page',$this->input->get('pag_per_page'));//запись в сессию переданое значение
  }
 }

///////////////////////////////////
//вход\выход
///////////////////////////////////

 function login(){
  $d=array();
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
     $this->back_basic_model->edit_back_user($v['id'],array('last_login_date'=>date('Y-m-d H:i:s'),'ip'=>$this->input->server('REMOTE_ADDR')));//фиксирую дату авторизации, ip
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
     $data['conf_status']=$v['status']==='administrator'?'administrator':'moderator';
     $data['conf_admin_mail']=$this->_get_admin_param('email');
     $this->conf=$data;
     return TRUE;
    }
   }
  }
  //////////////////////////все не то! прекратить работу контроллера и вывести форму входа
  exit($this->load->view('back/login_view',$d,TRUE));
 }

 function logout(){
  $this->session->unset_userdata(array('administrator','moderator'));
  redirect('admin');
 }

///////////////////////////////////
//вспомагательные методы
///////////////////////////////////

 function _get_admin_param($param){
  $q=$this->db->where('status','administrator')->get($this->_prefix().'back_users')->result_array();
  return $q[0][$param];
 }

 function _get_moderator_param($id_mail,$param){
 //$id_mail='basic_email'||'id'
  $mail=(!ctype_digit($id_mail))?$id_mail:FALSE;
  $id=(ctype_digit($id_mail))?$id_mail:FALSE;
  //mail или id
  if($mail){
   $this->db->where(array('email'=>$mail));
  }elseif($id){
   $this->db->where(array('id'=>$id));
  }else{return FALSE;}
  $q=$this->db->get($this->_prefix().'back_users')->result_array();
  //получение и возврат данных
  if($q){
   return $q[0][$param];
  }else{return FALSE;}
 }

 function _prefix(){//получение префикса таблиц базы данных из конфигурационного файла
  return $this->config->item('db_tabl_prefix');
 }

 function _format_data($data=array(),$trim=TRUE){//форматирование post\get данных перед записью в базу
  //$data массив post\get
  //$trim использовать ли функцию trim() для значений
  if(!is_array($data)||empty($data)){return FALSE;}
  foreach($data as $k=>$v){
   if(is_array($v)){
    $result[$k]=implode(',',$v);
   }else{
    $trim?$result[$k]=trim($v):$result[$k]=$v;
   }
  }
  return $result;
 }

 function _set_pagination($url,$total_rows){//установка постраничной навигации, возвращает массив
  $this->load->library('pagination');//подгружаю библиотеку
  //берем из сессии количество полей для отображения
  $per_page=($this->session->pag_per_page==='all')?$total_rows:$this->session->pag_per_page;
  $config['base_url']=$url;//урл после которого идет номерация станиц
  $config['total_rows']=$total_rows;//всего записей в запросе
  $config['per_page']=$per_page;//количество полей для отображения
  $config['reuse_query_string']=TRUE;//
  $config['page_query_string']=TRUE;//
  $this->pagination->initialize($config);
 }

 function _viewer($url,$data) {
  $this->load->view('back/blocks/header_view',$data);
  $this->load->view($url,$data);
  $this->load->view('back/blocks/footer_view',$data);
 }

 function del(){//удаление из таблицы $tabl по $id аяксом
  $post=$this->input->post();
  $this->back_basic_model->del($post['tab'],$post['id']);
  echo '';
 }

 function check_title(){//проверка на уникальность title в таблице БД
   $p=$this->input->post();
   $this->back_basic_model->check_title($p['title'],$p['id'],$p['tab'])?exit('found'):exit('ok');
  }

 function toggle_public(){//опубликовать\не опубликовывать аяксом (данные приходят аяксом)
  $post=$this->input->post();
  if($this->back_basic_model->toggle_public($post['id'],$post['tab'],$post['pub'])==='on'){//опубликовать\не опубликовывать
   echo '<a href="#" class="fa fa-eye green" title="Опубликовать/не опубликовывать" onclick="toggle_public(this,\''.$post['id'].'\',\''.$post['tab'].'\',\'on\');return false"></a>';//
  }elseif($this->back_basic_model->toggle_public($post['id'],$post['tab'],$post['pub'])==='off'){
   echo '<a href="#" class="fa fa-eye-slash red" title="Опубликовать/не опубликовывать" onclick="toggle_public(this,\''.$post['id'].'\',\''.$post['tab'].'\',\'off\');return false"></a>';//
  }
  $this->conf['conf_sitemap']['generate']==='auto'?$this->sitemap_generator():FALSE;//если карта сайта должна генерироваться автоматически
 }

///////////////////////////////////
//работа с картой сайта
///////////////////////////////////

 function sitemap(){
  $data=$this->conf;
  $data['conf_title']='Генератор карты сайта';
  $this->_viewer('back/sitemap/sitemap_view',$data);
 }

 function set_sitemap_config(){
  $conf=json_encode(array_map('trim',$this->input->post()));//убираю пробелы в начале и в конце
  $this->db->where('name','conf_sitemap')->update($this->_prefix().'config',array('value'=>$conf));//записываю конфигурацию
  $this->sitemap_generator();//обновить карту сайта
  redirect('admin/sitemap');
 }

 function sitemap_generator(){
  if(!is_writable(getcwd().'/sitemap.xml')){return FALSE;}//если sitemap.xml не доступен для записи
  //инициализация переменных
  $pages=$sections=$gallerys='';
  $where=array('robots !='=>'none','robots !='=>'noindex');//только индексируемые
  $this->conf['conf_sitemap']['allowed']==='public'?$where['public']='on':FALSE;//если включать только опубликованные материалы
  $select='alias';//только нужные поля
  $pgs=$this->db->where($where)->select($select)->get($this->_prefix().'pages')->result_array();
  $sctns=$this->db->where($where)->select($select)->get($this->_prefix().'sections')->result_array();
  $glrs=$this->db->where($where)->select($select)->get($this->_prefix().'gallerys')->result_array();
  //формирую URL XML sitemap
  if(!empty($pgs)){//если есть страницы
   foreach($pgs as $i){$pages.='<url><loc>'.base_url($i['alias']).'</loc></url>'.PHP_EOL;}
  }
  if(!empty($sctns)){//если есть разделы
   foreach($sctns as $i){$sections.='<url><loc>'.base_url('section/'.$i['alias']).'</loc></url>'.PHP_EOL;}
  }
  if(!empty($glrs)){//если есть галереи
   foreach($glrs as $i){$gallerys.='<url><loc>'.base_url('gallery/'.$i['alias']).'</loc></url>'.PHP_EOL;}
  }
  //формирую sitemap.xml
  $f=fopen(getcwd().'/sitemap.xml','a');//открываем файл в режиме чтения
  ftruncate($f,0);//очищаем файл sitemap.xml
  $sitemap='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
          .'<!-- Generator: Tagra CMS; Developer: Sergey Nizhnik kroloburet@gmail.com -->'.PHP_EOL
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
  fwrite($f,$sitemap);//записываю sitemap.xml
  fclose($f);//закрываю файл
  return TRUE;
 }

///////////////////////////////////
//на страницу конфигурации
///////////////////////////////////

 function index(){
  $data=$this->conf;
  $data['conf_title']='Конфигурация';
  $this->_viewer('back/config_view',$data);
 }

}
