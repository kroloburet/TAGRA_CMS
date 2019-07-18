<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//обработка запроса отправки нового логина и пароля
///////////////////////////////////

class Change_login extends CI_Controller{
 function __construct(){
  parent::__construct();
  $this->load->model('back_basic_model');
 }

 function gen_pass($lenght=10){//генерирует пароль длинной $lenght
  $alphabet=range('a','z');
  $up_alphabet=range('A','Z');
  $digits=range('1','9');
  $spech=['~','@','#','$','[',']','_','-'];
  $full_array=array_merge($alphabet,$up_alphabet,$digits,$spech);
  $password='';
  for($i=0;$i<$lenght;$i++){
   $entrie=array_rand($full_array);
   $password.=$full_array[$entrie];
  }
  return $password;
 }

 function index(){//проверить в БД email и отправить на него новые логин и пароль, старые перезаписать
  $resp=[];//массив для возврата в json
  $count=0;//счетчик сообщений
  $p=array_map('trim',$this->input->post());//данные
  $mail=filter_var($p['send_pass_mail'],FILTER_VALIDATE_EMAIL);//поле email
  if($p['fuck_bot']!==''){$resp['status']='bot';exit(json_encode($resp,JSON_FORCE_OBJECT));}//если бот
  if(!$mail){$resp['status']='nomail';exit(json_encode($resp,JSON_FORCE_OBJECT));}//если не передан email
  $q=$this->back_basic_model->get_back_users($mail);//выборка
  if(!$q){$resp['status']='nomail';exit(json_encode($resp,JSON_FORCE_OBJECT));}//если выборка пуста
  //////////////////////подготовка к рассылке
  $domen=str_replace('www.','',$this->input->server('HTTP_HOST'));//домен
  $site_name=$this->back_basic_model->get_val('config','name','site_name','value');//имя сайта
  $this->load->library('email');//загрузка библиотеки
  foreach($q as $v){//проход по выборке
   if($v['status']==='moderator'&&$v['access']!=='on'){//это запрещенный модератор
    if(count($q)<2){$resp['status']='noaccess';exit(json_encode($resp,JSON_FORCE_OBJECT));}//кроме него в выборке никого нет
    continue;//пропустить итерацию
   }
   $login=(strstr($mail,'@',TRUE))?strstr($mail,'@',TRUE):$this->gen_pass(8);//имя пользователя email или генерить
   $pass=$this->gen_pass();//генерить новый пароль
   $data['login']=password_hash($login,PASSWORD_BCRYPT);//шифровать логин для БД
   $data['password']=password_hash($pass,PASSWORD_BCRYPT);//шифровать пароль для БД
   $data['last_mod_date']=date('Y-m-d H:i:s');
   $this->back_basic_model->edit_back_user($v['id'],$data);//перезаписать данные пользователя
   //отправить новые данные пользователю
   $msg='
<html><head><title>Пароли к '.$domen.'</title>
</head><body>
<h2>Здравствуйте!</h2>
<p>'.date('Y-m-d H:i:s').' Вам отосланы новые логин и пароль для авторизации на сайте '.$domen.'.<br>
Вы можете использовать их для <a href="'.base_url('admin').'" target="_blank">входа в админку сайта</a>. Старые логин и пароль были перезаписаны с целью безопасности. Ваш статус в системе — '.$v['status'].'.</p>
<p>
Логин: '.$login.'<br>
Пароль: '.$pass.'<br>
</p>
<hr>
<b>Внимание!</b> — Эта информация конфиденциальна и отправлена только Вам! Не храните ее в месте доступном для посторонних. Сообщение сгенерировано системой. Не отвечайте на него.
</body></html>';
   $this->email->from('Robot@'.$domen,$site_name);
   $this->email->to($mail);
   $this->email->subject('Пароли к '.$domen);
   $this->email->message($msg);
   $this->email->send()?$count++:FALSE;
  }
  $resp['status']='ok';
  $resp['html']='На указанный e-mail «'.$mail.'» отослано сообщений: '.$count.'<br>Сообщения могут быть помещены в «спам»';
  exit(json_encode($resp,JSON_FORCE_OBJECT));
 }

}
