<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с конфигурацией
///////////////////////////////////

class Back_setting_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_setting_model');
 }
 
 function set_user(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  //если изменяем администратора
  if(isset($data['admin_login'],$data['admin_pass'],$data['admin_mail'])){
   $a_l=$data['admin_login'];
   $a_p=$data['admin_pass'];
   $a_m=$data['admin_mail'];
   //если пароль и логин не заполнены или не заполнен хоть один из них - используем старую соль, иначе - генерим новую
   $admin['salt']=(($a_l==''&&$a_p=='')||($a_l==''||$a_p==''))?$this->_get_user_param('administrator','salt'):$this->_gen_salt();
   //если логин не заполнен используем старый, иначе - генерим новый
   $admin['login']=($a_l=='')?$this->_get_user_param('administrator','login'):crypt($a_l,$admin['salt']);
   //если пароль не заполнен используем старый, иначе - генерим новый
   $admin['password']=($a_p=='')?$this->_get_user_param('administrator','password'):crypt($a_p,$admin['salt']);
   //записываем email
   $admin['email']=$a_m;
   //перезаписываю администратора
   $this->back_basic_model->set_user($this->_get_user_param('administrator','id'),$admin);
   //перезаписываю сессию чтобы не выбрасывало с админки
   ($this->session->administrator)?$this->session->set_userdata('administrator',$admin['password'].$admin['login']):TRUE;
  }
  //если изменяем модератора
  if(isset($data['moder_login'],$data['moder_pass'],$data['moder_mail'])){
   $m_l=$data['moder_login'];
   $m_p=$data['moder_pass'];
   $m_m=$data['moder_mail'];
   //если пароль и логин не заполнены или не заполнен хоть один из них - используем старую соль, иначе - генерим новую
   $moder['salt']=(($m_l==''&&$m_p=='')||($m_l==''||$m_p==''))?$this->_get_user_param('moderator','salt'):$this->_gen_salt();
   //если логин не заполнен используем старый, иначе - генерим новый
   $moder['login']=($m_l=='')?$this->_get_user_param('moderator','login'):crypt($m_l,$moder['salt']);
   //если пароль не заполнен используем старый, иначе - генерим новый
   $moder['password']=($m_p=='')?$this->_get_user_param('moderator','password'):crypt($m_p,$moder['salt']);
   //записываем email
   $moder['email']=$m_m;
   //перезаписываю модератора
   $this->back_basic_model->set_user($this->_get_user_param('moderator','id'),$moder);
  }
  redirect('admin');
 }
         
 function set_my_config(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $conf=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
  $this->back_setting_model->set_my_config($conf);//записываем конфигурацию
  redirect('admin');
 }
 
}