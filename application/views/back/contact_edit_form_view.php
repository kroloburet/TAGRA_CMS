<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять.
?>
<h1><?=$conf_title?></h1>
<div class="container">
 <form method="POST" action="<?=base_url('admin/contact/edit')?>" onsubmit="subm(this,s_opts);return false">

  <!--####### Title, description... #######-->
  <div class="touch">
   Заголовок страницы <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Должен быть информативным и емким,
содержать ключевые слова.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="input">
    <input type="text" class="width90" name="title" placeholder="Заголовок страницы. Пример: Мой персональный веб-сайт" value="<?=$title?>" onkeyup="lim(this,100)">
   </label>
   Описание <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Краткое (до 250 символов) описание этой страницы
которое будет показано под заголовком (ссылкой)
в результатах поиска в Интернете
и разделах, подразделах сайта.
Должно быть информативным и емким,
содержать ключевые слова.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="textarea">
    <textarea name="description" class="no-emmet width90" placeholder="Описание страницы (description)" onkeyup="lim(this,250)" rows="3"><?=$description?></textarea>
   </label>
  </div>

  <!--####### JS, CSS, кнопки соцсетей... #######-->
  <div class="algn_r"><a href="#" onclick="opn_cls('more_opt');return false">Дополнительно (JS, CSS, кнопки соцсетей...) <i class="fa-angle-down"></i></a></div>
  <div id="more_opt" class="opn_cls touch">
   Для индексации поисковыми роботами
   <script>
    $(function(){
     $('select[name="robots"] option[value="<?=$robots?>"]').attr('selected',true);
    })
   </script>
   <label class="select">
    <select name="robots">
     <option value="all">страница полностью доступна</option>
     <option value="index">ТОЛЬКО индексировать страницу</option>
     <option value="follow">ТОЛЬКО проходить по ссылкам на странице</option>
     <option value="noindex">НЕ индексировать страницу</option>
     <option value="nofollow">НЕ проходить по ссылкам на странице</option>
     <option value="noimageindex">НЕ индексировать изображения на странице</option>
     <option value="none">страница полностью НЕ доступна</option>
    </select>
   </label>
   <div class="row">
    <div class="col6">
     CSS-код <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
     <pre class="tt">
CSS-код с тегами style
который будет применен к этой странице.
Можно подгружать внешние таблицы стилей.</pre>
     <label class="textarea">
      <textarea name="css" class="emmet-syntax-css" placeholder="CSS-код с тегами <style> и </style>" rows="6"><?=$css?></textarea>
     </label>
    </div>
    <div class="col6">
     JAVASCRIPT-код <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
     <pre class="tt">
JAVASCRIPT-код без тегов script
который будет применен к этой странице.
Можно подгружать внешние скрипты</pre>
     <label class="textarea">
      <textarea name="js" class="no-emmet" placeholder="JAVASCRIPT-код с тегами <script> и </script>" rows="6"><?=$js?></textarea>
     </label>
    </div>
   </div>
   <div class="row">
    <div class="col6">
     Кнопки «Share»
     <label class="select">
      <select name="addthis_share">
       <option value="off" <?php if($addthis_share=='off') echo'selected'?>>скрыть</option>
       <option value="on" <?php if($addthis_share=='on') echo'selected'?>>показать</option>
      </select>
     </label>
    </div>
    <div class="col6">
     Кнопки «Follow»
     <label class="select">
      <select name="addthis_follow">
       <option value="off" <?php if($addthis_follow=='off') echo'selected'?>>скрыть</option>
       <option value="on" <?php if($addthis_follow=='on') echo'selected'?>>показать</option>
      </select>
     </label>
    </div>
   </div>
  </div>

  <!--####### Контент #######-->
  <div class="touch">
   <h3>Контент</h3>
   <hr>
   <a href="#" onclick="$('#layout_t').removeClass('nav_layout_active');return false">Kомпактно <i class="fa-compress"></i></a>
   <div id="layout_t" class="nav_layout_t"><?=$layout_t?></div>
  </div>

  <!--####### Контакты #######-->
  <div class="touch">
   <h3>Контакты</h3>
   <hr>
   E-mail <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Если у Вас несколько почтовых ящиков,
укажите их по одному в поле. Добавлять
и удалять поля можно с помощью кнопок
справа от поля.</pre>
   <label class="input mail">
    <?php if(!$mail){?>
    <label class="input">
     <input type="text" name="mail[]" class="width90">
     <a href="#" onclick="add_mail(this);return false" class="fa-plus-circle fa-lg blue"></a>
    </label>
    <?php }else{
    $mails=explode(',',$mail);
    foreach($mails as $k=>$v){
     if($k==count($mails)-1){?>
     <label class="input">
      <input type="text" name="mail[]" class="width90" value="<?=$v?>">
      <a href="#" onclick="add_mail(this);return false" class="fa-plus-circle fa-lg blue"></a>
     </label>
    <?php }else{?>
     <label class="input">
      <input type="text" name="mail[]" class="width90" value="<?=$v?>">
      <a href="#" onclick="del_mail(this);return false" class="fa-times-circle fa-lg blue"></a>
     </label>
    <?php }}}?>
   </label>
   Телефон <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Если у Вас несколько номеров,
укажите их по одному в поле. Добавлять
и удалять поля можно с помощью кнопок
справа от поля. Допускаются форматы:
+xx(ххх)ххх хх хх, ххх-хх-хх,
ххххххх, (ххх)-ххх-хх-хх, ххх ххх хх хх</pre>
   <label class="input tel">
    <?php if(!$tel){?>
    <label class="input">
     <input type="text" name="tel[]" class="width90">
     <a href="#" onclick="add_tel(this);return false" class="fa-plus-circle fa-lg blue"></a>
    </label>
    <?php }else{
    $tels=explode(',',$tel);
    foreach($tels as $k=>$v){
     if($k==count($tels)-1){?>
     <label class="input">
      <input type="text" name="tel[]" class="width90" value="<?=$v?>">
      <a href="#" onclick="add_tel(this);return false" class="fa-plus-circle fa-lg blue"></a>
     </label>
    <?php }else{?>
     <label class="input">
      <input type="text" name="tel[]" class="width90" value="<?=$v?>">
      <a href="#" onclick="del_tel(this);return false" class="fa-times-circle fa-lg blue"></a>
     </label>
    <?php }}}?>
   </label>
   Skype
   <label class="input">
    <input type="text" name="skype" class="width90" value="<?=$skype?>">
   </label>
   QR-код <i class="fa fa-question-circle blue" onmouseover="tt(this,'c');"></i>
   <pre class="tt">
Абсолютный путь к QR-коду в Интернете
или на сайте.<hr>QR-код — графическая картинка с небольшим обьемом
зашифрованной информации. Создать QR-код
можно на <a href="http://www.qr-code.com.ua/" target="_blank">qr-code.com.ua</a>.
Например, зашифруйте ваши контактные данные,
их можно будет быстро сканировать и сохранять
в телефонной книге.</pre>
   <label class="input">
    <input type="text" id="qr" name="qr" class="width90" value="<?=$qr?>">&nbsp;
    <a href="#" class="fa-folder-open fa-lg blue" onclick="files('qr');return false"></a>
   </label>
  </div>
  
  <!--####### Адреса #######-->
  <div class="touch" id="addr">
   <h3 class="float_l">Адреса</h3> <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
Здесь вы можете указать один или
несколько адресов, назначить им
GPS-координаты и маркеры на карте</pre>
   <hr>
   <button type="button" class="add_addr_btn">Добавить адрес</button>
   <div class="addr_box" style="display:none">
    Адрес <i class="fa-question-circle red" onmouseover="tt(this);"></i>
    <pre class="tt"><b class="red">Обязательно для заполнения!</b></pre>
    <label class="input">
     <input class="addr" type="text" placeholder="Крещатик, 20-22, Киев">
    </label>
    GPS-координаты <i class="fa-question-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
GPS-координаты (широта,долгота).
Координаты можно получить перетащив
маркер на карте в нужное место, или
воспользоваться полем «Поиск координат».
Начните вводить в поле поиска адрес чтобы
вызвать варианты, выберите нужный вариант,
координаты и маркер будут установлены.
<b class="red">Обязательно для заполнения!</b></pre>
    <label class="input">
     <input class="gps" type="text" placeholder="50.450031,30.524205">
    </label>
    <input class="gps_search" type="text" placeholder="Поиск координат">
    <div class="addr_gmap"></div>
    <textarea class="marker_desc" type="text" placeholder="Текст для маркера (можно использовать HTML)"></textarea>
    <div class="button algn_r">
     <button type="button" class="addr_done_btn">Готово</button><button type="button" class="addr_cancel_btn">Отмена</button>
    </div>
   </div>
   <textarea name="address" hidden><?=$address?></textarea>
   <div class="addr_prev"></div>
  </div>

  <!--####### Форма обратной связи #######-->
  <div class="touch">
   <h3>Форма обратной связи</h3>
   <hr>
   <label class="select">
    <select name="contact_form">
     <option value="on" <?php if($contact_form=='on') echo'selected'?>>Показать форму обратной связи</option>
     <option value="off" <?php if($contact_form=='off') echo'selected'?>>Скрыть форму обратной связи</option>
    </select>
   </label>
  </div>

  <div class="button">
   <input type="submit" value="Сохранить изменения"><a href="#" class="btn_lnk" onclick="window.history.back();return false">Отменить</a>
  </div>
 </form>
</div>

<script>
///////////////////////рег.выражения для проверки полей
var s_opts={};
 s_opts['title']=/^[^><]+$/i;
 s_opts['description']=/^[^><]+$/i;
 s_opts['mail[]']=/^(([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6})?$/;
 s_opts['tel[]']=/^((\+\d{2,3})?[\s-]?\(?\d{0,3}\)?[\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2})?$/;
//////////////////////////////////////////////////////////работа полей "телефон" и "email"
///////////////////////добавление дополнительного поля "телефон"
function add_tel(el){
 var tel=$(el).parent().html();
 $(el).parent().append('<a href="#" onclick="del_tel(this);return false" class="fa-times-circle fa-lg blue"></a>');
 $(el).remove();
 $(".tel").append('<label class="input">'+tel+'</label>').find('input:last').val('').focus();
}
///////////////////////удаление поля "телефон"
function del_tel(el){
 $(el).parent().remove();
}
///////////////////////добавление дополнительного поля "email"
function add_mail(el){
 var mail=$(el).parent().html();
 $(el).parent().append('<a href="#" onclick="del_mail(this);return false" class="fa-times-circle fa-lg blue"></a>');
 $(el).remove();
 $(".mail").append('<label class="input">'+mail+'</label>').find('input:last').val('').focus();
}
///////////////////////удаление поля "email"
function del_mail(el){
 $(el).parent().remove();
}

//////////////////////////////////////////////////////////адреса
;(function($,google){
 //////////////////////////////////////////////////////////
 //объявление приватных свойств по умолчанию
 //////////////////////////////////////////////////////////
 var _a=$('#addr'),//блок адреса
     _a_addr=_a.find('.addr'),//поле "адрес"
     _a_gps=_a.find('.gps'),//поле "gps"
     _a_marker_desc=_a.find('.marker_desc'),//поле "текст для маркера"
     _a_opt=_a.find('textarea[name=address]'),//поле объекта адресов
     _a_box=_a.find('.addr_box'),//блок полей добавить\редактировать
     _a_gmap=_a.find('.addr_gmap'),//блок карты
     _a_prev=_a.find('.addr_prev'),//блок превью
     _a_add_btn=_a.find('.add_addr_btn'),//кнопка "добавить адрес"
     _a_done_btn=_a.find('.addr_done_btn'),//кнопка "готово"
     _a_cancel_btn=_a.find('.addr_cancel_btn'),//кнопка "отмена"
     _gps_reg=/(^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?$/,//регулярка проверки gps
     _opt=!_a_opt.val()?{}:JSON.parse(_a_opt.val());//объект адресов
 
 //////////////////////////////////////////////////////////
 //приватные методы
 //////////////////////////////////////////////////////////
 ////////////////////////////////получить уникальный id
 var _get_id=function(){return new Date().getTime().toString();};
 ////////////////////////////////открыть форму добавления адреса
 var _get_add_form=function(){
  _a_done_btn.on('click.Addr',function(){_add();});//событие на "готово" - добавить
  _a_box.slideDown(200);//открыть блок полей
  _map_init();//запуск карты
 };
 ////////////////////////////////открыть форму редактирования адреса
 var _get_edit_form=function(id){
  _a_done_btn.on('click.Addr',function(){_edit(id);});//событие на "готово" - редактировать
  _a_addr.val(_opt[id].address);
  _a_gps.val(_opt[id].gps);
  _a_marker_desc.val(_opt[id].marker_desc);
  _a_box.slideDown(200);//открыть блок полей
  _map_init();//запуск карты
 };
 ////////////////////////////////скрыть, очистить блок полей
 var _clear=function(){
  _a_done_btn.off();//удалить все события у кнопки "готово"
  _a_gmap.empty();//очистить карту
  _a_box.slideUp(200).find('input,textarea').val('');//очистить поля, скрыть блок
 };
 ////////////////////////////////добавить адрес
 var _add=function(){
  var added=false;
  if(!/\S/.test(_a_addr.val())){alert('Поле "Адрес" не заполнено!');return false;}
  if(!_gps_reg.test(_a_gps.val())){alert('Поле "GPS-координаты" не заполнено или заполнено не правильно!');return false;}
  for(var k in _opt){if(_opt[k].gps===_a_gps.val()){added=true;break;}}//проверка на уникальность
  if(!added){
   _opt[_get_id()]={gps:_a_gps.val(),marker_desc:_a_marker_desc.val(),address:_a_addr.val()};
  }else{alert('Адрес с такими координатами уже добавлен!');return false;}
  if(!$.isEmptyObject(_opt)){_a_opt.val(JSON.stringify(_opt));_clear();_show();}
 };
 ////////////////////////////////редактирование опций
 var _edit=function(id){
  var added=false;
  if(!/\S/.test(_a_addr.val())){alert('Поле "Адрес" не заполнено!');return false;}
  if(!_gps_reg.test(_a_gps.val())){alert('Поле "GPS-координаты" не заполнено или заполнено не правильно!');return false;}
  for(var k in _opt){if(_opt[k].gps===_a_gps.val()&&k!==id){added=true;break;}}//проверка на уникальность
  if(!added){
   _opt[id]={gps:_a_gps.val(),marker_desc:_a_marker_desc.val(),address:_a_addr.val()};
  }else{alert('Адрес с такими координатами уже добавлен!');return false;}
  if(!$.isEmptyObject(_opt)){_a_opt.val(JSON.stringify(_opt));_clear();_show();}
 };
 ////////////////////////////////удаление адреса
 var _del=function(id){
  delete _opt[id];
  if($.isEmptyObject(_opt)){_a_prev.empty();_a_opt.val('');}else{_a_opt.val(JSON.stringify(_opt));_show();}
 };
 ////////////////////////////////отображение превью
 var _show=function(){
  if($.isEmptyObject(_opt)){return false;}
  _a_prev.empty();//очистить превью
  for(var k in _opt){//заполнять превью адресами
   var address=$('<div/>',{class:'addr_prev_address',text:_opt[k].address}),
       edit_btn=$('<div/>',{class:'addr_prev_edit_btn fa-edit',title:'Редактировать адрес'}).data('id',k),
       del_btn=$('<div/>',{class:'addr_prev_del_btn fa-trash-o',title:'Удалить адрес'}).data('id',k),
       control=$('<div/>',{class:'addr_prev_control',html:[edit_btn,del_btn]}),
       prev_item=$('<div/>',{class:'addr_prev_item',html:[address,control]});
   edit_btn.on('click.Addr',function(){_get_edit_form($(this).data('id'));});
   del_btn.on('click.Addr',function(){if(confirm('Этот адрес будет удален!\nВыполнить действие?'))_del($(this).data('id'));});
   _a_prev.prepend(prev_item);
  }
 };
 ////////////////////////////////инициализация карты
 var _map_init=function(){
  var latlng=_gps_reg.test(_a_gps.val())?new google.maps.LatLng(_a_gps.val().split(',')[0],_a_gps.val().split(',')[1]):new google.maps.LatLng(50.450209,30.522536899999977),
      mapOptions={zoom:6,scrollwheel:false,center:latlng,mapTypeId:google.maps.MapTypeId.ROADMAP,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}},
      map=new google.maps.Map(_a_gmap[0],mapOptions),
      geocoder=new google.maps.Geocoder(),
      marker=new google.maps.Marker({map:map,draggable:true,position:latlng,title:"Для получения координат передвиньте маркер в нужное место"});
  //поиск координат на карте
  _a.find('.gps_search').autocomplete({
   source:function(request,response){
    geocoder.geocode({'address':request.term},function(results,status){
     response($.map(results,function(item){
      return {
       label:item.formatted_address,
       value:item.formatted_address,
       latitude:item.geometry.location.lat(),
       longitude:item.geometry.location.lng()
      };
     }));
    });
   },
   select:function(event,ui){
    _a_gps.val(ui.item.latitude+','+ui.item.longitude);
    var location=new google.maps.LatLng(ui.item.latitude,ui.item.longitude);
    marker.setPosition(location);
    map.setCenter(location);
   },
   delay:500
  });
  //установка маркера по введенным координатам
  _a_gps.on('keyup.Addr',function(){
   if(_gps_reg.test($(this).val())){//если соответствует шаблону gps-координат
    var location=new google.maps.LatLng($(this).val().split(',')[0],$(this).val().split(',')[1]);
    marker.setPosition(location);
    map.setCenter(location);
   }
  });
  //перетаскивание маркера
  google.maps.event.addListener(marker,'drag',function(){
   geocoder.geocode({'latLng':marker.getPosition()},function(results,status){
    if(status===google.maps.GeocoderStatus.OK){
     if(results[0]){_a_gps.val(marker.getPosition().lat()+','+marker.getPosition().lng());}
    }
   });
  });
 };
 
 //////////////////////////////////////////////////////////
 //события
 //////////////////////////////////////////////////////////
 _a_add_btn.on('click.Addr',function(){_get_add_form();});//открыть блок полей
 _a_cancel_btn.on('click.Addr',function(){_clear();});//скрыть, очистить блок полей
 
 //////////////////////////////////////////////////////////
 //после загрузки модуля
 //////////////////////////////////////////////////////////
 _show();//показать превью
}(jQuery,google));
</script>
