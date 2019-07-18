<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'controllers/Back_basic_control.php');

///////////////////////////////////
//работа с комментариями
///////////////////////////////////

class Back_comment_control extends Back_basic_control{
 function __construct(){
  parent::__construct();
  $this->load->model('back_comment_model');
  $this->c_conf=$this->app('conf.comments');
  $this->domen=str_replace('www.','',$this->input->server('HTTP_HOST'));
 }

 function get_list(){
  $data['conf']=$this->c_conf;
  $data['new_comments']=$this->back_comment_model->get_new();
  //разобрать get-данные если они есть, если нет - установить по умолчанию
  $get=$this->input->get();
  isset($get['order'])?TRUE:$get['order']='id';
  isset($get['search'])?TRUE:$get['search']='';
  isset($get['context_search'])?TRUE:$get['context_search']='comment';
  isset($get['pag_per_page'])?TRUE:$get['pag_per_page']=$this->session->pag_per_page;
  isset($get['per_page'])?TRUE:$get['per_page']=0;
  //получить выборку для страницы результата и количество всех записей
  $comments=$this->back_basic_model->get_result_list('comments',$get);
  //инициализация постраничной навигации
  $this->_set_pagination(current_url(),$comments['count_result']);
  $data['comments']=$comments['result'];
  $data['view_title']='Управление комментариями';
  $this->_viewer('back/comments/comments_list_view',$data);
 }

 function del_branch(){
  $p=$this->input->post();
  !$p||!$p['id']||!$p['url']?exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT)):TRUE;
  $ids=$this->back_comment_model->del_branch($p['id'],$p['url']);
  $ids?exit(json_encode(['status'=>'ok','ids'=>$ids],JSON_FORCE_OBJECT)):exit(json_encode(['status'=>'error'],JSON_FORCE_OBJECT));
 }

 function del_new($code){
  $this->back_comment_model->del_new($code);
  redirect('admin/comment/get_list');
 }

 function public_new($code){
  $q=$this->db->where('premod_code',$code)->get('comments')->result_array();
  if(isset($q[0])&&!empty($q[0])){//комментарий существует и не опубликован
   if($this->back_comment_model->public_new($code) && $this->c_conf['feedback']=='on'){//опубликован и обратная связь разрешена
    $this->_send_feedback($q[0]);//отправить уведомление об ответе
   }
  }
  redirect('admin/comment/get_list');
 }

 function get_count_new(){
  echo count($this->back_comment_model->get_new());
 }

 function set_comments_config(){
  $conf=json_encode(array_map('trim',$this->input->post()));
  $this->db->where('name','comments')->update('config',['value'=>$conf]);
  redirect('admin/comment/get_list');
 }

 function _send_feedback($data){//уведомление комментатору об ответе
  if($this->c_conf['feedback']=='off'||$data['public']=='off'||$data['pid']=='0'){return FALSE;}
  $this->load->library('email');
  $q=$this->db->where('id',$data['pid'])->get('comments')->result_array();//получить родительский коммент
  if(!isset($q[0])||empty($q[0])||!filter_var($q[0]['name'],FILTER_VALIDATE_EMAIL)||$q[0]['feedback']=='off'){return FALSE;}
  //есть родитель с email и подпиской - отправка уведомления подписчику об ответе
  $lexic=$this->lang->load('front_template',in_array($q[0]['lang'],array_column($this->app('conf.langs'),'tag'))?$q[0]['lang']:$this->app('conf.lang_def.tag'),TRUE);//получить файл локализации языка родительского коммента
  $this->email->subject(htmlspecialchars($lexic['comments']['feedback_title']).$this->domen);
  $reply_name=filter_var($data['name'],FILTER_VALIDATE_EMAIL)?explode('@',$data['name'])[0]:$data['name'];
  $msg='
<html><head><title>'.htmlspecialchars($lexic['comments']['feedback_title']).$this->domen.'</title>
</head><body>
<h2>'.$lexic['comments']['feedback_title'].$this->domen.'</h2>
<p style="padding:0;margin:0.5em 0 0 0">
<b>'.explode('@',$q[0]['name'])[0].'</b> <time style="color:#888">'.$lexic['comments']['published'].$q[0]['date'].'</time><br>'.$q[0]['comment'].'
</p>
<p style="padding:0;margin:0.5em 0 0 2em" title="'.htmlspecialchars($lexic['comments']['new']).'">
<b><i style="color:green">* </i>'.$reply_name.'</b> <time style="color:#888">'.$lexic['comments']['published'].$data['date'].'</time><br>'.$data['comment'].'<br>
<a href="'.base_url($data['url'].'#comment_'.$data['id']).'" target="_blank">'.$lexic['comments']['go_to'].'</a>
</p>
<hr>
'.$lexic['comments']['unfeedback_msg'].'<br>
<a href="'.base_url('do/comment_unfeedback?action=uncomment&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url'].'&lang='.$data['lang']).'" target="_blank" title="'.htmlspecialchars($lexic['comments']['uncomment_more']).'">'.$lexic['comments']['uncomment_less'].'</a> | <a href="'.base_url('do/comment_unfeedback?action=unpage&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url'].'&lang='.$data['lang']).'" target="_blank" title="'.htmlspecialchars($lexic['comments']['unpage_more']).'">'.$lexic['comments']['unpage_less'].'</a> | <a href="'.base_url('do/comment_unfeedback?action=unsite&pid='.$q[0]['id'].'&mail='.$q[0]['name'].'&url='.$data['url'].'&lang='.$data['lang']).'" target="_blank" title="'.htmlspecialchars($lexic['comments']['unsite_more']).'">'.$lexic['comments']['unsite_less'].'</a>
</body></html>';
  $this->email->from('Robot@'.$this->domen,$this->app('conf.site_name'));
  $this->email->to($q[0]['name']);
  $this->email->message($msg);
  return $this->email->send()?TRUE:FALSE;
 }

}