<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Front_basic_control.php');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Front_comment_control extends Front_basic_control{
 protected $c_conf=[];//массив настроек комментариев
 public $domen;
 function __construct(){
  parent::__construct();
  $this->load->model('front_comment_model');
  $this->c_conf=$this->conf['conf_comments'];
  $this->domen=str_replace('www.','',$this->input->server('HTTP_HOST'));
 }

 function _send_feedback($data){//уведомление комментатору об ответе
  if($this->c_conf['feedback']=='off'||$data['public']=='off'||$data['pid']=='0'){return FALSE;}
  $this->load->library('email');
  $q=$this->db->where('id',$data['pid'])->get($this->_prefix().'comments')->result_array();//получить родительский коммент
  if(!isset($q[0])||empty($q[0])||!filter_var($q[0]['name'],FILTER_VALIDATE_EMAIL)||$q[0]['feedback']=='off'){return FALSE;}
  //есть родитель с email и подпиской - отправка уведомления подписчику об ответе
  $this->email->subject('Ответ на ваш комментарий с '.$this->domen);
  $reply_name=filter_var($data['name'],FILTER_VALIDATE_EMAIL)?explode('@',$data['name'])[0]:$data['name'];
  $msg='
<html><head><title>Ответ на ваш комментарий с '.$this->domen.'</title>
</head><body>
<h2>Ответ на ваш комментарий с '.$this->domen.'</h2>
<p style="padding:0;margin:0.5em 0 0 0">
<b>'.explode('@',$q[0]['name'])[0].'</b> <time style="color:#888">опубликован '.$q[0]['date'].'</time><br>'.$q[0]['comment'].'
</p>
<p style="padding:0;margin:0.5em 0 0 2em" title="Новый комментарий">
<b><i style="color:green">* </i>'.$reply_name.'</b> <time style="color:#888">опубликован '.$data['date'].'</time><br>'.$data['comment'].'<br>
<a href="'.base_url($data['url'].'#comment_'.$data['id']).'" target="_blank">Перейти к этому ответу в материале</a>
</p>
<hr>
Если это уведомление пришло вам по ошибке или вы больше не хотите получать такие уведомления:<br>
<a href="'.base_url('do/comment_unfeedback?action=uncomment&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url']).'" target="_blank" title="Больше не уведомлять об ответах на этот комментарий">Не уведомлять об ответах на этот комментарий</a> | <a href="'.base_url('do/comment_unfeedback?action=unpage&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url']).'" target="_blank" title="Больше не уведомлять об ответах на мои комментарии в этом материале">Не уведомлять об ответах в этом материале</a> | <a href="'.base_url('do/comment_unfeedback?action=unsite&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url']).'" target="_blank" title="Больше не уведомлять об ответах на мои комментарии на всем сайте">Не уведомлять об ответах на сайте</a>
</body></html>';
  $this->email->from('Robot@'.$this->domen,$this->conf['conf_site_name']);
  $this->email->to($q[0]['name']);
  $this->email->message($msg);
  return $this->email->send()?TRUE:FALSE;
 }

 function _send_notific($data){//уведомление админу/модераторам о новом комменте
  $this->load->library('email');
  $mail_to=$actions=$parent['html']=$parent['child_css']=FALSE;
  $public='<a href="'.base_url('do/comment_action/public/'.$data['premod_code']).'" target="_blank">Публиковать</a>';
  $del='<a href="'.base_url('do/comment_action/del/'.$data['premod_code']).'" target="_blank">Удалить</a>';
  $del_branch='<a href="'.base_url('do/comment_action/del_branch/'.$data['premod_code']).'" target="_blank">Удалить с дочерней ветвью</a>';
  $control='<a href="'.base_url('admin/comment/get_list').'" target="_blank">Управление комментариями</a>';
  $go_to_comment='<a href="'.base_url($data['url'].'#comment_'.$data['id']).'" target="_blank">Перейти к этому ответу в материале</a>';
  $no_premod_actions=$go_to_comment.' | '.$control.' | '.$del_branch;//опции быстрого управления комментом из e-mail (без публикации)
  $premod_actions=$public.' | '.$del.' | '.$control;//опции быстрого управления комментом из e-mail (с публикацией)
  switch($this->c_conf['notific']){//на какой e-mail высылать уведомление
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
    $actions=$premod_actions;
   break;
   case 'premod_admin_mail':
    $mail_to=$this->conf['conf_admin_mail'];
    $actions=$premod_actions;
   break;
   case 'premod_moderator_mail':
    $mail_to=$this->conf['conf_moderator_mail'];
    $actions=$premod_actions;
   break;
   default :return FALSE;//никому не высылать
  }
  //родительский коммент, если он есть
  if($data['pid']>0){//есть родитель
   $q=$this->db->where('id',$data['pid'])->get($this->_prefix().'comments')->result_array();//получить родительский коммент
   if(isset($q[0])&&!empty($q[0])){//получен
    $parent['html']='
<p style="padding:0;margin:0.5em 0 0 0">
<b>'.$q[0]['name'].'</b> <time style="color:#888">опубликован '.$q[0]['date'].'</time><br>'.$q[0]['comment'].'
</p>';
    $parent['child_css']='padding:0;margin:0.5em 0 0 2em';
   }
  }
  //отправка уведомления админу или модераторам
  $this->email->subject('Новый комментарий на '.$this->domen);
  $msg='
<html><head><title>Новый комментарий на '.$this->domen.'</title>
</head><body>
<h2>Новый комментарий на '.$this->domen.'</h2>
IP пользователя: '.$data['ip'].'<br>
Материал: <a href="'.base_url($data['url']).'" target="_blank">'.base_url($data['url']).'</a><br>
'.$parent['html'].'
<p style="'.$parent['child_css'].'" title="Новый комментарий">
<b><i style="color:green">* </i>'.$data['name'].'</b> <time style="color:#888">отправлен '.$data['date'].'</time><br>'.$data['comment'].'
</p>
<hr>
'.$actions.'
</body></html>';
  $this->email->from('Robot@'.$this->domen,$this->conf['conf_site_name']);
  $this->email->to($mail_to);
  $this->email->message($msg);
  return $this->email->send()?TRUE:FALSE;
 }

 function add_comment(){//отправка комментария аяксом
  !$this->input->post()?exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT)):TRUE;
  $p=array_map('strip_tags',array_map('trim',$this->input->post()));
  ////////////////////////////////////////////////валидация имени
  if($this->c_conf['reserved_names']&&!$this->conf['back_user']){//есть зарезервированные имена и комментит не админ
   preg_grep('/^'.$p['name'].'$/ui',array_map('trim',explode(';',$this->c_conf['reserved_names'])))?exit(json_encode(['status'=>'reserved_name'],JSON_FORCE_OBJECT)):TRUE;
  }
  ////////////////////////////////////////////////запись данных
  $data=[
  'id'=>$p['id'],
  'pid'=>$p['pid'],//id родительского коммента
  'name'=>$p['name'],
  'comment'=>$p['comment'],
  'url'=>$p['url'],
  'date'=>date('Y-m-d'),
  'ip'=>$this->input->server('REMOTE_ADDR'),
  'feedback'=>filter_var($p['name'],FILTER_VALIDATE_EMAIL)?'on':'off',//если в имени валидный email - подписка на ответы
  'premod_code'=>$this->c_conf['notific']!=='off'?microtime(TRUE):'',//одноразовый код коммента для быстрого управления из e-mail
  'public'=>in_array($this->c_conf['notific'],['off','site_mail','admin_mail','moderator_mail'])?'on':'off'//не публиковать если премодерация
  ];
  ////////////////////////////////////////////////запись в базу
  $this->input->set_cookie('comment_name',$p['name'],0);//запомнить имя комментатора
  $this->front_comment_model->add_comment($data)?TRUE:exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));
  ////////////////////////////////////////////////уведомление админу или модераторам
  $this->_send_notific($data);
  ////////////////////////////////////////////////обратная связь
  $this->_send_feedback($data);
  ////////////////////////////////////////////////возврат
  if($data['public']=='off'){//публикация после премодерации
   exit(json_encode(['status'=>'premod'],JSON_FORCE_OBJECT));
  }else{//публикация сейчас
   $q=$this->db->where('id',$data['id'])->get($this->_prefix().'comments')->result_array();//публикуемый коммент
   !isset($q[0])||empty($q[0])?exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT)):TRUE;//если удален
   $this->load->helper('front/comments');
   $comm=new Comments(array_replace($this->c_conf,['form'=>$p['conf']]));//заменить в глобальном конфиге конфигом со страницы, передать
   exit(json_encode(['status'=>'onpublic','html'=>$comm->print_comment($q[0])],JSON_FORCE_OBJECT));
  }
 }

 function comment_action($action,$code){//публикация\удаление комментария по ссылке из уведомления
  $data['msg_class']='notific_r';
  $data['msg']='Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
  $q=$this->db->where('premod_code',$code)->get($this->_prefix().'comments')->result_array();
  //////////////////////проверка данных
  if(!isset($q[0])||empty($q[0])||($action!=='public'&&$action!=='del'&&$action!=='del_branch')){//некорректное действие или в базе нет такого кода
   $data['msg_class']='notific_r';
   $data['msg']='Действие невозможно! Комментарий уже удален или опубликован.<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
  }else{//код есть, все корректно
   if($action==='public'){//публиковать
    $resp=$this->front_comment_model->public_new($code);
    if($resp && $this->c_conf['feedback']=='on'){//опубликован и обратная связь разрешена
     $q[0]['public']='on';
     $q[0]['date']=date('Y-m-d');
     $this->_send_feedback($q[0]);//отправить уведомление об ответе
    }
    $data['msg_class']='notific_g';
    $data['msg']='Комментарий успешно опубликован!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
   }elseif($action==='del'){//удалить
    if($this->front_comment_model->del_new($code)){
     $data['msg_class']='notific_g';
     $data['msg']='Комментарий успешно удален!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
    }
   }elseif($action==='del_branch'){//удалить с ветвью
    if($this->front_comment_model->del_branch($q[0]['id'],$q[0]['url'])){
     $data['msg_class']='notific_g';
     $data['msg']='Комментарий с дочерней ветвью успешно удален!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;завершение сценария...';
    }
   }
  }
  //////////////////////вывод
  $this->load->view('front/do/comment_action_view',$data);
 }

 function comment_unfeedback(){//отписка от обратной связи
  $reload='<a href="#" onclick="window.location.reload(true);return false;">Попробовать снова</a>';
  $close='<a href="#" onclick="window.close();return false;">Закрыть страницу</a>';
  $data['msg_class']='notific_r';
  $data['msg']="Ошибка..( Данные для работы сценария повреждены или не переданы! $close";
  $g=$this->input->get();
  $action=isset($g['action'])&&!empty($g['action'])?$g['action']:FALSE;
  $pid=isset($g['pid'])&&!empty($g['pid'])?$g['pid']:FALSE;
  $mail=filter_var(isset($g['mail'])?$g['mail']:FALSE,FILTER_VALIDATE_EMAIL);
  $url=filter_var(isset($g['url'])?$g['url']:FALSE,FILTER_SANITIZE_URL);
  if($action && $pid && $mail && $url){//данные переданы
   switch($action){
    case 'uncomment'://отписка от коммента
     $where=['feedback'=>'on','id'=>$pid,'name'=>$mail,'url'=>$url];
     $data['msg_class']='notific_g';
     $data['msg']="Успешно! На e-mail «{$mail}» больше не будут приходить уведомления об ответах на ваш комментарий. Если пожелаете получать уведомления об ответах на ваши новые комментарии, снова укажите e-mail вместо имени.<br>$close";
    break;
    case 'unpage'://отписка от всех комментов в материале
     $where=['feedback'=>'on','name'=>$mail,'url'=>$url];
     $data['msg_class']='notific_g';
     $data['msg']="Успешно! На e-mail «{$mail}» больше не будут приходить уведомления об ответах на ваши комментарии в материале. Если пожелаете получать уведомления об ответах на ваши новые комментарии, снова укажите e-mail вместо имени.<br>$close";
    break;
    case 'unsite'://отписка от всех комментов на сайте
     $where=['feedback'=>'on','name'=>$mail];
     $data['msg_class']='notific_g';
     $data['msg']="Успешно! На e-mail «{$mail}» больше не будут приходить уведомления об ответах на ваши комментарии с сайта. Если пожелаете получать уведомления об ответах на ваши новые комментарии, снова укажите e-mail вместо имени.<br>$close";
    break;
    default :$this->load->view('front/do/comment_unfeedback_view',$data);return FALSE;
   }
   if(!$this->db->where($where)->update($this->_prefix().'comments',['feedback'=>'off'])){//данные не изменены
    $data['msg_class']='notific_r';
    $data['msg']="Ой! Ошибка..( Возможно это временные неполадки.<br>$reload | $close";
   }
  }
  //////////////////////вывод
  $this->load->view('front/do/comment_unfeedback_view',$data);
 }

 function comment_rating(){//рейтинг комментариев
  if(!$this->input->post()){$resp['status']='error';}else{//есть данные
   $p=array_map('strip_tags',array_map('trim',$this->input->post()));
   $q=$this->db->select('rating')->get_where($this->_prefix().'comments',['id'=>$p['id']])->result_array();//получить рейтинг коммента
   if(empty($q)){$resp['status']='error';}else{//есть коммент
    //обновить рейтинг коммента, запомнить выбор, создать массив для возврата
    $arr=!$q[0]['rating']?['like'=>0,'dislike'=>0]:json_decode($q[0]['rating'],TRUE);
    $arr[$p['action']]++;
    $resp['status']=$this->front_comment_model->add_comment_rating($p['id'],json_encode($arr,JSON_FORCE_OBJECT))?'ok':'error';
    $resp['status']==='ok'?$this->input->set_cookie($p['hash'],$this->input->server('REMOTE_ADDR'),0):FALSE;
    $resp['rating']=$arr;
   }
  }
  ////////////////////json возврат
  exit(json_encode($resp,JSON_FORCE_OBJECT));
 }

}