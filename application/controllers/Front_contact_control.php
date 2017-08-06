<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Front_contact_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_contact_model');
 }

 function contact(){
  ($this->conf['conf_site_access']==='off')?redirect('plug.html'):TRUE;//если сайт закрыт в конфигурации - напрвляю на страницу-заглушку
  $data=array_merge($this->conf,$this->front_contact_model->get_contact_page());//соединение массивов
  $this->_viewer('front/contact_view',$data,'off');
 }

 function send_mail(){//отправка сообщения аяксом
  $this->input->post('fuck_bot')!==''?exit('bot'):TRUE;
  $p=array_map('strip_tags',array_map('trim',$this->input->post()));
  $domen=str_replace('www.','',$_SERVER['HTTP_HOST']);
  $this->load->library('email');
  $msg='
<html><head><title>Сообщение с '.$domen.'</title>
</head><body>
<h2>Сообщение с '.$domen.'</h2>
Дата и время отправки: '.date('d.m.Y H:i:s').'<br>
Email отправителя: '.$p['mail'].'<br>
Имя отправителя: '.$p['name'].'<br>
Текст сообщения: '.$p['text'].'<br>
</body></html>';
  $this->email->from('Robot@'.$domen,$this->conf['conf_site_name']);
  $this->email->to($this->conf['conf_site_mail']);
  $this->email->subject('Сообщение с '.$domen);
  $this->email->message($msg);
  $this->email->send()?exit('ok'):exit('error');
 }

}