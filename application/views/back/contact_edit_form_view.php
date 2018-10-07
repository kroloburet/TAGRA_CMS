<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять.
?>
<h1><?=$conf_title?></h1>
<div class="sheath">
 <form method="POST" action="<?=base_url('admin/contact/edit')?>" onsubmit="subm(this,s_opts);return false">
  <input type="hidden" name="last_mod_date" value="<?=date('Y-m-d')?>">

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
       <option value="off" <?=$addthis_share=='off'?'selected':''?>>скрыть</option>
       <option value="on" <?=$addthis_share=='on'?'selected':''?>>показать</option>
      </select>
     </label>
    </div>
    <div class="col6">
     Кнопки «Follow»
     <label class="select">
      <select name="addthis_follow">
       <option value="off" <?=$addthis_follow=='off'?'selected':''?>>скрыть</option>
       <option value="on" <?=$addthis_follow=='on'?'selected':''?>>показать</option>
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
  <div class="touch" id="contact">
   <h3 class="float_l">Контакты</h3> <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
Здесь вы можете указать один или
несколько контактов, а так же
редактировать их.</pre>
   <hr>
   <button type="button" class="add_contact_btn">Добавить контакт</button>
   <div class="contact_box" style="display:none">
    <div class="row">
     <div class="col6">
      Порядок <i class="fa-question-circle red" onmouseover="tt(this);"></i>
      <pre class="tt">
Порядковый номер этого контакта
в списке контактов.
<b class="red">Обязательно для заполнения!</b></pre>
      <label class="input">
       <input type="number" class="order" min="1">
      </label>
     </div>
     <div class="col6">
      Заголовок <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
      <pre class="tt">
Заголовок для этого контакта.
Пример: Отдел поддержки котиков</pre>
      <label class="input">
       <input type="text" class="title" placeholder="Отдел поддержки котиков">
      </label>
     </div>
    </div>
    <div class="row">
     <div class="col6">
      E-mail <i class="fa-question-circle red" onmouseover="tt(this);"></i>
      <pre class="tt">
Если у контакта несколько почтовых ящиков,
укажите их по одному через запятую.
<b class="red">Обязательно для заполнения!</b></pre>
      <label class="input">
       <input type="text" class="mail" placeholder="info@domen.ua, sale@domen.ua">
      </label>
     </div>
     <div class="col6">
      Телефон <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
      <pre class="tt">
Если у контакта несколько номеров,
укажите их по одному через запятую.
Внимание! Указывайте номера с кодом станы.
Пример: +38(093)1234567, +38 044 123-45-67</pre>
      <label class="input">
       <input type="text" class="tel" placeholder="+38(095)1112233, +38 063 123 4567">
      </label>
     </div>
    </div>
    Skype
    <label class="input">
     <input type="text" class="skype">
    </label>
    Адрес <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Физический адрес контакта</pre>
    <label class="input">
     <input class="address" type="text" placeholder="Крещатик, 20-22, Киев">
    </label>
    GPS-координаты <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
GPS-координаты (широта,долгота).
Координаты можно получить перетащив
маркер на карте в нужное место, или
воспользоваться полем «Поиск координат».
Начните вводить в поле поиска адрес чтобы
вызвать варианты, выберите нужный вариант,
координаты и маркер будут установлены.</pre>
    <label class="input">
     <input class="gps" type="text" placeholder="50.450031,30.524205">
    </label>
    <input class="gps_search" type="text" placeholder="Поиск координат">
    <div class="contact_gmap"></div>
    <textarea class="marker_desc" type="text" placeholder="Текст для маркера (можно использовать HTML)"></textarea>
    <div class="button algn_r">
     <button type="button" class="contact_done_btn">Готово</button><button type="button" class="contact_cancel_btn">Отмена</button>
    </div>
   </div>
   <textarea name="contacts" hidden><?=$contacts?></textarea>
   <div class="contact_prev"></div>
  </div>

  <!--####### Форма обратной связи #######-->
  <div class="touch">
   <h3>Форма обратной связи</h3>
   <hr>
   <label class="select">
    <select name="contact_form">
     <option value="on" <?=$contact_form=='on'?'selected':''?>>Показать форму обратной связи</option>
     <option value="off" <?=$contact_form=='off'?'selected':''?>>Скрыть форму обратной связи</option>
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

//////////////////////////////////////////////////////////контакты
;(function($,google){
 var _=$('#contact'),//блок контакта
     _order=_.find('.order'),//поле "порядок"
     _title=_.find('.title'),//поле "заголовок"
     _mail=_.find('.mail'),//поле "email"
     _tel=_.find('.tel'),//поле "телефон"
     _skype=_.find('.skype'),//поле "skype"
     _address=_.find('.address'),//поле "адрес"
     _gps=_.find('.gps'),//поле "gps"
     _marker_desc=_.find('.marker_desc'),//поле "текст для маркера"
     _contacts=_.find('textarea[name=contacts]'),//поле объекта контактов
     _box=_.find('.contact_box'),//блок полей добавить\редактировать
     _gmap=_.find('.contact_gmap'),//блок карты
     _prev=_.find('.contact_prev'),//блок превью
     _add_btn=_.find('.add_contact_btn'),//кнопка "добавить контакт"
     _del_all_btn=$('<button/>',{type:'button',text:'Удалить все'}).on('click.Contact',function(){__del_all();}),//кнопка "удалить все"
     _done_btn=_.find('.contact_done_btn'),//кнопка "готово"
     _cancel_btn=_.find('.contact_cancel_btn'),//кнопка "отмена"
     _gps_reg=/(^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?$/,//регулярка проверки gps
     _opt=!_contacts.val()?{}:JSON.parse(_contacts.val());//объект контактов

 ////////////////////////////////валидация полей
 var __validator=function(id){
  var opt_count=Object.keys(_opt).length;//всего контактов
  //валидация поля "Порядок"
  if(!/^[1-9]\d*$/.test(_order.val())){
   alert('Поле "Порядок" не заполнено или заполнено некорректно!\nТолько целое число больше нуля!');return false;
  }
  if(typeof id!=="undefined" && parseInt(_order.val(),10)>opt_count){
   alert('Поле "Порядок" заполнено некорректно!\nТолько целое число в пределах 1-'+opt_count+'!');return false;
  }
  //валидация поля "E-mail"
  if(!/\S/.test(_mail.val())){//если пусто
   alert('Поле "E-mail" не заполнено!');return false;
  }else{//если поле заполнено
   var mail_arr=_mail.val().split(',');//разбиваю стоку в массив email-ов
   for(var i=0;i<mail_arr.length;i++){//проход по массиву email-ов
    if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(mail_arr[i].trim())){//проверка
     alert('Поле "E-mail" заполнено некорректно!');return false;
    }
   }
  }
  //валидация поля "Tелефон"
  if(/\S/.test(_tel.val())){//поле заполнено
   var tel_arr=_tel.val().split(',');//разбиваю стоку в массив телефонов
   for(var i=0;i<tel_arr.length;i++){//проход по массиву телефонов
    if(!/^\+\d{2,3}[\s-]?\(?\d{0,3}\)?[\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}$/.test(tel_arr[i].trim())){//проверка
     alert('Поле "Телефон" заполнено некорректно!');return false;
    }
   }
  }
  //валидация поля "Адрес","GPS-координаты"
  if(/\S/.test(_address.val())){//поле "Адрес" заполнено - нужно заполнить координаты
   if(!_gps_reg.test(_gps.val())){//GPS-координаты некорректны
    alert('Поле "GPS-координаты" не заполнено или заполнено некорректно!\nЕсли заполнено поле "Адрес", то поле "GPS-координаты" обязательно для заполнения.');return false;
   }else{//GPS-координаты корректны - проверка на уникальность в контактах
    for(var k in _opt){//обход контактов
     if(_opt[k].gps===_gps.val()&&k!==id){
      alert('Контакт с такими координатами уже добавлен!');return false;
     }
    }
   }
  }
  if(/\S/.test(_gps.val())){//поле "GPS-координаты" заполнено - нужно заполнить адрес
   if(!/\S/.test(_address.val())){
    alert('Поле "Адрес" не заполнено!\nЕсли заполнено поле "GPS-координаты", то поле "Адрес" обязательно для заполнения.');return false;
   }
  }
  //проверка пройдена успешно
  return true;
 };
 ////////////////////////////////открыть форму добавления
 var __get_add_form=function(){
  __clear();
  var order=Object.keys(_opt).length+1;
  _done_btn.on('click.Contact',function(){__add();});//событие на "готово" - добавить
  _order.val(order).attr({'readonly':true,'max':order});//задать порядковый номер и заблокировать поле
  _box.slideDown(200);//открыть блок полей
  __map_init();//запуск карты
 };
 ////////////////////////////////открыть форму редактирования
 var __get_edit_form=function(id){
  __clear();
  _done_btn.on('click.Contact',function(){__edit(id);});//событие на "готово" - редактировать
  _order.val(id).attr({'readonly':false,'max':Object.keys(_opt).length});
  _title.val(_opt[id].title);
  _mail.val(_opt[id].mail);
  _tel.val(_opt[id].tel);
  _skype.val(_opt[id].skype);
  _address.val(_opt[id].address);
  _gps.val(_opt[id].gps);
  _marker_desc.val(_opt[id].marker_desc);
  _box.slideDown(200);//открыть блок полей
  __map_init();//запуск карты
 };
 ////////////////////////////////скрыть, очистить блок полей
 var __clear=function(){
  _done_btn.off();//удалить все события у кнопки "готово"
  _gmap.empty();//очистить карту
  _box.slideUp(200).find('input,textarea').val('');//очистить поля, скрыть блок
 };
 ////////////////////////////////добавить
 var __add=function(){
  if(!__validator()){return false;}//проверка полей
  _opt[_order.val()]={title:_title.val(),mail:_mail.val(),tel:_tel.val(),skype:_skype.val(),gps:_gps.val(),marker_desc:_marker_desc.val(),address:_address.val()};
  if(!$.isEmptyObject(_opt)){_contacts.val(JSON.stringify(_opt));__clear();__show();scrll('contact');}
 };
 ////////////////////////////////редактировать
 var __edit=function(id){
  if(!__validator(id)){return false;}//проверка полей
  var order=_order.val(),//новый порядковый номер
  int_order=parseInt(order,10),//новый порядковый номер в число
  int_id=parseInt(id,10),//текущий порядковый номер в число
  temp={};//если изменится порядковый номер - будет хранить объект слайдов с новым порядком
  Number.prototype.between=function(a,b){var min=Math.min(a,b),max=Math.max(a,b);return this>=min&&this<=max;};//проверка числа в диапазоне
  if(order!==id){//порядковый номер изменился
   for(var k in _opt){//проход по контактам
    var int_k=parseInt(k,10);//текущий порядковый номер (ключ) в число
    if(int_k.between(int_id,int_order)){//текущий ключ входит в диапазон
     if(int_k===int_id){//текущий ключ и текущий порядковый номер совпали
      temp[order]={title:_title.val(),mail:_mail.val(),tel:_tel.val(),skype:_skype.val(),gps:_gps.val(),marker_desc:_marker_desc.val(),address:_address.val()};//записать измененный элемент и назначить новый порядковый номер
     }else{//не совпали
      //увеличить или уменьшить номер в диапазоне чтобы сдвинуть
      int_id>int_order?temp[int_k+1]=_opt[k]:temp[int_k-1]=_opt[k];
     }
    }else{//текущий ключ не входит в диапазон
     temp[k]=_opt[k];//оставить как есть
    }
   }//проход закончен
   _opt=temp;
   if(!$.isEmptyObject(_opt)){_contacts.val(JSON.stringify(_opt));__clear();__show();scrll('contact');}
   return true;
  }//порядковый номер не изменился
  _opt[id]={title:_title.val(),mail:_mail.val(),tel:_tel.val(),skype:_skype.val(),gps:_gps.val(),marker_desc:_marker_desc.val(),address:_address.val()};
  if(!$.isEmptyObject(_opt)){_contacts.val(JSON.stringify(_opt));__clear();__show();scrll('contact');}
 };
 ////////////////////////////////удалить элемент
 var __del=function(id){
  var int_id=parseInt(id,10);
  for(var k in _opt){
    var int_k=parseInt(k,10);
    int_k>int_id?_opt[int_k-1]=_opt[k]:true;
    int_k===Object.keys(_opt).length?delete _opt[k]:true;
  }
  if($.isEmptyObject(_opt)){_prev.empty();_contacts.val('');_del_all_btn.remove();}else{_contacts.val(JSON.stringify(_opt));__show();scrll('contact');}
 };
 ////////////////////////////////удалить все
 var __del_all=function(){
  if(!confirm('Все элементы будут удалены!\nВыполнить действие?')){return false;}
  _opt={};
  _prev.empty();
  _contacts.val('');
  __clear();
  _del_all_btn.remove();
 };
 ////////////////////////////////отобразить превью
 var __show=function(){
  if($.isEmptyObject(_opt)){return false;}
  _add_btn.after(_del_all_btn);//добавить кнопку "удалить все"
  _prev.empty();//очистить превью
  for(var k in _opt){//заполнять превью адресами
   var str=(k+'. '+_opt[k].title+' '+_opt[k].mail+' '+_opt[k].tel+' '+_opt[k].address).substring(0,100)+'...',
       edit_btn=$('<div/>',{class:'contact_prev_edit_btn fa-edit',title:'Редактировать'}).data('id',k),
       del_btn=$('<div/>',{class:'contact_prev_del_btn fa-trash-o',title:'Удалить'}).data('id',k),
       prev_str_box=$('<div/>',{class:'contact_prev_str_box',text:str}),
       control_box=$('<div/>',{class:'contact_prev_control',html:[edit_btn,del_btn]}),
       prev_item=$('<div/>',{class:'contact_prev_item',html:[prev_str_box,control_box]});
   edit_btn.on('click.Contact',function(){__get_edit_form($(this).data('id'));scrll('contact');});
   del_btn.on('click.Contact',function(){if(confirm('Этот элемент будет удален!\nВыполнить действие?'))__del($(this).data('id'));});
   _prev.append(prev_item);
  }
 };
 ////////////////////////////////инициализация карты
 var __map_init=function(){
  var latlng=_gps_reg.test(_gps.val())?new google.maps.LatLng(_gps.val().split(',')[0],_gps.val().split(',')[1]):new google.maps.LatLng(50.450209,30.522536899999977),
      mapOptions={zoom:6,scrollwheel:false,center:latlng,mapTypeId:google.maps.MapTypeId.ROADMAP,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}},
      map=new google.maps.Map(_gmap[0],mapOptions),
      geocoder=new google.maps.Geocoder(),
      marker=new google.maps.Marker({map:map,draggable:true,position:latlng,title:"Для получения координат передвиньте маркер в нужное место"});
  //поиск координат на карте
  _.find('.gps_search').autocomplete({
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
    _gps.val(ui.item.latitude+','+ui.item.longitude);
    var location=new google.maps.LatLng(ui.item.latitude,ui.item.longitude);
    marker.setPosition(location);
    map.setCenter(location);
   },
   delay:500
  });
  //установка маркера по введенным координатам
  _gps.on('keyup.Contact',function(){
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
     if(results[0]){_gps.val(marker.getPosition().lat()+','+marker.getPosition().lng());}
    }
   });
  });
 };
////////////////////////////////события
 _add_btn.on('click.Contact',function(){__get_add_form();});//открыть блок полей
 _cancel_btn.on('click.Contact',function(){__clear();scrll('contact');});//скрыть, очистить блок полей
////////////////////////////////после загрузки модуля
 __show();//показать превью
}(jQuery,google));
</script>
