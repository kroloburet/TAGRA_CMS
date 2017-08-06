<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Front_comment_control extends Front_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('front_comment_model');
 }

 function add_comment(){//отправка комментария аяксом
  $this->input->post('fuck_bot')!==''?exit('bot'):TRUE;
  $p=array_map('strip_tags',array_map('trim',$this->input->post()));
  $data=array('name'=>$p['name'],'comment'=>$this->_replace_urls($p['comment']),'url'=>$p['url'],'date'=>date('d.m.Y'),'public'=>'on');
  $mail_to=FALSE;
  if($this->conf['conf_comment_notific']!=='off'){//нужно отправлять уведомление на e-mail
   //подготовка данных для отправки уведомления
   $data['premod_code']=microtime(TRUE);//одноразовый код коммента для быстрого управления из e-mail
   $no_premod_actions='<a href="'.base_url('do/comment_action/del/'.$data['premod_code']).'" target="_blank">Удалить</a> | <a href="'.base_url('admin/comment/get_list').'" target="_blank">Управление комментариями</a>';//опции быстрого управления комментом из e-mail (без публикации)
   $premod_actions='<a href="'.base_url('do/comment_action/public/'.$data['premod_code']).'" target="_blank">Публиковать</a> | <a href="'.base_url('do/comment_action/del/'.$data['premod_code']).'" target="_blank">Удалить</a> | <a href="'.base_url('admin/comment/get_list').'" target="_blank">Управление комментариями</a>';//опции быстрого управления комментом из e-mail (с публикацией)
   //на какой e-mail администратора\модератора высылать уведомление
   switch($this->conf['conf_comment_notific']){
    //без премодерации
    case 'site_mail':
     $mail_to=$this->conf['conf_site_mail'];
     $actions=$no_premod_actions;
    break;
    case 'admin_mail':
     $mail_to=$this->conf['conf_admin_mail'];
     $actions=$no_premod_actions;
    break;
    case 'moderator_mail':
     $mail_to=$this->conf['conf_moderator_mail'];
     $actions=$no_premod_actions;
    break;
    //с премодерацией
    case 'premod_site_mail':
     $mail_to=$this->conf['conf_site_mail'];
     $data['public']='off';
     $actions=$premod_actions;
    break;
    case 'premod_admin_mail':
     $mail_to=$this->conf['conf_admin_mail'];
     $data['public']='off';
     $actions=$premod_actions;
    break;
    case 'premod_moderator_mail':
     $mail_to=$this->conf['conf_moderator_mail'];
     $data['public']='off';
     $actions=$premod_actions;
    break;
   }
  }
  $this->front_comment_model->add_comment($data)?TRUE:exit('error');//записать коммент в базу
  $mail_to?TRUE:exit('onpublic');//не уведомлять и публиковать по умолчанию
  //отправка уведомления
  $domen=str_replace('www.','',$_SERVER['HTTP_HOST']);
  $this->load->library('email');
  $this->email->subject('Новый комментарий на '.$domen);
  $msg='
<html><head><title>Новый комментарий на '.$domen.'</title>
</head><body>
<h2>Новый комментарий на '.$domen.'</h2>
Дата отправки: '.$data['date'].'<br>
Материал: <a href="'.base_url($data['url']).'" target="_blank">'.base_url($data['url']).'</a><br>
Имя комментатора: '.$data['name'].'<br>
Текст комментария: '.$data['comment'].'<br>
<hr>
'.$actions.'
</body></html>';
  $this->email->from('Robot@'.$domen,$this->conf['conf_site_name']);
  $this->email->to($mail_to);
  $this->email->message($msg);
  $send=$this->email->send();
  $send?TRUE:exit('error');
  $data['public']==='on'?exit('onpublic'):exit('premod');
 }
 
 function comment_action($action,$code){//публикация\удаление комментария по ссылке из уведомления
  $data['msg_class']='notific_r';
  $data['msg']='Ой! Ошибка..(<br>Возможно это временные неполадки, побробуйте снова.<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
  $q=$this->db->where('premod_code',$code)->get($this->_prefix().'_comments')->result_array();
  //////////////////////проверка данных
  if(empty($q)||($action!=='public'&&$action!=='del')){//ошибка если некорректное действие или в базе нет такого кода
   $data['msg_class']='notific_r';
   $data['msg']='Действие невозможно! Комментарий уже удален или опубликован.<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
  }else{//код есть, все корректно
   if($action==='public'){//публиковать
    $this->db->where('premod_code',$code)->update($this->_prefix().'_comments',array('public'=>'on','premod_code'=>''));
    $data['msg_class']='notific_g';
    $data['msg']='Комментарий успешно опубликован!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
   }elseif($action==='del'){//удалить
    $this->db->where('premod_code',$code)->delete($this->_prefix().'_comments');
    $data['msg_class']='notific_g';
    $data['msg']='Комментарий успешно удален!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
   }
  }
  //////////////////////вывод
  $this->load->view('front/do/comment_action_view',$data);
 }

}