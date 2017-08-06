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
 <select name="type" id="g_type">
  <option value="foto_folder" <?=$type==='foto_folder'?'selected':''?>>Папка с изображениями</option>
  <option value="foto_desc" <?=$type==='foto_desc'?'selected':''?>>Изображения с описанием</option>
  <option value="video_yt" <?=$type==='video_yt'?'selected':''?>>Видео c YouTube</option>
  <option value="audio" <?=$type==='audio'?'selected':''?>>Аудио</option>
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
 Путь к изображению <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Нажмите на значок папки и выберите
файл изображения в менеджере файлов.
Можно указать абсолютный путь к изображению,
который доступен из Интернета.</pre><br>
 <label class="input inline width90">
  <input type="text" id="g_f_url" class="g_field">
 </label>
 <a href="#" class="fa-folder-open fa-lg blue" onclick="files('g_f_url');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'g_f_url')"></i>
 <pre class="tt"></pre><br>
 Заголовок
 <label class="input">
  <input type="text" class="width90 g_field" id="g_f_title" onkeyup="lim(this,75)" placeholder="Заголовок описания изображения">
 </label>
 Описание
 <label class="textarea">
  <textarea class="no-emmet width90 g_field" id="g_f_desc" rows="4" onkeyup="lim(this,1000)" placeholder="Описание изображения"></textarea>
 </label>
 <button type="button" id="add_img_opt_btn">Добавить изображение</button>
 <span class="g_preview_msg"></span><br>
 <!--Превью для изображений-->
 <div class="g_preview"></div>
</div>

<!--Видео c YouTube-->
<div class="type_section" data-type="video_yt">
 Ссылки YouTube <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Вставьте в поле одну или несколько ссылок на видео из YouTube.
Вы можете разместить неограниченное число ссылок.
По каждой ссылке будет загружаться видео в галерею.
<b>Внимание! Размещайте ссылки по одной в строке
и нажмите "Добавить видео". Ниже появяться
превью добавленных видео.</b></pre>
 <label class="textarea">
  <textarea id="g_v_yt_links" class="no-emmet g_field" rows="4" placeholder="Пример: https://www.youtube.com/watch?v=xLQ6OCT5XUU"></textarea>
 </label>
 <button type="button" id="add_video_yt_btn">Добавить видео</button>
 <span class="g_preview_msg"></span><br>
 <!--Превью для видео-->
 <div class="g_preview"></div>
</div>

<!--Аудио-->
<div class="type_section" data-type="audio">
 Путь к файлу <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
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
 <label class="input inline width90">
  <input type="text" id="g_a_url" class="g_field">
 </label>
 <a href="#" class="fa-folder-open fa-lg blue" onclick="files('g_a_url');return false"></a>
 Название трека
 <label class="input">
  <input type="text" id="g_a_title" class="width90 g_field" onkeyup="lim(this,75)" placeholder="Пример: Лунная соната">
 </label>
 <button type="button" id="add_audio_opt_btn">Добавить трек</button>
 <span class="g_preview_msg"></span><br>
 <!--Превью для аудио-->
 <div class="g_preview"></div>
</div>
<textarea id="g_opt" class="no-emmet" name="opt" hidden><?=$opt?></textarea>

<script>
;(function($){
 //////////////////////////////////////////////////////////
 //объявление приватных свойств по умолчанию
 //////////////////////////////////////////////////////////
 var _type=$('#g_type').val()||'foto_folder',//тип галереи
     _g_opt=$('#g_opt'),//textarea с опциями галереи
     _opt=!_g_opt.val()?{}:JSON.parse(_g_opt.val()),//объект опций галереи
     _section=$('[data-type='+_type+']');//секция типа галереи

 //////////////////////////////////////////////////////////
 //приватные методы
 //////////////////////////////////////////////////////////
 /////////////////////////////////открытие секции типа   
 var _open_section=function(type){
  var s=$('[data-type='+type+']');
  $('.type_section').hide();
  s.slideDown(200);
  _type=type;
  _section=s;
 };
 ////////////////////////////////получить уникальный id
 var _get_id=function(){return new Date().getTime().toString();};
 ////////////////////////////////получить id ролика youtube
 var _get_yt_id=function(url){
  var match=url.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
  if(match && match[7].length===11){
   return match[7];
  }else{
   alert('Некорректная ссылка:\n'+url);
   return false;
  }
 };
 ////////////////////////////////добавление опций типа
 var _add=function(type){
  switch(type){
   case 'foto_folder'://///////////////////////////////////////////
    var folder_url=$('#g_f_folder_url').val();
    if(/\S/.test(folder_url)){
     _opt={f_folder:folder_url};
     _g_opt.val(JSON.stringify(_opt));
    }else{
     _opt={};
     _g_opt.val('');
    }
   break;
   case 'foto_desc'://///////////////////////////////////////////
    var f_url=$('#g_f_url'),//поле "путь к изображению"
        f_title=$('#g_f_title'),//поле "заголовок"
        f_desc=$('#g_f_desc');//поле "описание"
    if(!/\S/.test(f_url.val())){alert('Выберите изображение!');return false;}
    _opt[_get_id()]={f_url:f_url.val(),f_title:f_title.val(),f_desc:f_desc.val()};
    _g_opt.val(JSON.stringify(_opt));
    _section.find('.g_field').val('');
    _show(type);
   break;
   case 'video_yt'://///////////////////////////////////////////
    var v_yt_links=$('#g_v_yt_links');//поле "ссылки YouTube"
    if(!/\S/.test(v_yt_links.val())){
     alert('Заполните поле "Ссылки YouTube" одной или несколькими ссылками на видео, по одной в строке!');
     return false;
    }
    var arr=$.trim(v_yt_links.val()).split(/\s+/),
        temp_opt={};
    for(var i=0;i<arr.length;i++){
     var yt_url=$.trim(arr[i]),
         yt_id=_get_yt_id(yt_url);
     if(yt_id){temp_opt[_get_id()+i]={yt_url:yt_url,yt_id:yt_id};}else{return false;}
    }
    _g_opt.val(JSON.stringify($.extend(_opt,temp_opt)));
    _section.find('.g_field').val('');
    _show(type);
   break;
   case 'audio'://///////////////////////////////////////////
    var a_url=$('#g_a_url').val(),
        a_title=$('#g_a_title').val()===''?a_url.replace(/\.(mp3|wav|ogg)$/,''):$('#g_a_title').val();
    if(!/^(https?:\/\/)[\(\)\s\w\.\/-]+\.(mp3|wav|ogg)$/.test(a_url)){alert('Выберите аудио файл!');return false;}
    _opt[_get_id()]={a_url:a_url,a_ext:a_url.replace(/(\s|\S)*[^\.(mp3|ogg|wav)$]/,''),a_file:a_url.replace(/\.(mp3|wav|ogg)$/,''),a_title:a_title};
    _g_opt.val(JSON.stringify(_opt));
    _section.find('.g_field').val('');
    _show(type);
   break;
  }
 };
 ////////////////////////////////отображение превью типа
 var _show=function(type){
  if($.isEmptyObject(_opt)){return false;}
  var clear_btn=$('<button/>',{type:'button',text:'Очистить все'}),
      preview_msg=_section.find('.g_preview_msg'),
      preview=_section.find('.g_preview').empty();
  switch(type){
   case 'foto_folder'://///////////////////////////////////////////
    $('#g_f_folder_url').val(_opt.f_folder);
   break;
   case 'foto_desc'://///////////////////////////////////////////
    var clear_msg=$('<span/>',{text:'Чтобы удалить изображение — кликните на нем в списке превью ниже.'});
    for(var k in _opt){
     var img=$('<img/>',{class:'img_prev_foto',id:k,src:_opt[k].f_url,title:'Удалить изображение и его описание'}),
         desc=$('<div/>',{class:'img_prev_foto_desc',html:'<h3>'+_opt[k].f_title+'</h3>'+_opt[k].f_desc});
     img.on('click.G',function(){if(confirm('Это изображение и его описание будет удалено!\nВыполнить действие?'))_del($(this)[0].id);});
     preview.prepend([img,desc]);
    }
    clear_btn.on('click.G',function(){if(confirm('Все изображения и их описания будут удалены!\nВыполнить действие?'))_clear();});
    preview_msg.html([clear_btn,clear_msg]);
   break;
   case 'video_yt'://///////////////////////////////////////////
    var clear_msg=$('<span/>',{text:'Чтобы удалить видео — кликните на нем в списке превью ниже.'});
    for(var k in _opt){
     var video=$('<img/>',{class:'img_prev_yt',id:k,src:'http://img.youtube.com/vi/'+_opt[k].yt_id+'/mqdefault.jpg',title:'Удалить видео'});
     video.on('click.G',function(){if(confirm('Это видео будет удалено!\nВыполнить действие?'))_del($(this)[0].id);});
     preview.prepend(video);
    }
    clear_btn.on('click.G',function(){if(confirm('Все видео будут удалены!\nВыполнить действие?'))_clear();});
    preview_msg.html([clear_btn,clear_msg]);
   break;
   case 'audio'://///////////////////////////////////////////
    var clear_msg=$('<span/>',{text:'Чтобы удалить трек — кликните на крестик в списке превью ниже.'});
    for(var k in _opt){
     var ogg=$('<source/>',{src:_opt[k].a_file+'.ogg',type:'audio/ogg'}),
         mp3=$('<source/>',{src:_opt[k].a_file+'.mp3',type:'audio/mpeg'}),
         wav=$('<source/>',{src:_opt[k].a_file+'.wav',type:'audio/wav'}),
         audio=$('<audio/>',{controls:true,html:[ogg,mp3,wav,'Ваш браузер не поддерживает тег audio']}),
         title=$('<div/>',{class:'audio_prev_title',text:_opt[k].a_title}),
         del_btn=$('<i/>',{id:k,class:'fa-times-circle',title:'Удалить трек'}),
         wraper=$('<div/>',{class:'audio_prev_wraper',html:[audio,del_btn]});
     del_btn.on('click.G',function(){if(confirm('Этот трек будет удален!\nВыполнить действие?'))_del($(this)[0].id);});
     preview.prepend([title,wraper]);
    }
    clear_btn.on('click.G',function(){if(confirm('Все треки будут удалены!\nВыполнить действие?'))_clear();});
    preview_msg.html([clear_btn,clear_msg]);
   break;
  }
 };
 ////////////////////////////////удаление елемента из опций
 var _del=function(id){
  delete _opt[id];
  if($.isEmptyObject(_opt)){
   _clear();
  }else{
   _g_opt.val(JSON.stringify(_opt));
   _show(_type);
  }
 };
 ////////////////////////////////очистка всех полей, превью и объекта опций
 var _clear=function(){
  _opt={};
  _g_opt.add('.g_field').val('');
  $('.g_preview_msg,.g_preview').empty();
 };
 
 //////////////////////////////////////////////////////////
 //события
 //////////////////////////////////////////////////////////
 //////////////////////////////////////выбор типа галереи
 $('#g_type').on('change.G',function(){
  if(_g_opt.val()===''){
   _open_section($(this).val());
  }else{
   if(!confirm('Мультимедиа в галерее могут быть только одного типа.\nИзменить тип галереи и удалить добавленные мультимедиа?')){
    $(this).html($(this).html());
    return false;
   }else{
    _clear();
    _open_section($(this).val());
   }
  }
 });
 //////////////////////////////////////добавить опции "папка с изображениями"
 $('#g_f_folder_url').on('change.G',function(){_add('foto_folder');});
 //////////////////////////////////////добавить опции "изображения с описанием"
 $('#add_img_opt_btn').on('click.G',function(){_add('foto_desc');});
 //////////////////////////////////////добавить опции "видео c YouTube"
 $('#add_video_yt_btn').on('click.G',function(){_add('video_yt');});
 //////////////////////////////////////добавить опции "аудио"
 $('#add_audio_opt_btn').on('click.G',function(){_add('audio');});
 
 //////////////////////////////////////////////////////////
 //после загрузки модуля
 //////////////////////////////////////////////////////////
 _open_section(_type);//открыть секцию типа
 _show(_type);//показать превью
}(jQuery));
</script>
<?php }}