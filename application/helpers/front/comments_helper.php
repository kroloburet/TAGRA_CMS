<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
class Comments{
 protected $CI,$conf,$lexic;
 function __construct($conf=[]){
  $this->CI=&get_instance();
  $this->conf=$conf;
  $this->lexic=$this->CI->app('lexic');
 }

 function print_comments(){//вывод всех комментариев и формы комментирования
  $q=array_reverse($this->CI->db->where(['public'=>'on','url'=>uri_string()])->get('comments')->result_array(),true);//выборка комментов
  if($this->conf['form']!=='off'||!empty($q)){?>
<!--####### Комментарии #######-->
<div id="comments_layout">
 <div id="header_comments">
  <h2><?=$this->lexic['comments']['mod_title']?></h2>
  <div class="count_comments fa-comment">&nbsp;<span><?=count($q)?></span></div>
 </div>
 <?=$this->_build_tree($this->_tree_arr($q))?>
</div>
<?php }if($this->conf['form']!=='off'){?>
<!--####### Форма отправки #######-->
<div class="add_comment_box" class="noprint">
 <form class="add_comment_form">
  <label class="input">
   <input type="text" name="name" placeholder="<?=htmlspecialchars($this->lexic['comments']['your_name'])?>" <?=$this->conf['name_limit']>0?'onkeyup="lim(this,'.$this->conf['name_limit'].')"':FALSE?> value="<?=$this->CI->input->cookie('comment_name')?htmlspecialchars($this->CI->input->cookie('comment_name')):''?>">
  </label>
  <label class="textarea">
   <textarea name="comment" rows="5" placeholder="<?=htmlspecialchars($this->lexic['comments']['your_comment'])?>" <?=$this->conf['text_limit']>0?'onkeyup="lim(this,'.$this->conf['text_limit'].')"':FALSE?>></textarea>
  </label>
  <input type="hidden" name="pid" value="0">
  <div class="comment_form_actions">
   <button type="button" onclick="Comments.add(form)"><?=$this->lexic['comments']['send_form']?></button>
  </div>
 </form>
</div>
 <?php }
$this->print_js();
 }

 function print_comment($i){//вывод комментария (строка)
  if($this->conf['rating']=='on'){
   if(!$i['rating']){$like=$dislike=0;$disable=FALSE;}else{
    $opt=json_decode($i['rating'],TRUE);
    $like=$opt['like'];
    $dislike=$opt['dislike'];
    $cookie=$this->CI->input->cookie(md5('comment_rating_').$i['id']);
    $disable=$cookie&&$cookie===$_SERVER['REMOTE_ADDR']?'comment_rating_disable':FALSE;
   }
  }
  $reply_item=$i['pid']>0?'reply_item':'';
  $name=filter_var($i['name'],FILTER_VALIDATE_EMAIL)?explode('@',$i['name'])[0]:$i['name'];
  $reply_to_btn=$i['pid']>0?'<span class="reply_to">'.$this->lexic['comments']['reply_to'].' <a class="fa-level-up" onclick="scrll(\'comment_'.$i['pid'].'\')"></a></span>':'';
  $reply_btn=$this->conf['form']=='on'?'<a class="show_reply_form" onclick="Comments.reply(this,'.$i['id'].')">'.$this->lexic['comments']['reply'].'</a>':'';
  $rating=$this->conf['rating']=='on'?'
<div class="comment_rating_like fa-thumbs-up '.$disable.'" data-comment_id="'.$i['id'].'" title="'.htmlspecialchars($this->lexic['comments']['like']).'" onclick="Comments.rating(this)">
 <span class="comment_rating_total">'.$like.'</span>
</div>
<div class="comment_rating_dislike fa-thumbs-down '.$disable.'" data-comment_id="'.$i['id'].'" title="'.htmlspecialchars($this->lexic['comments']['dislike']).'" onclick="Comments.rating(this)">
 <span class="comment_rating_total">'.$dislike.'</span>
</div>':'';
  return'
<div class="comment_item '.$reply_item.'" id="comment_'.$i['id'].'">
 <div class="header_comment">
  <div class="comment_user">
   <span class="comment_pic" style="background-color:#'.substr(md5($i['name']),-6).'">'.mb_substr($i['name'],0,1,'UTF-8').'</span>
   <span class="comment_name">'.$name.'</span>
   '.$reply_to_btn.'
  </div>
  <time class="comment_date" title="'.htmlspecialchars($this->lexic['comments']['public_date']).'">'.$i['date'].'</time>
 </div>
 <div class="comment_text">'.$this->_replace_urls($i['comment']).'</div>
 <div class="comment_action_box">
  '.$reply_btn.$rating.'
 </div>
</div>';
 }

 function print_js(){//вывод javascript?>
<script>
var Comments={
 <?php if($this->conf['show']>0){//Установлен лимит видимых комментов?>
 go_to:function(){//переход к комменту по id в якоре
  var hash=window.location.hash;//якорь
  if(!hash||$.isEmptyObject($(hash))){return false;}//якоря нет или нет коммента для перехода к нему
  return true;//перейти
 },

 hide:function(){//скрыть комментарии после лимита и вывести кнопку "Еще комментарии"
  var comments=$('.comment_item'),//все комменты
      show=<?=$this->conf['show']?>,//лимит видимых
      show_text='<?=addslashes($this->lexic['comments']['more'])?>',
      hide_text='<?=addslashes($this->lexic['comments']['hide'])?>',
      def_text=hide_text;
  if(comments.length>show){//лимит превышен, нужно скрывать
   if(!this.go_to()){comments.slice(show).hide();def_text=show_text;}//скрыть комменты свыше лимита если нет якоря
   comments.last().after(//вывод кнопки
    $('<div/>',{class:'comments_more_btn noprint',text:def_text})
    .on('click.Hide',function(){//по клику
     var c=$('.comment_item'),//все комменты
         h=c.slice(show);//которые скрываются
     if(c.is(':hidden')){h.slideDown(200);$(this).text(hide_text);}else{h.slideUp(200);$(this).text(show_text);}
    })
   );
  }
 },

 <?php }if($this->conf['feedback']=='on'){//Обратная связь?>
 feedback:function(){//показать уведомление о возможности обратной связи
  var name=$('input[name="name"]').attr('placeholder','<?=addslashes($this->lexic['comments']['your_name_or_mail'])?>'),
      msg=$('<div/>',{class:'feedback_msg',text:'<?=addslashes($this->lexic['comments']['feedback_msg'])?>'});
  name.on('focus.Feedback',function(){$(this).before(msg);});
  name.on('blur.Feedback',function(){msg.remove();});
 },

 <?php }if($this->conf['rating']=='on'){//Рейтинг коммента?>
 rating:function(el){//рейтинг коммента
  for(var i=0,a=['comment_rating_disable','comment_rating_process','comment_rating_good_msg','comment_rating_bad_msg'];i<a.length;i++){if($(el).hasClass(a[i]))return false;}//выходить если уже проголосовали или в процессе
  var self=$(el),
      id=self.data('comment_id'),
      box=self.parent('.comment_action_box'),
      all=box.find('.comment_rating_like,.comment_rating_dislike'),
      action=self.hasClass('comment_rating_like')?'like':'dislike',
      err_msg=$('<p/>',{class:'notific_r mini',html:'<?=addslashes($this->lexic['basic']['error'])?>'}),
      clear=function(){self.removeClass('comment_rating_process comment_rating_good_msg comment_rating_bad_msg');err_msg.remove();return self;},
      err=function(){clear().addClass('comment_rating_bad_msg');box.append(err_msg);setTimeout(function(){clear();all.removeClass('comment_rating_disable');},4000);},
      ok=function(total){clear().addClass('comment_rating_good_msg').find('.comment_rating_total').text(total);setTimeout(clear,2000);};
  all.addClass('comment_rating_disable');
  self.addClass('comment_rating_process');
  $.ajax({
   url: '/do/comment_rating',
   type: 'post',
   data: {id:id,hash:'<?=md5('comment_rating_')?>'+id,action:action},
   dataType: 'json',
   success: function(resp){
    switch(resp.status){
     case 'ok':ok(resp.rating[action]);break;
     case 'error':err();break;
     default :err();console.log(resp);break;
    }
   },
   error:function(){err();}
  });
 },

 <?php }if($this->conf['form']=='on'){//Форма ответа на комментарий?>
 reply:function(el,id){//подготовка и вывод формы ответа
  if($('.add_comment_form').length>1){return false;}//не запускать больше одной формы ответа
  var form=$('.add_comment_form'),//основная форма комментирования
      clone=form.clone(true),//клонировать основную форму
      parent=$('#comment_'+id),//ответ для
      cancel=$('<a/>',{class:'hide_reply_form',text:'<?=addslashes($this->lexic['comments']['hide_form'])?>'}).on('click.Reply',function(){clone.remove();form.slideDown(200)});//кнопка отмены
  clone.addClass('reply_form');
  clone.find('[name="comment"]').attr('placeholder','<?=addslashes($this->lexic['comments']['your_reply'])?>').val(parent.find('.comment_name').text()+', ');
  clone.find('[name="pid"]').val(id);
  clone.find('button').text('<?=addslashes($this->lexic['comments']['send_reply'])?>').after(cancel);
  parent.find('.comment_action_box').after(clone);
  form.slideUp(200);
 },

 <?php }if($this->conf['form']!=='off'){//Отправка комментария?>
 add:function(form){//отправка коммента/ответа
  var f=$(form),
      name=f.find('[name="name"]'),
      name_val=$.trim(name.val()),
      comment=f.find('[name="comment"]'),
      pid=f.find('[name="pid"]'),
      id=new Date().getTime().toString(),
      actions_box=f.find('.comment_form_actions'),
      actions=actions_box.html(),
      delay=4000,
      msg=function(m){actions_box.html(m);setTimeout(function(){actions_box.html(actions);},delay);};
  //проверка полей
  if(!/\S/.test(name_val)){
   msg('<p class="notific_r mini full"><?=addslashes($this->lexic['comments']['novalid_field'])?>"'+name.attr('placeholder')+'"</p>');return false;}
  if(!/\S/.test(comment.val())){
   msg('<p class="notific_r mini full"><?=addslashes($this->lexic['comments']['novalid_field'])?>"'+comment.attr('placeholder')+'"</p>');return false;}
  <?php if($this->conf['feedback']=='on'){//если обратная связь?>
  if(~name_val.indexOf('@'))//в поле есть признак email
   if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(name_val)){
    msg('<p class="notific_r mini full"><?=addslashes($this->lexic['comments']['novalid_mail'])?>'+name.attr('placeholder')+'"</p>');return false;}
  <?php }?>
  //блокирую кнопку
  actions_box.find('button').attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i><?=addslashes($this->lexic['basic']['loading'])?>');
  //отправка
  $.ajax({
   url:'/do/add_comment',
   type:'post',
   data:{
    id:id,//id коммента/ответа
    pid:pid.val(),//id родительского коммента/ответа
    name:name_val,//имя/email комментатора
    comment:comment.val(),//текст коммента
    url:'<?=uri_string()?>',//url материала
    lang:'<?=$this->CI->app('data.lang')?>',//язык материала
    conf:'<?=$this->conf['form']?>'//форма
   },
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case 'onpublic'://публикация без премодерации
      comment.val('');//очистка формы
      actions_box.html(actions);
      if(pid.val()!=='0'){//это ответ
       f.remove();//удалить форму ответа
       $('#comment_'+pid.val()).after(resp.html)//вставить ответ в список комментариев
       $('.add_comment_form').slideDown(200);//отобразить основную форму если она скрыта
      }else{//это комментарий
       $('#header_comments').after(resp.html);//вставить комментарий в список
       scrll('comment_'+id);//прокрутить к комментарию
      }
      $('.count_comments span').text($('.comment_item').length);//обновить счетчик комментариев
      break;
     case 'premod'://не опубликован, нужна премодерация
      comment.val('');//очистка формы
      msg('<p class="notific_g mini full"><?=addslashes($this->lexic['comments']['premod_msg'])?></p>');
      if(pid.val()!=='0'){setTimeout(function(){f.remove();$('.add_comment_form').slideDown(200);},delay);}//удалить форму ответа, отобразить основную
      break;
     case 'reserved_name'://имя зарезервировано
      msg('<p class="notific_r mini full">"'+name_val+'" <?=addslashes($this->lexic['comments']['reserved_name_msg'])?></p>');
      break;
     case 'error'://ошибки
      msg('<p class="notific_r mini full"><?=addslashes($this->lexic['basic']['error'])?></p>');
      break;
     default :console.log(resp);
    }
   },
   error:function(){msg('<p class="notific_r mini full"><?=addslashes($this->lexic['basic']['server_error'])?></p>');}
  });
 }
 <?php }?>
};
//////////////////////////////////////////вызов методов событий елементов (слушателей)
window.addEventListener('load',function(){
<?php if($this->conf['show']>0){//Кнопка "Еще комментарии"?>
Comments.hide();
<?php }if($this->conf['feedback']=='on'){//Обратная связь?>
Comments.feedback();
<?php }?>
});
</script>
 <?php }

 function _tree_arr($arr){//получить многомерный массив
  //$arr=выборка из базы
  $tree_arr=[];
  foreach($arr as $v){$tree_arr[$v['pid']][]=$v;}
  return $tree_arr;
 }

 function _build_tree($tree_arr,$pid=0){//построение дерева
  //$tree_arr=многомерный массив выборки из базы
  //$pid=уровень, с которого начать построение дерева. по умолчанию 0 (корень)
  if(!is_array($tree_arr) || !isset($tree_arr[$pid])){return false;}
  $tree='';
  foreach($tree_arr[$pid] as $v){
   $tree.=$this->print_comment($v);
   $tree.=$this->_build_tree($tree_arr,$v['id']);
  }
  return $tree;
 }

 function _replace_urls($text=null){//метод находит в строке урлы и заменяет их на ссылки HTML (nofollow)
  return preg_replace_callback('/https?:\/\/[\S]+/ui',function($m){
   return '<a href="'.$m[0].'" target="_blank" rel="nofollow">'.$m[0].'</a>';
  },$text);
 }

}