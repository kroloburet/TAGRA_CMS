<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с страницей "Контакты"
///////////////////////////////////

class Front_contact_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_contact_model');
 }

 function contact(){
  $data=array_merge($this->conf,$this->front_contact_model->get_contact_page());
  $this->_viewer('front/contact_view',$data);
 }

 function send_mail(){
  !$this->input->post()?exit('error'):TRUE;
  $this->input->post('fuck_bot')!==''?exit('bot'):TRUE;
  $p=array_map('trim',$this->input->post());
  !filter_var($p['mail'],FILTER_VALIDATE_EMAIL)?exit('nomail'):TRUE;
  $domen=str_replace('www.','',$this->input->server('HTTP_HOST'));
  $this->load->library('email');
  $msg='
<html><head><title>Сообщение с '.$domen.'</title>
</head><body>
<h2>Сообщение с '.$domen.'</h2>
Дата и время отправки: '.date('d.m.Y H:i:s').'<br>
Email отправителя: '.$p['mail'].'<br>
Имя отправителя: '.strip_tags($p['name']).'<br>
Текст сообщения: '.strip_tags($p['text']).'<br>
</body></html>';
  $this->email->from('Robot@'.$domen,$this->conf['conf_site_name']);
  $this->email->to($this->conf['conf_site_mail']);
  $this->email->subject('Сообщение с '.$domen);
  $this->email->message($msg);
  $this->email->send()?exit('ok'):exit('error');
 }

}