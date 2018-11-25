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
  $this->_is_login();
  //////////////////задаем значение количества вывода полей из БД на страницу
  if(!$this->session->pag_per_page&&!$this->input->get('pag_per_page')){//значение не передано и в сессии его еще нет
   $this->session->set_userdata('pag_per_page','100');//запись в сессию сколько выводить полей по умолчанию
  }elseif($this->input->get('pag_per_page')){//передано значение
   $this->session->set_userdata('pag_per_page',$this->input->get('pag_per_page'));//запись в сессию переданое значение
  }
 }

///////////////////////////////////
//приватные методы
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

 function _gen_pass($lenght=10){//генерирует пароль длинной $lenght
  $alphabet=range('a','z');
  $up_alphabet=range('A','Z');
  $digits=range('1','9');
  $spech=array('~','@','#','$','[',']','_','-');
  $full_array=array_merge($alphabet,$up_alphabet,$digits,$spech);
  $password='';
  for($i=0;$i<$lenght;$i++){
   $entrie=array_rand($full_array);
   $password.=$full_array[$entrie];
  }
  return $password;
 }

 function _is_login(){//если в сессии есть админ или модератор и пароли совпали с БД — формирую массив conf
  foreach($this->back_basic_model->get_back_users() as $v){
   if($v['status']==='administrator'&&$v['password'].$v['login']===$this->session->administrator){
    $data=$this->back_basic_model->my_config_data();
    $data['conf_status']='administrator';
    $data['conf_admin_mail']=$this->_get_admin_param('email');
    $this->conf=$data;
    return TRUE;
   }elseif($v['status']==='moderator'&&$v['password'].$v['login']===$this->session->moderator){
    $data=$this->back_basic_model->my_config_data();
    $data['conf_status']='moderator';
    $data['conf_admin_mail']=$this->_get_admin_param('email');
    $this->conf=$data;
    return TRUE;
   }
  }
  return FALSE;//если не admin||moderator
 }

 function login(){
  if($this->input->post('login')&&$this->input->post('password')){//если пришли данные
   $input=array_map('trim',$this->input->post());//убираем пробелы в начале и в конце
   $l=$input['login'];
   $p=$input['password'];
   foreach ($this->back_basic_model->get_back_users() as $v){
    if(password_verify($l,$v['login'])&&password_verify($p,$v['password'])){
     $this->session->set_userdata($v['status'], $v['password'].$v['login']);//стартует сессия
     $this->back_basic_model->edit_back_user($v['id'],array('last_login_date'=>date('Y-m-d H:i:s'),'ip'=>$this->input->server('REMOTE_ADDR')));//фиксирую дату авторизации, ip
     redirect('admin');
    }
   }
   redirect('admin');
  }else{//если не пришли данные
   $this->load->view('back/login_view');
  }
 }

 function logout(){//выход из админки к форме авторизации с уничтожением сессии пользователя
  $this->_is_login()?TRUE:redirect('admin/login');
  $this->session->sess_destroy();
  redirect('admin/login');
 }

 function sand_pass(){//если забыл — проверяем в БД email и отправляем на него новые логин и пароль, старые перезаписываем
  $resp=array();//массив для возврата в json
  $count=0;//счетчик сообщений
  $p=array_map('trim',$this->input->post());//пост данные
  $mail=$p['send_pass_mail'];//поле email
  if($p['fuck_bot']!==''){$resp['status']='bot';exit(json_encode($resp));}//если бот
  if(!$mail){$resp['status']='nomail';exit(json_encode($resp));}//если не передан email
  $q=$this->back_basic_model->get_back_users($mail);//выборка
  if(!$q){$resp['status']='nomail';exit(json_encode($resp));}//если выборка пуста
  //////////////////////подготовка к рассылке
  $domen=str_replace('www.','',$_SERVER['HTTP_HOST']);//домен
  $site_name=$this->back_basic_model->get_val($this->_prefix().'my_config','name','conf_site_name','value');//имя сайта
  $this->load->library('email');//загрузка библиотеки
  foreach($q as $v){//проход по выборке
   $login=(strstr($mail,'@',TRUE))?strstr($mail,'@',TRUE):$this->_gen_pass(8);//имя пользователя из email или генерим как пароль
   $pass=$this->_gen_pass();//генерим новый пароль
   $data['login']=password_hash($login,PASSWORD_BCRYPT);//шифруем логин для БД
   $data['password']=password_hash($pass,PASSWORD_BCRYPT);//шифруем пароль для БД
   $data['last_mod_date']=date('Y-m-d H:i:s');
   $this->back_basic_model->edit_back_user($v['id'],$data);//перезаписываем данные пользователя
   //отправляем новые данные пользователю
   $msg='
<html><head><title>Пароли к '.$domen.'</title>
</head><body>
<h2>Здравствуйте!</h2>
<p>'.date('Y-m-d H:i:s').' Вам отосланы новые логин и пароль для авторизации на сайте '.$domen.'.<br>
Вы можете использовать их для <a href="'.base_url('admin/login').'" target="_blank">входа в админку сайта</a>. Старые логин и пароль были перезаписаны с целью безопасности. Ваш статус в системе — '.$v['status'].'.</p>
<p>
Логин: '.$login.'<br>
Пароль: '.$pass.'<br>
</p>
<hr>
<b> Внимание! </b>— Эта информация конфиденциальна и отправлена только Вам! Не храните ее в месте доступном для посторонних. Сообщение генерировано системой. Не отвечайте на него.
</body></html>';
   $this->email->from('Robot@'.$domen,$site_name);//от кого
   $this->email->to($mail);//кому
   $this->email->subject('Пароли к '.$domen);//тема
   $this->email->message($msg);
   $this->email->send()?$count++:FALSE;
  }
  $resp['status']='ok';
  $resp['html']='На указанный e-mail «'.$mail.'» отослано сообщений: '.$count.'<br>Сообщения могут быть помещены в «спам»';
  exit(json_encode($resp));
 }

 function del(){//удаление из таблицы $tabl по $id аяксом
  if($this->_is_login()){//если админ, модератор — логика
   $post=$this->input->post();
   $this->back_basic_model->del($post['tab'],$post['id']);
  }
  echo '';
 }

 function check_title(){//проверка на уникальность title в таблице БД
  if($this->_is_login()){//если админ, модератор — логика
   $p=$this->input->post();
   $this->back_basic_model->check_title($p['title'],$p['id'],$p['tab'])?exit('found'):exit('ok');
  }
 }

 function toggle_public(){//опубликовать\не опубликовывать аяксом (данные приходят аяксом)
  $this->_is_login()?TRUE:redirect('admin/login');
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
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['conf_title']='Генератор карты сайта';
  $this->_viewer('back/sitemap/sitemap_view',$data);
 }

 function set_sitemap_config(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $conf=json_encode(array_map('trim',$this->input->post()));//убираю пробелы в начале и в конце
  $this->db->where('name','conf_sitemap')->update($this->_prefix().'my_config',array('value'=>$conf));//записываю конфигурацию
  $this->sitemap_generator();//обновить карту сайта
  redirect('admin/sitemap');
 }

 function sitemap_generator(){
  $this->_is_login()?TRUE:redirect('admin/login');
  if(is_writable(getcwd().'/sitemap.xml')){//если sitemap.xml доступен для записи
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
  }else{//если sitemap.xml не доступен для записи
   return FALSE;
  }
 }

///////////////////////////////////
//на страницу конфигурации
///////////////////////////////////

 function index(){
  $this->_is_login()?TRUE:redirect('admin/login');
  $data=$this->conf;
  $data['moderators']=$this->db->where('status','moderator')->get($this->_prefix().'back_users')->result_array();
  $data['conf_title']='Конфигурация';
  $this->_viewer('back/set_my_config_view',$data);
 }

}
