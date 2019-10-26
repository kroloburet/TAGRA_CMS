<h1><?=$data['view_title']?></h1>
<div class="sheath">
 <form id="l_r_form" method="POST" action="/admin/language/edit">
  <input type="hidden" name="id" value="<?=$data['id']?>">

  <!--####### Основное #######-->
  <div class="touch">
   <h3>Основное</h3>
   <hr>
   Название языка <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Название будет отображено для переключения
языков в пользовательской части ресурса.
Используйте короткое и уникальное название
чтобы не запутать пользователей и не
запутаться самому.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="input">
    <input type="text" name="title" placeholder="Пример: UA" onkeyup="lim(this,20)" onchange="check_title(this,<?=$data['id']?>,'languages','Язык с таким названием уже существует!\nИзмените название и продолжайте.')" value="<?=htmlspecialchars($data['title'])?>">
   </label>

   <?php if($data['def']!=='on'){?>
    <label class="checkbox inline" style="margin:0">
     <input type="checkbox" name="def" value="on">
     <span class="custom-checkbox"><i class="icon-check"></i></span>
     Язык по умолчанию
    </label> <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Будет предложен по умолчанию посетителю
пользовательской части ресурса если его
язык не определен или не добавлен.</pre>
   <?php }?>
  </div>

  <!--####### Файлы локализации #######-->
  <div class="touch">
   <h3 class="float_l">Файлы локализации</h3> <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Чтобы текст элементов интерфейса пользовательской
части ресурса (кнопки, подсказки, поля формы, карты
и прочее) соответствовал языку материала, для
каждого добавленного вами языка предусмотрен один
или несколько файлов локализации. Выберите в
списке ниже и отредактируйте все файлы согласно
текущему языку.</pre>
   <hr>
   <label class="select">
    <select id="l_r_list">
     <option value="">Выберите файл для редактирования</option>
     <?php foreach(glob(APPPATH."language/{$data['tag']}/*.php") as $v){?>
      <option value="<?=$v?>"><?=strtoupper(basename($v,'.php'))?></option>
     <?php }?>
    </select>
   </label>
   <div id="l_r_msg"></div>

   <div id="l_r">
    <a href="#" onclick="opn_cls('l_r_info');return false">Справка по редактированию <i class="fa-angle-down"></i></a>
    <div id="l_r_info" class="opn_cls notific_b">
     <h3>Обратите внимание</h3>
     <hr>
     <p>Файлы локализации написаны на скриптовом языке PHP. Данные в них представлены в многомерном массиве. Если вы не знакомы с таким представлением данных, внимательно изучите эту справку. Если у вас возникли трудности, или после редактирования возникли ошибки, или локализация работает не корректно &mdash; обратитесь к разработчику системы.</p>
     <h3>Структура</h3>
     <hr>
     <ul>
      <li><b>Файл должен начинаться с тега</b> <mark>&lt;?php</mark>. Перед ним допускаются только пробелы и пустые строки.</li>
      <li>Файл содержит данные в виде <mark>'ключ'=>'значение'</mark>.</li>
      <li><b>Редактируйте только значения ключей!</b></li>
      <li>Данные поделены на группы заключенные в скобки <mark>[  ]</mark> и разделены запятыми.</li>
      <li>Например, в группе <mark>'basic'=>[...]</mark> ключ (языковая переменная используемая в шаблоне) 'home' указывает на значение 'Главная'.</li>
      <li>В пределах каждой группы ключи должны быть уникальны. Не добавляйте и не редактируйте ключи.</li>
     </ul>
     <h3>Синтаксис</h3>
     <hr>
     <ul>
      <li>Строки после <mark>//</mark> или <mark>#</mark> до переноса строки считаются комментариями и будут игнорированы.</li>
      <li>Каждая пара <mark>'ключ'=>'значение'</mark> должны начинаться с новой строки, отформатировннны отступами и <b>быть разделены между собой запятыми</b>.</li>
      <li><b>Ключ и значение обязательно записываются в одинарных или двойных кавычках, не в апострофах</b>.</li>
      <li>Если в строке значения нужна пара или одна кавычка, вам необходимо следить за тем, чтобы кавычка из внешней пары не попалась внутри. Пример: <mark>'ключ'=>'так <b class="red">'</b>не<b class="red">'</b> нужно'</mark>, <mark>'ключ'=>"тоже <b class="red">"</b>не<b class="red">"</b> верно"</mark>, <mark>'ключ'=>'не<b class="red">'</b>правильно'</mark>, <mark>'ключ'=>'а <b class="green">"</b>так<b class="green">"</b> верно'</mark>, <mark>'ключ'=>"пра<b class="green">'</b>вильно"</mark>.</li>
      <li>По возможности избегайте в значениях символ <mark>$</mark>. &mdash; Это специальный сомвол в PHP, обозначает объявление переменной, как например <mark>$lang</mark>.</li>
      <li>Нигде не используйте: <mark>&lt;?php</mark>, <mark>&lt;?</mark>, <mark>?&gt;</mark>. &mdash; Эти сочетания тоже специальные в PHP.</li>
      <li>Если в значении все же нужно использовать специальные символы или кавычки, которые будут конфликтовать с внешними, используйте перед каждым таким символом обратный слеш <mark>\</mark>. &mdash; Это называется экранированием. Символ <mark>\</mark> отменяет специальное значение одного символа после него, а сам он в итоге не будет выведен. Например: <mark>'ключ'=>'значение на \$100, it\'s good'</mark>. Результат: <mark>значение на $100, it's good</mark>. Ни в коем случае <b>не экранируйте ни одну из внешних кавычек</b> <mark>'ключ'=><b class="red">\</b>'нельзя<b class="red">\</b>'</mark>. Поскольку символ <mark>\</mark> тоже специальный, чтобы использовать его в значении как обычный символ, его нужно так же экранировать. Например: <mark>'ключ'=>'значение\\я'</mark>. Результат: <mark>значение\я</mark>.</li>
      <li>В некоторых значениях можно встретить <mark>%s</mark>. &mdash; Это специальные метки, вместо которых система подставит переменные или иные данные. Например: <mark>'ключ'=>'Сегодня %s, на часах %s'</mark>. Результат: <mark>Сегодня вторник, на часах 12:07</mark>. <b>Редактируя значения с такими метками учитывайте их смысловую значимость в строке и не удаляйте их.</b></li>
      <li>Если вы сомневаетесь, будет ли символ интерпритирован PHP как специальный, попробуйте найти этот символ в <a href="https://ru.wikipedia.org/wiki/%D0%9C%D0%BD%D0%B5%D0%BC%D0%BE%D0%BD%D0%B8%D0%BA%D0%B8_%D0%B2_HTML" target="_blank">таблице мнемоников</a> и записать его в виде кода.</li>
      <li>Переносы на новую строку в значении не будут ошибкой. Но по возможности, страрайтесь редактировать значения в одну строку.</li>
      <li>Если в значении нужно использовать HTML &mdash; используйте его, но следите за кавычками.</li>
     </ul>
     <a href="#" onclick="opn_cls('l_r_info');return false">Скрыть справку</a>
    </div>
    <label class="textarea">
     <div id="l_r_lines"></div>
     <textarea class="no-emmet"></textarea>
    </label>
   </div>
  </div>

  <div class="button this_form_control">
   <button type="button">Сохранить изменения</button><a href="/admin/language/get_list" class="btn_lnk">Отменить</a>
  </div>
 </form>
</div>

<script>
//////////////////////////////////////работа редактора файлов локализации и сохранение формы редактирования
 ;
 (function($){
  var _l_list=$('#l_r_list'),//список файлов локализации
  _l_redactor=$('#l_r'),//контейнер редактора
  _l_textarea=_l_redactor.find('textarea'),//поле редактирования
  _l_lines=_l_redactor.find('#l_r_lines'),//контейнер нумерации строк
  _l_file={};//будет содержать данные редактируемого файла
  ////////////////////////вывод сообщений
  var _l_msg=function(style,msg,time){
   var box=$('#l_r_msg'),
   html=$('<p/>',{class:'full mini '+style,html:msg});
   box.find('._loader').remove();
   box.append(html);
   if(time){
    setTimeout(function(){
     html.remove();
    },time);
   }else{
    html.addClass('_loader');
   }
   return html;
  };
  ////////////////////////проверка на содержание php контента
  var _is_php=function(text){
   return (!/\S\s*<\?php/i.test(text)&&/<\?php/i.test(text));
  };
  ////////////////////////номерация строк редактора
  var _liner=function(){
   var lines=_l_textarea.val().split('\n').length,
   events='scroll.LR keydown.LR paste.LR';
   _l_lines.empty();
   _l_textarea.off(events);
   for(var i=1;i<=lines;i++){
    _l_lines.append(i+'<br>');
   }
   _l_lines.append((i++)+'<br>');
   _l_textarea.on(events,function(e){
    e.type==='scroll'?_l_lines.scrollTop(this.scrollTop):true;
    e.keyCode===13?_l_lines.append((i++)+'<br>'):true;
    e.type==='paste'?setTimeout(_liner,100):true;
   });
  };
  ////////////////////////очистка
  var _clear=function(){
   _l_list.val('');
   _l_textarea.val('');
   _l_redactor.slideUp(200);
   _l_file={};
  };
  ////////////////////////получение контрольной суммы
  var _get_hash=function(str){
   var hash=0;
   for(var i=0;i<str.length;i++){
    hash=((hash<<5)-hash+str.charCodeAt(i))<<0;
   }
   return hash;
  };
  ////////////////////////выбор файла для редактирования
  var _choice_file=function(){
   var text=_l_textarea.val(),
   file=_l_list.val();
   if(_l_file.hash&&_get_hash(text)!==_l_file.hash&&confirm('Файл "'+_l_file.name+'" был изменен!\nСохранить изменения?')){
    if(!_save_file()){
     _l_list.val(_l_file.path);
     return false;
    }
   }
   !file?_clear():_get_file(file);
  };
  ///////////////////////загрузка файла
  var _get_file=function(path){
   var name=_l_list.find('option[value="'+path+'"]').text();
   _l_msg('notific_b','<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;загрузка файла <q>'+name+'</q>');
   $.ajax({
    url:'/admin/language/get_localization_file',
    type:'post',
    data:{path:path},
    dataType:'text',
    async:false,
    success:function(resp){
     if(_is_php(resp)){
      _l_textarea.val(resp);
      _liner();
      _l_redactor.slideDown(200);
      _l_file={name:name,path:path,hash:_get_hash(resp),text:resp};
      _l_msg('notific_g','Файл <q>'+name+'</q> готов к редактированию',3000);
     }else{
      _l_msg('notific_r','Ошибка! Не удалось загрузить файл <q>'+name+'</q>');
      _clear();
     }
    },
    error:function(){
     _l_msg('notific_r','Не удалось загрузить файл <q>'+name+'</q>.<br>Возникла ошибка соединения. Повторите попытку.');
    }
   });
  };
  ///////////////////////сохранение файла
  var _save_file=function(){
   if($.isEmptyObject(_l_file)){
    return false;
   }
   var result=false,
   revoke=$('<a/>',{class:'fa-mail-reply',text:' Отменить все изменения в этом файле'})
   .on('click.LR',function(){
    _l_textarea.val(_l_file.text);
    $(this).parent('p').remove();
    return false;
   });
   if(!_is_php(_l_textarea.val())){
    _l_msg('notific_r','Файл <q>'+_l_file.name+'</q> не сохранен!<br>Возможные ошибки:<br>В начале файла не найден обязательный тег <mark>&lt;?php</mark>;<br>Перед тегом <mark>&lt;?php</mark> присутствует текст;<br>Найдено более одного тега <mark>&lt;?php</mark>').append(revoke);
    return false;
   }
   _l_msg('notific_b','<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;сохранение файла <q>'+_l_file.name+'</q>');
   $.ajax({
    url:'/admin/language/save_localization_file',
    type:'post',
    data:{path:_l_file.path,text:_l_textarea.val()},
    dataType:'json',
    async:false,
    success:function(resp){
     switch(resp.status){
      case 'error':
       _l_msg('notific_r','Файл <q>'+_l_file.name+'</q> не сохранен!<br>Возможные ошибки:<br>'+resp.msg).append(revoke);
       break;
      case 'ok':
       _l_msg('notific_g','Файл <q>'+_l_file.name+'</q> успешно сохранен!',3000);
       result=true;
       break;
      default :
       console.log(resp);
     }
    },
    error:function(){
     _l_msg('notific_r','Файл <q>'+_l_file.name+'</q> не сохранен!<br>Возникла ошибка соединения. Повторите попытку.');
    }
   });
   return result;
  };
  ///////////////////////события
  _clear();//в начале работы установить список файлов в первую позицию
  _l_list.on('change.LR',_choice_file);//инициализация редактора, сохранение\загрузка файла
  $('.this_form_control button').on('click.LR',function(){//сохранение формы редактирования языка
   var self=$(this),
   html=self.html();
   if(_l_file.hash&&_get_hash(_l_textarea.val())!==_l_file.hash){
    self.html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;сохранение файла <q>'+_l_file.name+'</q>');
    if(!_save_file()){
     self.html('<p class="notific_r mini">Ошибка при сохранении файла <q>'+_l_file.name+'</q>!</p>');
     setTimeout(function(){
      self.html(html);
     },5000);
     return false;
    }
   }
   subm($('#l_r_form')[0],{title:/[^\s]/});
  });
 }(jQuery));
</script>