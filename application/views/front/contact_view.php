<!--####### Headline #######-->
<div id="headline" class="clear">
 <h1><?=$title?></h1>
 <div class="algn_r noprint">
  <button type="button" class="fa fa-print" id="print_btn" onclick="window.print();">&nbsp;Печатать</button>
 </div>
</div>

<?php if($layout_t){?>
<!--####### Контент страницы #######-->
<div id="layouts">
<div id="layout_t"><?=$layout_t?></div>
</div>
<?php }?>

<!--####### Контакты #######-->
<div class="row">
<div class="col9">
 <table class="contact_tabl">
  <?php if($tel){?>
  <tr>
   <td><b>Телефон: </b></td>
   <td class="num">
    <?php foreach(explode(',',$tel) as $t){echo $t.'<br>';}?>
   </td>
  </tr>
  <?php }?>
  <?php if($mail){?>
  <tr>
   <td><b>E-mail: </b></td>
   <td class="num">
    <?php foreach(explode(',',$mail) as $m){echo $m.'<br>';}?>
   </td>
  </tr>
  <?php }?>
  <?php if($skype){?>
  <tr>
   <td><b>Skype: </b></td>
   <td class="num"><?=$skype?></td>
  </tr>
  <?php }?>
 </table>
 <?php if($conf_addthis_follow&&$addthis_follow=='on'){?><div class="noprint"><?=$conf_addthis_follow?></div><?php }?>
</div>
<div class="col3">
 <?php if($qr){?>
 <img src="<?=$qr?>" style="border: solid 1px #000" id="qr" alt="QR-код">
 <?php }?>
</div>
</div>
<?php if($address){?><b>Адрес: </b><?=$address?><?php }?>
<?php if($gps){//если пришли координаты - округляем их до 6-ти знаков после точки
 $gps_=explode(',',$gps);
 $gps_[0]=round($gps_[0],6);
 $gps_[1]=round($gps_[1],6);
 ?><p><b>GPS-координаты (широта, долгота): </b><span class="num"><?=$gps_[0].', '.$gps_[1]?></span></p><?php }?>
<?php if($address&&$gps){?>
 <div id="map" style="height:500px;"></div>
 <p class="algn_r noprint">
  <a href="https://www.google.com.ua/maps/place/<?=$gps?>" target="_blank" class="fa fa-map-marker">&nbsp;показать на большой карте</a>
 </p>
<?php }?>
 
<?php if($contact_form==='on'){?>
<!--####### Форма обратной связи #######-->
<div id="send_mail_form" class="noprint">
<h2>Форма обратной связи</h2>
<form id="send_mail">
 <label class="input">
  <input type="text" name="mail" placeholder="Ваш email">
 </label>
 <label class="input">
  <input type="text" name="name" onkeyup="lim(this,50)" placeholder="Ваше имя">
 </label>
 <label class="textarea">
  <textarea name="text" rows="5" onkeyup="lim(this,500)" maxlength="500" placeholder="Ваше сообщение"></textarea>
 </label>
 <input type="text" name="fuck_bot">
 <div id="send_msg"></div>
 <button type="submit">Отправить сообщение</button>
</form>
</div>
 
<script>
window.addEventListener('load',function(){
 $('#send_mail').on('submit',function(e){
  e.preventDefault();
  var form=$(this),
      mail=form.find('[name="mail"]'),
      name=form.find('[name="name"]'),
      text=form.find('[name="text"]'),
      btn=form.find(':submit'),
      msg=$('#send_msg');
  //проверка полей
  if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(mail.val())){mail.addClass('novalid').focus();return false;}else{mail.removeClass('novalid');}
  if(!/\S/.test(name.val())){name.addClass('novalid').focus();return false;}else{name.removeClass('novalid');}
  if(!/\S/.test(text.val())){text.addClass('novalid').focus();return false;}else{text.removeClass('novalid');}
  btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
  //отправка
  $.ajax({
    url: '<?=base_url('do/send_mail')?>',//путь к скрипту, который обработает запрос
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
       btn.attr('disabled',false).html('Отправить сообщение');
       break;
      //все пучком
      case 'ok':mail.add(name).add(text).val('');
       btn.attr('disabled',false).html('Отправить сообщение');
       msg.html('<div class="notific_g">Ваше сообщение успешно отправлено!</div>');
       setTimeout(function(){msg.empty();},5000);
       break;
      //ошибки сценария сервера
      default :msg.html('<div class="notific_b">'+data+'</div>');
       btn.attr('disabled',false).html('Отправить сообщение');
       break;
     }
    }
  });
 });
});
</script>
<?php }if($address&&$gps){//если нужна карта"?>
<script src="http://maps.googleapis.com/maps/api/js?language=ru" defer></script>
<script>
function initialize(){
 var LL='<?=$gps?>'.split(','),//разобрать строку до и после запятой и записать в массив
     latlng=new google.maps.LatLng(LL[0],LL[1]),
     options={
  zoom:16,
  scrollwheel:false,
  center:latlng,
  mapTypeControlOptions:{
   style:google.maps.MapTypeControlStyle.DROPDOWN_MENU
  },
  mapTypeId:google.maps.MapTypeId.ROADMAP
 },
     map=new google.maps.Map($("#map")[0], options);
 new google.maps.Marker({
  animation:google.maps.Animation.DROP,
  map:map,
  position:latlng
 });
}
window.addEventListener('load',function(){
 $('#map').height(($(window).height())/1.5);//устанвливаю высоту и инициализирую карту
 initialize();
});
</script>
<?php }?>
