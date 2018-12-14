<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('gallery')){
function gallery($type='',$opt=''){//Тип галереи и добавление мультимедиа
?>
<h3>Тип галереи и добавление мультимедиа</h3>
<hr>
Тип галереи <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
Выберите, какое мультимедиа вы хотите
отображать в галерее.</pre>
<label class="select">
 <select name="gallery_type" id="g_type">
  <option value="foto_folder" <?=$type=='foto_folder'?'selected':''?>>Папка с изображениями</option>
  <option value="foto_desc" <?=$type=='foto_desc'?'selected':''?>>Изображения с описанием</option>
  <option value="video_yt" <?=$type=='video_yt'?'selected':''?>>Видео c YouTube</option>
  <option value="audio" <?=$type=='audio'?'selected':''?>>Аудио</option>
 </select>
</label>

<!--Папка с изображениями-->
<div class="type_section" data-type="foto_folder">
 Путь к папке <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Нажмите на значок папки и выберите
папку с изображениями в менеджере файлов.
Все изображения в выбранной папке
будут отображены в этой галерее.</pre><br>
 <label class="input inline width90">
  <input type="text" id="g_f_folder_url" class="g_field">
 </label>
 <a href="#" class="fa-folder-open fa-lg blue" onclick="files('g_f_folder_url',{no_host:true});return false"></a>
</div>

<!--Изображения с описанием-->
<div class="type_section" data-type="foto_desc">
 <div class="row">
  <div class="col3">
   Порядок <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Порядковый номер изображения в галерее.</pre>
   <label class="input">
    <input type="number" class="order" min="1">
   </label>
  </div>
  <div class="col9">
   Путь к изображению <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Нажмите на значок папки и выберите
файл изображения в менеджере файлов.
Можно указать абсолютный путь к изображению,
который доступен из Интернета.</pre><br>
   <label class="input inline width70">
    <input type="text" id="g_f_url" class="g_field">
   </label>
   <a href="#" class="fa-folder-open fa-lg blue" onclick="files('g_f_url');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'g_f_url')"></i>
   <pre class="tt"></pre>
  </div>
 </div>
 Заголовок
 <label class="input">
  <input type="text" class="g_field" id="g_f_title" onkeyup="lim(this,75)" placeholder="Заголовок описания изображения">
 </label>
 Описание
 <label class="textarea">
  <textarea class="no-emmet g_field" id="g_f_desc" rows="4" onkeyup="lim(this,1000)" placeholder="Описание изображения"></textarea>
 </label>
 <div class="g_control">
  <button type="button" class="g_add_btn">Добавить изображение</button>
 </div>
 <!--Превью для изображений-->
 <div class="g_preview"></div>
</div>

<!--Видео c YouTube-->
<div class="type_section" data-type="video_yt">
 <div class="row">
  <div class="col3">
   Порядок <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Порядковый номер видео в галерее.</pre>
   <label class="input">
    <input type="number" class="order" min="1">
   </label>
  </div>
  <div class="col9">
   Путь к видео YouTube <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Ссылка на видео из YouTube.</pre>
   <label class="input">
    <input type="text" id="g_v_yt_url" class="g_field" placeholder="Пример: https://www.youtube.com/watch?v=xLQ6OCT5XUU">
   </label>
  </div>
 </div>
 <div class="g_control">
  <button type="button" class="g_add_btn">Добавить видео</button>
 </div>
 <!--Превью для видео-->
 <div class="g_preview"></div>
</div>

<!--Аудио-->
<div class="type_section" data-type="audio">
 <div class="row">
  <div class="col3">
   Порядок <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Порядковый номер аудио трека в галерее.</pre>
   <label class="input">
    <input type="number" class="order" min="1">
   </label>
  </div>
  <div class="col9">
   Путь к треку <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Нажмите на значок папки и выберите
файл трека в менеджере файлов.
Можно указать абсолютный путь к треку,
который доступен из Интернета.
ВНИМАНИЕ!
Чтобы ваш трек можно было прослушать
в разных браузерах, сохраните его
в форматах .mp3, .ogg, .wav с одинаковым
именем файла. Все три файла должны находиться
в одной папке. Если браузер не поддерживает
один из форматов — он загружает другой</pre><br>
   <label class="input inline width80">
    <input type="text" id="g_a_url" class="g_field">
   </label>
   <a href="#" class="fa-folder-open fa-lg blue" onclick="files('g_a_url');return false"></a>
  </div>
 </div>
 Название трека
 <label class="input">
  <input type="text" id="g_a_title" class="g_field" onkeyup="lim(this,75)" placeholder="Пример: Лунная соната">
 </label>
 <div class="g_control">
  <button type="button" class="g_add_btn">Добавить трек</button>
 </div>
 <!--Превью для аудио-->
 <div class="g_preview"></div>
</div>
<textarea id="g_opt" class="no-emmet" name="gallery_opt" hidden><?=$opt?></textarea>

<script>
;(function($){
 /////////////////////////////////по умолчанию
 var _type=$('#g_type').val()||'foto_folder',//тип галереи
     _section=$('[data-type='+_type+']'),//секция типа галереи
     _g_opt=$('#g_opt'),//textarea с опциями галереи
     _opt=!_g_opt.val()?{}:JSON.parse(_g_opt.val());//объект опций галереи

 /////////////////////////////////валидатор формы
 var _validator=function(id){
  var opt_count=Object.keys(_opt).length,//всего в галерее
      order=_section.find('.order');//поле "порядок"
  if(order[0]){//поле "порядок" найдено - валидация
   if(!/^[1-9]\d*$/.test(order.val())){
    alert('Поле "Порядок" не заполнено или заполнено некорректно!\nТолько целое число больше нуля!');return false;
   }
   if(typeof id!=="undefined" && parseInt(order.val(),10)>opt_count){
    alert('Поле "Порядок" заполнено некорректно!\nТолько целое число в пределах 1-'+opt_count+'!');return false;
   }
  }
  switch(_type){
   case 'foto_folder'://валидация полей типа "Папка с изображениями"
    var folder_url=$('#g_f_folder_url').val();//поле "путь к папке"
    if(!/\S/.test(folder_url)){alert('Выберите папку!');return false;}
   break;
   case 'foto_desc'://валидация полей типа "Изображения с описанием"
    var f_url=$('#g_f_url');//поле "путь к изображению"
    if(!/^(https?:\/\/)[\(\)\s\w\.\/-]+\.(png|jpg|jpeg|gif|webp|svg)$/i.test(f_url.val())){alert('Выберите изображение!');return false;}
   break;
   case 'video_yt'://валидация полей типа "Видео c YouTube"
    var yt_url=$('#g_v_yt_url');//поле "путь к видео YouTube"
    if(!/\S/.test(yt_url.val())){alert('Заполните поле "Путь к видео YouTube"!');return false;}
   break;
   case 'audio'://валидация полей типа "Аудио"
    var a_url=$('#g_a_url').val();//поле "путь к треку"
    if(!/^(https?:\/\/)[\(\)\s\w\.\/-]+\.(mp3|wav|ogg)$/i.test(a_url)){alert('Выберите аудио файл!');return false;}
   break;
  }
  return true;//проверка пройдена успешно
 };
 /////////////////////////////////открыть форму типа на добавление
 var _get_add_form=function(type){
  var s=$('[data-type='+type+']');//секция типа
  $('.type_section').hide();//скрыть формы всех типов
  s.slideDown(200);//открыть форму типа
  _type=type;//записать тип в глобальную переменную
  _section=s;//записать секцию в глобальную переменную
  _clear();//очистить и подготовить форму типа к добавлению
 };
 /////////////////////////////////открыть форму типа на редактирование
 var _get_edit_form=function(id){
  var control=_section.find('.g_control'),//блок кнопок секции: "добавть", "редактировать", "удалить все"...
      edit_btn=$('<button/>',{class:'g_edit_btn',type:'button',text:'Редактировать мультимедиа'}).on('click.G',function(){_edit(id);}),
      cancel_btn=$('<button/>',{class:'g_cancel_btn',type:'button',text:'Отмена'}).on('click.G',function(){_clear();});
  _section.find('.order').val(id).attr({'readonly':false,'max':Object.keys(_opt).length});//задать порядковый номер и разблокировать поле
  control.html([edit_btn,cancel_btn]);//добавить кнопки "редактировать", "отмена" к форме
  switch(_type){//заполняю поля типа
   case 'foto_folder'://///////////////////////////////////////////
    $('#g_f_folder_url').val(_opt.f_folder);
   break;
   case 'foto_desc'://///////////////////////////////////////////
    $('#g_f_url').val(_opt[id].f_url);
    $('#g_f_title').val(_opt[id].f_title);
    $('#g_f_desc').val(_opt[id].f_desc);
   break;
   case 'video_yt'://///////////////////////////////////////////
    $('#g_v_yt_url').val(_opt[id].yt_url);
   break;
   case 'audio'://///////////////////////////////////////////
    $('#g_a_url').val(_opt[id].a_url);
    $('#g_a_title').val(_opt[id].a_title);
   break;
  }
 };
 ////////////////////////////////получить id ролика youtube
 var _get_yt_id=function(url){
  var match=url.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
  if(match && match[7].length===11){return match[7];}else{alert('Некорректный путь к видео:\n'+url);return false;}
 };
 ////////////////////////////////добавить
 var _add=function(){
  if(!_validator()){return false;}//проверка полей
  var order=_section.find('.order');
  switch(_type){
   case 'foto_folder'://///////////////////////////////////////////
    _opt={f_folder:$('#g_f_folder_url').val()};
   break;
   case 'foto_desc'://///////////////////////////////////////////
    var f_url=$('#g_f_url'),
        f_title=$('#g_f_title'),
        f_desc=$('#g_f_desc');
    _opt[order.val()]={f_url:f_url.val(),f_title:f_title.val(),f_desc:f_desc.val()};
    _clear();
   break;
   case 'video_yt'://///////////////////////////////////////////
    var yt_url=$('#g_v_yt_url'),
        yt_id=_get_yt_id(yt_url.val());
    if(yt_id){_opt[order.val()]={yt_url:yt_url.val(),yt_id:yt_id};}else{return false;}
    _clear();
   break;
   case 'audio'://///////////////////////////////////////////
    var a_url=$('#g_a_url').val(),
        a_title=!$('#g_a_title').val()?a_url.split('/').pop().replace(/\.(mp3|wav|ogg)$/i,''):$('#g_a_title').val();
    _opt[order.val()]={a_url:a_url,a_ext:a_url.split('.').pop(),a_file:a_url.replace(/\.(mp3|wav|ogg)$/i,''),a_title:a_title};
    _clear();
   break;
  }
  _g_opt.val(JSON.stringify(_opt));
  _show();
 };
 ////////////////////////////////редактировать
 var _edit=function(id){
  if(!_validator(id)){return false;}//проверка полей
  var order=_section.find('.order').val(),//порядок
  int_order=parseInt(order,10),//новый порядковый номер в число
  int_id=parseInt(id,10),//текущий порядковый номер в число
  temp={};//если изменится порядковый номер - будет хранить объект с новым порядком
  Number.prototype.between=function(a,b){var min=Math.min(a,b),max=Math.max(a,b);return this>=min&&this<=max;};//проверка числа в диапазоне
  if(order!==id){//порядковый номер изменился
   for(var k in _opt){//проход
    var int_k=parseInt(k,10);//текущий порядковый номер (ключ) в число
    if(int_k.between(int_id,int_order)){//текущий ключ входит в диапазон
     if(int_k===int_id){//текущий ключ и текущий порядковый номер совпали
      switch(_type){//записать измененный элемент и назначить новый порядковый номер
       case 'foto_desc'://///////////////////////////////////////////
        var f_url=$('#g_f_url'),
            f_title=$('#g_f_title'),
            f_desc=$('#g_f_desc');
        temp[order]={f_url:f_url.val(),f_title:f_title.val(),f_desc:f_desc.val()};
       break;
       case 'video_yt'://///////////////////////////////////////////
        var yt_url=$('#g_v_yt_url'),
            yt_id=_get_yt_id(yt_url.val());
        if(yt_id){temp[order]={yt_url:yt_url.val(),yt_id:yt_id};}else{return false;}
       break;
       case 'audio'://///////////////////////////////////////////
        var a_url=$('#g_a_url').val(),
            a_title=!$('#g_a_title').val()?a_url.split('/').pop().replace(/\.(mp3|wav|ogg)$/i,''):$('#g_a_title').val();
        temp[order]={a_url:a_url,a_ext:a_url.split('.').pop(),a_file:a_url.replace(/\.(mp3|wav|ogg)$/i,''),a_title:a_title};
       break;
      }
     }else{//не совпали - увеличить или уменьшить номер в диапазоне чтобы сдвинуть
      int_id>int_order?temp[int_k+1]=_opt[k]:temp[int_k-1]=_opt[k];
     }
    }else{//текущий ключ не входит в диапазон - оставить как есть
     temp[k]=_opt[k];
    }
   }//проход закончен
   _opt=temp;
   _g_opt.val(JSON.stringify(_opt));
   _clear();
   _show();
   return true;
  }//порядковый номер не изменился
  _add();
 };
 ////////////////////////////////очистить поля формы типа и подготовить к добавлению
 var _clear=function(){
  var order=Object.keys(_opt).length+1,//следующий порядковый номер элемента
      control=_section.find('.g_control'),//блок кнопок секции: "добавть", "редактировать", "удалить все"...
      add_btn=$('<button/>',{class:'g_add_btn',type:'button',text:'Добавить мультимедиа'}).on('click.G',function(){_add();}),
      clear_btn=$('<button/>',{class:'g_clear_btn',type:'button',text:'Удалить все'}).on('click.G',function(){if(confirm('Все мультимедиа будут удалены!\nВыполнить действие?'))_del_all();});
  _section.find('.g_field').val('');//очистить поля формы типа
  _section.find('.order').val(order).attr({'readonly':true,'max':order});//задать порядковый номер и заблокировать поле
  if($.isEmptyObject(_opt)){control.html(add_btn);}else{control.html([add_btn,clear_btn]);}//добавить кнопк(у|и)
 };
 ////////////////////////////////удалить элемент
 var _del=function(id){
  var int_id=parseInt(id,10);
  for(var k in _opt){
    var int_k=parseInt(k,10);
    int_k>int_id?_opt[int_k-1]=_opt[k]:true;
    int_k===Object.keys(_opt).length?delete _opt[k]:true;
  }
  if($.isEmptyObject(_opt)){_del_all();}else{_g_opt.val(JSON.stringify(_opt));_clear();_show();}
 };
 ////////////////////////////////удалить все элементы
 var _del_all=function(){
  _opt={};
  _g_opt.val('');
  $('.g_preview').empty();
  _clear();
 };
 ////////////////////////////////отобразить превью
 var _show=function(){
  if($.isEmptyObject(_opt)){return false;}
  var g_preview=_section.find('.g_preview').empty();
  switch(_type){
   case 'foto_folder'://///////////////////////////////////////////
    $('#g_f_folder_url').val(_opt.f_folder);
   break;
   case 'foto_desc'://///////////////////////////////////////////
    for(var k in _opt){
     var info=_opt[k].f_title||_opt[k].f_desc?$('<span/>',{class:'g_prev_info fa-info-circle'}):$(),
         desc=$('<div/>',{class:'tt',html:'<h3>'+_opt[k].f_title+'</h3>'+_opt[k].f_desc}),
         edit_btn=$('<span/>',{class:'g_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
         del_btn=$('<span/>',{class:' g_prev_del_btn fa-trash-o',title:'Удалить'}).data('id',k),
         control=$('<span/>',{class:'g_prev_control',html:[info,edit_btn,del_btn]}),
         prev_item=$('<div/>',{class:'g_prev_item',css:{'background-image':'url('+_opt[k].f_url+')'},html:[control]});
     edit_btn.on('click.G',function(){_get_edit_form($(this).data('id'));});
     del_btn.on('click.G',function(){if(confirm('Этот элемент будет удален!\nВыполнить действие?'))_del($(this).data('id'));});
     info.after(desc).on('mouseover.G',function(){tt(this);});
     g_preview.append(prev_item);
    }
   break;
   case 'video_yt'://///////////////////////////////////////////
    for(var k in _opt){
     var edit_btn=$('<span/>',{class:'g_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
         del_btn=$('<span/>',{class:' g_prev_del_btn fa-trash-o',title:'Удалить'}).data('id',k),
         control=$('<span/>',{class:'g_prev_control',html:[edit_btn,del_btn]}),
         prev_item=$('<div/>',{class:'g_prev_item',css:{'background-image':'url(https://img.youtube.com/vi/'+_opt[k].yt_id+'/mqdefault.jpg)'},html:[control]});
     edit_btn.on('click.G',function(){_get_edit_form($(this).data('id'));});
     del_btn.on('click.G',function(){if(confirm('Этот элемент будет удален!\nВыполнить действие?'))_del($(this).data('id'));});
     g_preview.append(prev_item);
    }
   break;
   case 'audio'://///////////////////////////////////////////
    $.each(_opt,function(k){
     var audio=$('<audio/>',{src:_opt[k].a_url}),
         play=$('<i/>',{class:'fa-play'}),
         pause=$('<i/>',{class:'fa-pause'}),
         title=$('<span/>',{text:_opt[k].a_title.length>100?_opt[k].a_title.substring(0,100)+'...':_opt[k].a_title}),
         player=$('<div/>',{class:'audio_prev_player',html:[play,pause,title]}),
         edit_btn=$('<span/>',{class:'g_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
         del_btn=$('<span/>',{class:' g_prev_del_btn fa-trash-o',title:'Удалить'}).data('id',k),
         control=$('<span/>',{class:'g_prev_control',html:[edit_btn,del_btn]}),
         prev_item=$('<div/>',{class:'g_prev_item audio_prev_item',html:[player,control]});
     edit_btn.on('click.G',function(){_get_edit_form($(this).data('id'));});
     del_btn.on('click.G',function(){if(confirm('Этот элемент будет удален!\nВыполнить действие?'))_del($(this).data('id'));});
     audio.on('error.G',function(){title.before('<span class="red">[FILE ERROR!] </span>');});
     play.on('click.G',function(){audio[0].play();if(!audio[0].paused){play.addClass('green');}});
     pause.on('click.G',function(){audio[0].pause();if(audio[0].paused){play.removeClass('green');}});
     g_preview.append(prev_item);
    });
   break;
  }
 };

 //////////////////////////////////////////////////////////
 //события
 //////////////////////////////////////////////////////////
 //////////////////////////////////////выбор типа галереи
 $('#g_type').on('change.G',function(){
  if(!_g_opt.val()){
   _get_add_form($(this).val());
  }else{
   if(!confirm('Мультимедиа в галерее могут быть только одного типа.\nИзменить тип галереи и удалить добавленные мультимедиа?')){
    $(this).html($(this).html());
    return false;
   }else{
    _del_all();
    _get_add_form($(this).val());
   }
  }
 });
 //////////////////////////////////////добавить "папка с изображениями"
 $('#g_f_folder_url').on('change.G',function(){_add();});

 //////////////////////////////////////////////////////////
 //после загрузки модуля
 //////////////////////////////////////////////////////////
 _get_add_form(_type);//открыть форму типа на добавление
 _show();//показать превью
}(jQuery));
</script>
<?php }}