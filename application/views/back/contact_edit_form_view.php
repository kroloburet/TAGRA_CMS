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
   Адрес <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Начинайте вводить адрес для вызова вариантов.
Выберите из предложенных вариантов чтобы установить
координаты этого адреса в поле «GPS-координаты»
и установить маркер на карте.</pre>
   <label class="input">
    <input id="address" name="address" type="text" class="width90" value="<?=$address?>" placeholder="Крещатик, 20-22, Киев">
   </label>
   GPS-координаты <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
GPS-координаты (широта,долгота)
При вводе недопустимые символы
удаляются автоматически.
Если координаты указаны,
на странице «Контакты» будет
отображена карта.</pre><br>
   <label class="input inline width90">
    <input name="gps" id="LL" type="text" value="<?=$gps?>" placeholder="50.450031,30.524205">
   </label>
   <a href="#" class="fa-map-marker fa-lg blue" id="geo_init_btn" onmouseover="tt(this);"></a>
   <pre class="tt">
Нажмите чтобы воспользоваться поиском координат.
Начинайте вводить адрес в поле поиска для вызова
вариантов. Выберите из предложенных вариантов
чтобы установить координаты этого адреса в поле
«GPS-координаты». Чтобы кузать более точно,
передвиньте маркер на карте в нужное место.</pre>
   <div id="geo_search">
    <input id="gps_search" type="text" placeholder="Поиск координат">
    <div id="gmap"></div>
   </div>
  </div>

  <!--####### Форма обратной связи #######-->
  <div class="touch">
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
//////////////////////////////////////////////////////////работа полей "телефон" и "email"
///////////////////////рег.выражения для проверки полей
var s_opts={};
 s_opts['title']=/^[^><]+$/i;
 s_opts['description']=/^[^><]+$/i;
 s_opts['mail[]']=/^(([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6})?$/;
 s_opts['tel[]']=/^((\+\d{2,3})?[\s-]?\(?\d{0,3}\)?[\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2})?$/;
 s_opts['gps']=/^((^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?)?$/;
 
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

//////////////////////////////////////////////////////////GPS и карта
//////////////////////////установка переменных
var geocoder,map,marker,
    LL=$('#LL'),
    g_search=$('#geo_search'),
    gps_search=$('#gps_search'),
    g_btn=$('#geo_init_btn'),
    g_map=$('#gmap')[0];

/////////////////////////валидация поля координат
LL.on('keyup',function(){
 if(!/[0-9]|\.|,|-/.test($(this).val().slice(-1))){//если не цифра,точка,запятая,минус в последнем символе поля
  $(this).val($(this).val().slice(0,-1));//установить значение без последнего символа
 }
 if(/(^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?$/.test($(this).val())){//если соответствует шаблону gps-координат
  var LL_arr=$(this).val().split(',');//разобрать строку до и после запятой и записать в массив
  var location=new google.maps.LatLng(LL_arr[0],LL_arr[1]);
  marker.setPosition(location);
  map.setCenter(location);
 }
});

/////////////////////////показать\скрыть геопоиск и карту
g_btn.on('click',function(e){
 e.preventDefault();
 if(g_search.is(':hidden')){//геопоиск и карту показать
  g_search.slideDown(200);//показать
  $(g_map).is(':empty')?setTimeout(map_init,300):true;//инициализировать карту если не была инициализирована
//  setTimeout(map_init,300);
 }else{//геопоиск и карту скрыть
  g_search.slideUp(200);//скрыть
  gps_search.val('');//очистить поле геопоиска
 }
});

/////////////////////////подготовка инициализации карты
function map_init(){
 /////////////////////установка координат
 if(/(^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?$/.test(LL.val())){//если соответствует шаблону gps-координат
  var LL_arr=LL.val().split(','); //разобрать строку до и после запятой и записать в массив
  var latlng=new google.maps.LatLng(LL_arr[0],LL_arr[1]);
 }else{
  var latlng=new google.maps.LatLng(50.450209,30.522536899999977);
 }
 ////////////////////опции карты
 var mapOptions={
   zoom:6,
   scrollwheel:false,
   center:latlng,
   mapTypeId:google.maps.MapTypeId.ROADMAP,
   mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}
 };
 ///////////////////создание карты
 map=new google.maps.Map(g_map,mapOptions);//карта
 geocoder=new google.maps.Geocoder();//геолокация
 marker=new google.maps.Marker({//маркер
  map:map,
  draggable:true,
  position:latlng,
  title:"Для получения координат передвиньте маркер в нужное место"
 });
 //////////////////поиск координат на карте
 gps_search.autocomplete({
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
   LL.val(ui.item.latitude+','+ui.item.longitude);
   var location=new google.maps.LatLng(ui.item.latitude,ui.item.longitude);
   marker.setPosition(location);
   map.setCenter(location);
  },
  delay:500
 });
 google.maps.event.addListener(marker,'drag',function(){
  geocoder.geocode({'latLng':marker.getPosition()},function(results,status){
   if(status===google.maps.GeocoderStatus.OK){
    if(results[0]){LL.val(marker.getPosition().lat()+','+marker.getPosition().lng());}
   }
  });
 });
};
</script>
