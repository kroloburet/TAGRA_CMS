<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('administrator_control')){//управление администратором системы
function administrator_control(){
$CI=&get_instance();
$q=$CI->db->where('status','administrator')->get('back_users')->result_array();
if(empty($q)){$a='{}';}else{foreach($q as $v){$a[$v['id']]=$v;}$a=json_encode($a,JSON_FORCE_OBJECT);}
unset($q);
?>
<!--####### Настройки администратора #######-->
<div class="touch" id="administrator_control">
 <h3>Настройки администратора</h3>
 <hr>
 <div class="buc_box">
  <div class="buc_info"></div>
  Логин <i class="fa-info-circle red" onmouseover="tt(this);"></i>
  <pre class="tt">
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin</pre>
  <label class="input">
   <input type="text" class="buc_login" placeholder="Оставьте поле пустым если не хотите менять логин">
  </label>
  Пароль <i class="fa-info-circle red" onmouseover="tt(this);"></i>
  <pre class="tt">
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генерируйте его!</b></pre>
  <a href="#" onclick="gen_pass('ac_pass');return false" class="fa-refresh" title="Генерировать пароль"></a>
  <label class="input">
   <input type="text" class="buc_pass" id="ac_pass"placeholder="Оставьте поле пустым если не хотите менять пароль">
  </label>
  E-mail <i class="fa-info-circle red" onmouseover="tt(this);"></i>
  <pre class="tt">
На этот е-mail будут высланы логин
и парол, если вы их забудите.
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
  <label class="input">
   <input type="text" class="buc_mail">
  </label>
  <div class="button">
   <button type="button" class="buc_done_btn">Готово</button><button type="button" class="buc_cancel_btn">Отмена</button>
  </div>
 </div>
 <div class="buc_prev"></div>
</div>

<script>
;(function($){
 var _=$('#administrator_control'),//блок настроек
     _box=_.find('.buc_box'),//блок полей "редактировать"
     _info=_.find('.buc_info'),//блок информации
     _login=_.find('.buc_login'),//поле "логин"
     _pass=_.find('.buc_pass'),//поле "пароль"
     _mail=_.find('.buc_mail'),//поле "email"
     _done_btn=_.find('.buc_done_btn'),//кнопка "готово"
     _cancel_btn=_.find('.buc_cancel_btn'),//кнопка "отмена"
     _prev=_.find('.buc_prev'),//блок превью
     _opt=<?=$a?>;//объект администратора
 ////////////////////////////////валидация полей
 var __validator=function(){
  //валидация поля "E-mail"
  _mail.val($.trim(_mail.val()));
  if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(_mail.val())){
   alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');return false;
  }
  //проверка пройдена успешно
  return true;
 };
 ////////////////////////////////открыть форму редактирования
 var __get_edit_form=function(id){
  var last_mod=_opt[id].last_mod_date||'не изменялся',
      last_login=_opt[id].last_login_date?_opt[id].last_login_date+' с IP '+_opt[id].ip:'не входил';
  __clear();
  _done_btn.on('click.BUC',function(){__edit(id);});//событие на "готово" - редактировать
  _info.html('<p class="notific_b mini full">Дата создания: '+_opt[id].register_date+'<br>Дата изменения: '+last_mod+'<br>Дата последнего входа: '+last_login+'</p>');//информация об администраторе
  _mail.val(_opt[id].email);
  _box.slideDown(200);//открыть блок полей
 };
 ////////////////////////////////скрыть, очистить блок полей
 var __clear=function(){
  _done_btn.off();//удалить все события у кнопки "готово"
  _info.empty();
  _box.slideUp(200).find('input').val('');//очистить поля, скрыть блок
 };
 ////////////////////////////////редактировать
 var __edit=function(){
  if(!__validator()){return false;}
  var done_btn_text=_done_btn.text();
  _cancel_btn.hide();
  _done_btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');
  $.ajax({
   url:'/admin/setting/edit_administrator',
   type:'post',
   data:{login:_login.val(),password:_pass.val(),email:_mail.val()},
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case'error':alert('Ой! Ошибка..(\nВозможно это временные неполадки, попробуйте снова.');break;
     case'nomail':alert('E-mail некорректный!\nИзмените и попробуйте снова.');break;
     case'nounq':alert('В системе уже есть пользователь с такими данными!');break;
     case'ok':if(!$.isEmptyObject(resp.opt)){_opt=resp.opt;__clear();__show();scrll('administrator_control');}break;
     default:console.log(resp);
    }
    _cancel_btn.show();
    _done_btn.attr('disabled',false).html(done_btn_text);
   },
   error:function(){
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 };
 ////////////////////////////////отобразить превью
 var __show=function(){
  if($.isEmptyObject(_opt)){return false;}
  _prev.empty();//очистить превью
  for(var k in _opt){//заполнять превью
   var edit_btn=$('<div/>',{class:'buc_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
       prev_str_box=$('<div/>',{class:'buc_prev_str_box',html:_opt[k].email}),
       control_box=$('<div/>',{class:'buc_prev_control',html:edit_btn}),
       prev_item=$('<div/>',{class:'buc_prev_item',html:[prev_str_box,control_box]});
   edit_btn.on('click.BUC',function(){__get_edit_form($(this).data('id'));scrll('administrator_control');});
   _prev.prepend(prev_item);
  }
 };
////////////////////////////////события
 _cancel_btn.on('click.BUC',function(){__clear();scrll('administrator_control');});//скрыть, очистить блок полей
////////////////////////////////после загрузки модуля
 __show();
}(jQuery));
</script>
<?php }}

if(!function_exists('moderators_control')){//управление модераторами системы
function moderators_control(){
$CI=&get_instance();
$q=$CI->db->where('status','moderator')->get('back_users')->result_array();
if(empty($q)){$m='{}';}else{foreach($q as $v){$m[$v['id']]=$v;}$m=json_encode($m,JSON_FORCE_OBJECT);}
unset($q);
?>
<!--####### Настройки модераторов #######-->
<div class="touch" id="moderators_control">
 <h3>Настройки модераторов</h3>
 <hr>
 <button type="button" class="add_buc_btn">Добавить модератора</button>
 <div class="buc_box" style="display:none">
  <div class="buc_info"></div>
  <div class="row">
   <div class="col6">
    Логин <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin</pre>
    <label class="input">
     <input type="text" class="buc_login">
    </label>
    Пароль <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генерируйте его!</b></pre>
    <a href="#" onclick="gen_pass('mc_pass');return false" class="fa-refresh" title="Генерировать пароль"></a>
    <label class="input">
     <input type="text" class="buc_pass" id="mc_pass">
    </label>
   </div>
   <div class="col6">
    E-mail <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
    <label class="input">
     <input type="text" class="buc_mail">
    </label>
    Доступ <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Разрешить или запретить вход в
административную часть системы и
все действия от имени модератора
в пользовательской части системы.</pre>
    <label class="select">
     <select class="buc_access">
      <option value="on">Разрешен</option>
      <option value="off">Запрещен</option>
     </select>
    </label>
   </div>
  </div>
  <div class="button">
   <button type="button" class="buc_done_btn">Готово</button><button type="button" class="buc_cancel_btn">Отмена</button>
  </div>
 </div>
 <div class="buc_prev"></div>
</div>

<script>
;(function($){
 var _=$('#moderators_control'),//блок настроек
     _add_btn=_.find('.add_buc_btn'),//кнопка "добавить модератора"
     _box=_.find('.buc_box'),//блок полей "добавить\редактировать"
     _info=_.find('.buc_info'),//блок информации
     _login=_.find('.buc_login'),//поле "логин"
     _pass=_.find('.buc_pass'),//поле "пароль"
     _mail=_.find('.buc_mail'),//поле "email"
     _access=_.find('.buc_access'),//поле "доступ"
     _done_btn=_.find('.buc_done_btn'),//кнопка "готово"
     _cancel_btn=_.find('.buc_cancel_btn'),//кнопка "отмена"
     _prev=_.find('.buc_prev'),//блок превью
     _opt=<?=$m?>;//объект модераторов
 ////////////////////////////////валидация полей
 var __validator=function(id){
  //валидация логина и пароля если модератор добавляется
  if(typeof id==="undefined"){
   if(!/\S/.test(_login.val())){alert('Поле "Логин" не заполнено!');return false;}
   if(!/\S/.test(_pass.val())){alert('Поле "Пароль" не заполнено!');return false;}
  }
  //валидация поля "E-mail"
  _mail.val($.trim(_mail.val()));
  if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(_mail.val())){
   alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');return false;
  }
  //проверка пройдена успешно
  return true;
 };
 ////////////////////////////////открыть форму добавления
 var __get_add_form=function(){
  __clear();
  _done_btn.on('click.BUC',function(){__add();});//событие на "готово" - добавить
  _box.slideDown(200);//открыть блок полей
 };
 ////////////////////////////////открыть форму редактирования
 var __get_edit_form=function(id){
  var last_mod=_opt[id].last_mod_date||'не изменялся',
      last_login=_opt[id].last_login_date?_opt[id].last_login_date+' с IP '+_opt[id].ip:'не входил';
  __clear();
  _done_btn.on('click.BUC',function(){__edit(id);});//событие на "готово" - редактировать
  _info.html('<p class="notific_b mini full">Дата создания: '+_opt[id].register_date+'<br>Дата изменения: '+last_mod+'<br>Дата последнего входа: '+last_login+'</p>');//информация о модераторе
  _login.attr('placeholder','Оставьте пустым если не хотите менять логин');
  _pass.attr('placeholder','Оставьте пустым если не хотите менять пароль');
  _mail.val(_opt[id].email);
  _access.find('option[value="'+_opt[id].access+'"]').attr('selected',true);
  _box.slideDown(200);//открыть блок полей
 };
 ////////////////////////////////скрыть, очистить блок полей
 var __clear=function(){
  _done_btn.off();//удалить все события у кнопки "готово"
  _info.empty();
  _access.find('option').removeAttr('selected');
  _box.slideUp(200).find('input').removeAttr('placeholder').val('');//очистить поля, скрыть блок
 };
 ////////////////////////////////добавить
 var __add=function(){
  if(!__validator()){return false;}
  var done_btn_text=_done_btn.text();
  _cancel_btn.hide();
  _done_btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');
  $.ajax({
   url:'/admin/setting/add_moderator',
   type:'post',
   data:{login:_login.val(),password:_pass.val(),email:_mail.val(),access:_access.val()},
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case'error':alert('Ой! Ошибка..(\nВозможно это временные неполадки, попробуйте снова.');break;
     case'nomail':alert('E-mail некорректный!\nИзмените и попробуйте снова.');break;
     case'nounq':alert('В системе уже есть пользователь с такими данными!');break;
     case'ok':if(!$.isEmptyObject(resp.opt)){_opt=resp.opt;__clear();__show();scrll('moderators_control');}break;
     default:console.log(resp);
    }
    _cancel_btn.show();
    _done_btn.attr('disabled',false).html(done_btn_text);
   },
   error:function(){
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 };
 ////////////////////////////////редактировать
 var __edit=function(id){
  if(!__validator(id)){return false;}
  var done_btn_text=_done_btn.text();
  _cancel_btn.hide();
  _done_btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');
  $.ajax({
   url:'/admin/setting/edit_moderator/'+id,
   type:'post',
   data:{login:_login.val(),password:_pass.val(),email:_mail.val(),access:_access.val()},
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case'error':alert('Ой! Ошибка..(\nВозможно это временные неполадки, попробуйте снова.');break;
     case'nomail':alert('E-mail некорректный!\nИзмените и попробуйте снова.');break;
     case'nounq':alert('В системе уже есть пользователь с такими данными!');break;
     case'ok':if(!$.isEmptyObject(resp.opt)){_opt=resp.opt;__clear();__show();scrll('moderators_control');}break;
     default:console.log(resp);
    }
    _cancel_btn.show();
    _done_btn.attr('disabled',false).html(done_btn_text);
   },
   error:function(){
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 };
 ////////////////////////////////удалить
 var __del=function(id){
  $.ajax({
   url:'/admin/setting/del_moderator',
   type:'post',
   data:{id:id},
   dataType:'text',
   success:function(resp){
    switch(resp){
     case'error':alert('Ой! Ошибка..(\nВозможно это временные неполадки, попробуйте снова.');break;
     case'last':alert('Вы пытаетесь удалить единственного модератора!\nВ системе должен быть один или больше модераторов.');break;
     case'ok':delete _opt[id];__show();break;
     default:console.log(resp);
    }
   },
   error:function(){
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 };
 ////////////////////////////////отобразить превью
 var __show=function(){
  if($.isEmptyObject(_opt)){return false;}
  _prev.empty();//очистить превью
  for(var k in _opt){//заполнять превью
   var edit_btn=$('<div/>',{class:'buc_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
       del_btn=$('<div/>',{class:'buc_prev_del_btn fa-trash-o',title:'Удалить'}).data('id',k),
       prev_str_box=$('<div/>',{class:'buc_prev_str_box '+(_opt[k].access==='off'?'red':''),html:_opt[k].email}),
       control_box=$('<div/>',{class:'buc_prev_control',html:[edit_btn,del_btn]}),
       prev_item=$('<div/>',{class:'buc_prev_item',html:[prev_str_box,control_box]});
   edit_btn.on('click.BUC',function(){__get_edit_form($(this).data('id'));scrll('moderators_control');});
   del_btn.on('click.BUC',function(){if(confirm('Этот модератор будет удален!\nВыполнить действие?'))__del($(this).data('id'));});
   _prev.prepend(prev_item);
  }
 };
////////////////////////////////события
 _add_btn.on('click.BUC',function(){__get_add_form();});//открыть блок полей
 _cancel_btn.on('click.BUC',function(){__clear();scrll('moderators_control');});//скрыть, очистить блок полей
////////////////////////////////после загрузки модуля
 __show();
}(jQuery));
</script>
<?php }}