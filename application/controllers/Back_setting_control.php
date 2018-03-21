<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

class Back_setting_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_setting_model');
 }

 function _check_back_user($login,$pass,$mail=FALSE,$id=FALSE){//проверка на уникальность
  $q=$this->back_basic_model->get_back_users();//выборка пользователей админки
  if(!$q){return TRUE;}//нет пользователей
  foreach($q as $v){
   if($v['login']===crypt($login,$v['salt'])&&$v['password']===crypt($pass,$v['salt'])){return FALSE;}//логин и пароль не уникальны
   if($mail&&$v['email']===$mail&&$v['status']==='moderator'){//модератор с таким email уже есть
    if(!$id||$id!==$v['id']){return FALSE;}//это не текущий модератор (который сейчас редактируется)
   }
  }
  return TRUE;//уникальный
 }

 function edit_administrator(){//если изменяем администратора
  $this->_is_login()?TRUE:redirect('admin/login');
  $p=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $this->_check_back_user($p['login'],$p['password'])?TRUE:exit('nounq');//проверка на уникальность логина и пароля
  $p['last_mod_date']=date('Y-m-d H:i:s');
  //если пароль и логин не заполнены или не заполнен хоть один из них - используем старую соль, иначе - генерим новую
  $p['salt']=(($p['login']===''&&$p['password']==='')||($p['login']===''||$p['password']===''))?$this->_get_admin_param('salt'):$this->_gen_salt();
  //если логин не заполнен используем старый, иначе - генерим новый
  $p['login']=($p['login']==='')?$this->_get_admin_param('login'):crypt($p['login'],$p['salt']);
  //если пароль не заполнен используем старый, иначе - генерим новый
  $p['password']=($p['password']==='')?$this->_get_admin_param('password'):crypt($p['password'],$p['salt']);
  //перезаписываю администратора
  $this->back_basic_model->edit_back_user($this->_get_admin_param('id'),$p)?TRUE:exit('error');
  //перезаписываю сессию чтобы не выбрасывало с админки
  $this->session->administrator?$this->session->set_userdata('administrator',$p['password'].$p['login']):TRUE;
  exit('ok');
 }

 function add_moderator(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $p=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $this->_check_back_user($p['login'],$p['password'],$p['email'])?TRUE:exit('nounq');//проверка на уникальность логина и пароля
  $p['register_date']=date('Y-m-d H:i:s');
  $p['status']='moderator';
  $p['salt']=$this->_gen_salt();
  $p['login']=crypt($p['login'],$p['salt']);
  $p['password']=crypt($p['password'],$p['salt']);
  $this->back_basic_model->add($p,$this->_prefix().'back_users')?exit('ok'):exit('error');
 }

 function edit_moderator($id){//если изменяем модератора
  $this->_is_login()?TRUE:redirect('admin/login');
  $p=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $this->_check_back_user($p['login'],$p['password'],$p['email'],$id)?TRUE:exit('nounq');//проверка на уникальность логина и пароля
  $p['last_mod_date']=date('Y-m-d H:i:s');
  //если пароль и логин не заполнены или не заполнен хоть один из них - используем старую соль, иначе - генерим новую
  $p['salt']=(($p['login']===''&&$p['password']==='')||($p['login']===''||$p['password']===''))?$this->_get_moderator_param($id,'salt'):$this->_gen_salt();
  //если логин не заполнен используем старый, иначе - генерим новый
  $p['login']=($p['login']==='')?$this->_get_moderator_param($id,'login'):crypt($p['login'],$p['salt']);
  //если пароль не заполнен используем старый, иначе - генерим новый
  $p['password']=($p['password']==='')?$this->_get_moderator_param($id,'password'):crypt($p['password'],$p['salt']);
  //перезаписываю модератора
  $this->back_basic_model->edit_back_user($id,$p)?exit('ok'):exit('error');
 }

 function del_moderator(){//удалить модератора
  $this->_is_login()?TRUE:redirect('admin/login');
  //сколько модераторов в системе, если один - не удалять
  $this->db->where('status','moderator')->from($this->_prefix().'back_users')->count_all_results()===1?exit('last'):TRUE;
  $id=$this->input->post('id');
  $this->back_basic_model->del($this->_prefix().'back_users',$id)?exit('ok'):exit('error');
 }

 function set_my_config(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $conf=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $this->back_setting_model->set_my_config($conf);//записываем конфигурацию
  redirect('admin');
 }

}