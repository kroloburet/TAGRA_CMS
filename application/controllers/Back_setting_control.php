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
   if(password_verify($login,$v['login'])&&password_verify($pass,$v['password'])){return FALSE;}//логин и пароль не уникальны
   if($mail&&$v['email']===$mail&&$v['status']==='moderator'){//модератор с таким email уже есть
    if(!$id||$id!==$v['id']){return FALSE;}//это не текущий модератор (который сейчас редактируется)
   }
  }
  return TRUE;//уникальный
 }

 function edit_administrator(){//изменить данные администратора
  $p=array_map('trim',$this->input->post());
  !filter_var($p['email'],FILTER_VALIDATE_EMAIL)?exit(json_encode(['status'=>'nomail'],JSON_FORCE_OBJECT)):TRUE;//проверка email
  $this->_check_back_user($p['login'],$p['password'])?TRUE:exit(json_encode(['status'=>'nounq'],JSON_FORCE_OBJECT));//проверка на уникальность логина и пароля
  $p['last_mod_date']=date('Y-m-d H:i:s');
  //если логин не заполнен использовать старый, иначе - шифровать новый
  $p['login']=($p['login']==='')?$this->_get_admin_param('login'):password_hash($p['login'],PASSWORD_BCRYPT);
  //если пароль не заполнен использовать старый, иначе - шифровать новый
  $p['password']=($p['password']==='')?$this->_get_admin_param('password'):password_hash($p['password'],PASSWORD_BCRYPT);
  //перезаписать данные
  $this->back_basic_model->edit_back_user($this->_get_admin_param('id'),$p)?TRUE:exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));
  //перезаписать сессию чтобы не выбрасывало из админки
  $this->session->administrator?$this->session->set_userdata('administrator',$p['password'].$p['login']):TRUE;
  //вернуть ok и json запись админа
  $q=$this->db->where('status','administrator')->get('back_users')->result_array();
  if(empty($q)){$a='{}';}else{foreach($q as $v){$a[$v['id']]=$v;}}
  exit(json_encode(['status'=>'ok','opt'=>$a],JSON_FORCE_OBJECT));
 }

 function add_moderator(){
  $p=array_map('trim',$this->input->post());
  !filter_var($p['email'],FILTER_VALIDATE_EMAIL)?exit(json_encode(['status'=>'nomail'],JSON_FORCE_OBJECT)):TRUE;//проверка email
  $this->_check_back_user($p['login'],$p['password'],$p['email'])?TRUE:exit(json_encode(['status'=>'nounq'],JSON_FORCE_OBJECT));//проверка на уникальность логина и пароля
  $p['register_date']=date('Y-m-d H:i:s');
  $p['status']='moderator';
  $p['login']=password_hash($p['login'],PASSWORD_BCRYPT);
  $p['password']=password_hash($p['password'],PASSWORD_BCRYPT);
  if($this->back_basic_model->add($p,'back_users')){//добавлен в базу
   $q=$this->db->where('status','moderator')->get('back_users')->result_array();
   if(empty($q)){$m='{}';}else{foreach($q as $v){$m[$v['id']]=$v;}}
   exit(json_encode(['status'=>'ok','opt'=>$m],JSON_FORCE_OBJECT));
  }else{//не добавлен в базу
   exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));
  }
 }

 function edit_moderator($id){
  $p=array_map('trim',$this->input->post());
  !filter_var($p['email'],FILTER_VALIDATE_EMAIL)?exit(json_encode(['status'=>'nomail'],JSON_FORCE_OBJECT)):TRUE;//проверка email
  $this->_check_back_user($p['login'],$p['password'],$p['email'],$id)?TRUE:exit(json_encode(['status'=>'nounq'],JSON_FORCE_OBJECT));//проверка на уникальность логина и пароля
  $p['last_mod_date']=date('Y-m-d H:i:s');
  //если логин не заполнен использовать старый, иначе - шифровать новый
  if($p['login']!==''){$p['login']=password_hash($p['login'],PASSWORD_BCRYPT);}else{unset($p['login']);}
  //если пароль не заполнен использовать старый, иначе - шифровать новый
  if($p['password']!==''){$p['password']=password_hash($p['password'],PASSWORD_BCRYPT);}else{unset($p['password']);}
  if($this->back_basic_model->edit_back_user($id,$p)){//перезаписан
   $q=$this->db->where('status','moderator')->get('back_users')->result_array();
   if(empty($q)){$m='{}';}else{foreach($q as $v){$m[$v['id']]=$v;}}
   exit(json_encode(['status'=>'ok','opt'=>$m],JSON_FORCE_OBJECT));
  }else{//не перезаписан
   exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));
  }
 }

 function del_moderator(){//удалить модератора
  //сколько модераторов в системе, если один - не удалять
  $this->db->where('status','moderator')->from('back_users')->count_all_results()===1?exit('last'):TRUE;
  $id=$this->input->post('id');
  $this->back_basic_model->del('back_users',$id)?exit('ok'):exit('error');
 }

 function set_config(){
  $conf=$this->_format_data($this->input->post(),TRUE,'json');
  $this->back_setting_model->set_config($conf);
  redirect('admin');
 }

}