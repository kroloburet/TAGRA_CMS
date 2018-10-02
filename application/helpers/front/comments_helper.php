<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('get_comments')){
function get_comments($access=FALSE){//
if(!$access||$access==='off'){return FALSE;}
$CI=&get_instance();
$prefix=$CI->config->item('db_tabl_prefix');
$url=uri_string();
$q=$CI->db->order_by('id','DESC')->where(array('public'=>'on','url'=>$url))->get($prefix.'comments')->result_array();//выборка комментов
$c=count($q);//количество комментов
$vsbl=3;//количество первых (не скрытых)
if($c>0){//есть комментарии
?>
<!--####### Комментарии к материалу #######-->
<div id="comments_layout">
 <div id="header_comments">
  <h2>Комментарии</h2>
  <div class="count_comments"><i class="fa fa-comment">&nbsp;<?=$c?></i></div>
 </div>
<?php foreach($q as $k=>$v){if($c>$vsbl&&$k===$vsbl){?>
 <div id="comments_more">
<?php }?>
 <div class="comment_item">
  <div id="header_comment">
   <div class="comment_name">
    <i class="fa fa-user"></i>&nbsp;<b><?=$v['name']?></b>
   </div>
   <div class="comment_date">
    <time class="fa fa-clock-o">&nbsp;<?=$v['date']?></time>
   </div>
  </div>
  <div class="comment_text">
   <sup class="fa fa-quote-left"></sup>
    &nbsp;&nbsp;<span><?=$v['comment']?></span>&nbsp;&nbsp;
   <sub class="fa fa-quote-right"></sub>
  </div>
  <?php
  if(!$v['rating']){$like=$dislike=0;$disable=FALSE;}else{
   $opt=json_decode($v['rating'],TRUE);
   $like=$opt['like'];
   $dislike=$opt['dislike'];
   $cookie=$CI->input->cookie(md5('comment_rating_').$v['id']);
   $disable=$cookie&&$cookie===$_SERVER['REMOTE_ADDR']?'comment_rating_disable':FALSE;
  }
  ?>
  <div class="comment_rating_box">
   <div class="comment_rating_like fa-thumbs-up <?=$disable?>" data-comment_id="<?=$v['id']?>" title="Мне нравится">
    <span class="comment_rating_total"><?=$like?></span>
   </div>
   <div class="comment_rating_dislike fa-thumbs-down <?=$disable?>" data-comment_id="<?=$v['id']?>" title="Мне не нравится">
    <span class="comment_rating_total"><?=$dislike?></span>
   </div>
  </div>
 </div>
<?php if($c>$vsbl&&$k===$c-1){?>
 </div>
<?php }}if($c>$vsbl){?>
 <div id="comments_more_btn" onclick="opn_cls('comments_more')" class="noprint">Показать/скрыть еще <?=($c-$vsbl)?></div>
<?php }?>
</div>

<script>
//////////////////////////////////////рейтинг комментария
window.addEventListener('load',function(){
 $('.comment_rating_like,.comment_rating_dislike').on('click.Comment_Rating',function(){
  for(var i=0,a=['comment_rating_disable','comment_rating_process','comment_rating_good_msg','comment_rating_bad_msg'];i<a.length;i++){if($(this).hasClass(a[i]))return false;}
  var self=$(this),
      id=self.data('comment_id'),
      box=self.parent('.comment_rating_box'),
      all=box.find('.comment_rating_like,.comment_rating_dislike'),
      action=self.hasClass('comment_rating_like')?'like':'dislike',
      err_msg=$('<p/>',{class:'notific_r mini',html:'Ой! Ошибка..( Возможно это временные неполадки. Попробуйте снова!'}),
      clear=function(){self.removeClass('comment_rating_process comment_rating_good_msg comment_rating_bad_msg');err_msg.remove();return self;},
      err=function(){clear().addClass('comment_rating_bad_msg');box.append(err_msg);setTimeout(function(){clear();all.removeClass('comment_rating_disable');},4000);},
      ok=function(total){clear().addClass('comment_rating_good_msg').find('.comment_rating_total').text(total);setTimeout(clear,2000);};
  all.addClass('comment_rating_disable');
  self.addClass('comment_rating_process');
  $.ajax({
   url: '<?=base_url('do/comment_rating')?>',
   type: 'post',
   data: {id:id,hash:'<?=md5('comment_rating_')?>'+id,action:action},
   dataType: 'json',
   success: function(resp){
    switch(resp.status){
     case 'ok':ok(resp.rating[action]);break;
     case 'error':err();break;
     default :err();console.log(resp);break;
    }
   }
  });
 });
});
</script>
<?php }}}
/////////////////////////////////////////////////////////////////
if(!function_exists('get_comments_form')){
function get_comments_form($access=FALSE){//
if(!$access||$access==='off'){return FALSE;}
?>
<!--####### Форма для комментирования #######-->
<div id="add_comment" class="noprint">
<h2>Комментировать</h2>
<form id="add_com">
<label class="input">
<input type="text" name="name" onkeyup="lim(this,50)" placeholder="Ваше имя">
</label>
<label class="textarea">
<textarea name="comment" rows="5" onkeyup="lim(this,500)" maxlength="500" placeholder="Ваш комментарий"></textarea>
</label>
<input type="hidden" name="url" value="<?=uri_string()?>">
<input type="text" name="fuck_bot">
<div id="comment_msg"></div>
<button type="submit">Отправить комментарий</button>
</form>
</div>

<script>
//////////////////////////////////////отправка комментария
window.addEventListener('load',function(){
 $('#add_com').on('submit',function(e){
  e.preventDefault();
  var form=$(this),
      name=form.find('[name="name"]'),
      comment=form.find('[name="comment"]'),
      btn=form.find(':submit'),
      msg=$('#comment_msg');
  //проверка полей
  if(!/\S/.test(name.val())){name.addClass('novalid').focus();return false;}else{name.removeClass('novalid');}
  if(!/\S/.test(comment.val())){comment.addClass('novalid').focus();return false;}else{comment.removeClass('novalid');}
  btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
  //отправка
  $.ajax({
    url: '<?=base_url('do/add_comment')?>',//путь к скрипту, который обработает запрос
    type: 'post',
    data: form.serialize(),
    dataType: 'text',
    success: function(data){//обработка ответа
     switch(data){
      //бот или хитрожопый мудак
      case 'bot':msg.html('<div class="notific_r">Ой! Вы робот!? Вам здесь не рады..(</div>');
       btn.remove();//удаляю кнопку
       break;
      //комментарий не записан в базу
      case 'error':msg.html('<div class="notific_r">Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.</div>');
       btn.attr('disabled',false).html('Отправить комментарий');
       break;
      //все пучком, опубликован без премодерации
      case 'onpublic':btn.remove();
       msg.html('<div class="notific_g">Ваш Комментарий успешно опубликован!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обновление страницы...</div>');
       setTimeout(function(){location.reload();},5000);
       break;
      //все пучком, не опубликован, нужна премодерация
      case 'premod':name.add(comment).val('');
       btn.attr('disabled',false).html('Отправить комментарий');
       msg.html('<div class="notific_g">Ваш комментарий будет опубликован после проверки модератором.</div>');
       setTimeout(function(){msg.empty();},5000);
       break;
      //ошибки сценария сервера
      default :msg.html('<div class="notific_b">'+data+'</div>');
       btn.attr('disabled',false).html('Отправить комментарий');
       break;
     }
    }
  });
 });
});
</script>
<?php }}
