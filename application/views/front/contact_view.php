<!--####### Mine #######-->
<div class="mine_wrapper">
<div class="container" style="max-width:<?=htmlspecialchars($conf['body_width'])?>px">

<!--####### Headline #######-->
<div id="headline">
 <h1><?=$data['title']?></h1>
 <div class="noprint">
  <button type="button" class="fa-print" id="print_btn" onclick="window.print();">&nbsp;<?=$lexic['contact']['print']?></button>
 </div>
</div>

<?php if($data['layout_l']||$data['layout_r']||$data['layout_t']||$data['layout_b']){//если заполнен один из сегментов макета?>
<!--####### Material content #######-->
<div id="layouts">
<?php if($data['layout_t']){//если заполнен верхний?>
<div id="layout_t"><?=$data['layout_t']?></div>
<?php }?>
<?php if($data['layout_l']||$data['layout_r']){//если заполнен правый или левый?>
<div id="layout_l" style="width:<?=htmlspecialchars($data['layout_l_width'])?>%;"><?=$data['layout_l']?></div>
<div id="layout_r"><?=$data['layout_r']?></div>
<?php }?>
<?php if($data['layout_b']){//если заполнен нижний?>
<div id="layout_b"><?=$data['layout_b']?></div>
<?php }?>
</div>
<?php }?>

<?php if($data['contacts']){?>
<!--####### Контакты #######-->
<dl class="tabs contacts">
 <dt class="tab_active"><?=$lexic['contact']['list_view']?></dt>
 <dd>
  <div class="tab_content">
   <?php foreach(json_decode($data['contacts'],TRUE) as $v){$gps=$v['gps']?TRUE:FALSE?>
   <div class="contacts_list">
    <?=$v['title']?'<h2>'.$v['title'].'</h2>':FALSE?>
    <?=$v['mail']?'<div><i class="fa-envelope"></i> '.$v['mail'].'</div>':FALSE?>
    <?=$v['tel']?'<div><i class="fa-phone"></i> '.$v['tel'].'</div>':FALSE?>
    <?=$v['skype']?'<div><i class="fa-skype"></i> '.$v['skype'].'</div>':FALSE?>
    <?=$v['address']?'<div><i class="fa-home"></i> '.$v['address'].'</div>':FALSE?>
    <?=$v['gps']?'<div><i class="fa-crosshairs"></i> '.$v['gps'].'</div>':FALSE?>
    <?=$v['gps']?'<div><i class="fa-compass"></i> <a href="https://www.google.com.ua/maps/place/'.$v['gps'].'" target="_blank">'.$lexic['contact']['big_map_view'].'</a></div>':FALSE?>
   </div>
   <?php }?>
  </div>
 </dd>
 <?php if($gps){?>
 <dt><?=$lexic['contact']['map_view']?></dt>
 <dd>
  <div class="tab_content">
   <div id="map" style="height:500px;"></div>
  </div>
 </dd>
 <?php }?>
</dl>
<?php }?>

<?php if($data['contact_form']==='on'){?>
<!--####### Форма обратной связи #######-->
<div id="send_mail_form" class="noprint">
<h2><?=$lexic['contact']['form_title']?></h2>
<form id="send_mail">
 <label class="input">
  <input type="text" name="mail" placeholder="<?=htmlspecialchars($lexic['contact']['your_mail'])?>">
 </label>
 <label class="input">
  <input type="text" name="name" onkeyup="lim(this,50)" placeholder="<?=htmlspecialchars($lexic['contact']['your_name'])?>">
 </label>
 <label class="textarea">
  <textarea name="text" rows="5" onkeyup="lim(this,500)" placeholder="<?=htmlspecialchars($lexic['contact']['your_msg'])?>"></textarea>
 </label>
 <input type="text" name="fuck_bot">
 <div class="send_mail_actions">
  <button type="submit"><?=$lexic['contact']['send_form']?></button>
 </div>
</form>
</div>
</div>
</div>

<script>
window.addEventListener('load',function(){
 $('#send_mail').on('submit',function(e){
  e.preventDefault();
  var f=$(this),
      mail=f.find('[name="mail"]'),
      name=f.find('[name="name"]'),
      text=f.find('[name="text"]'),
      actions_box=f.find('.send_mail_actions'),
      actions=actions_box.html(),
      delay=5000,
      msg=function(m){actions_box.html(m);setTimeout(function(){actions_box.html(actions);},delay);};
  //проверка полей
  if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(mail.val())){
   msg('<p class="notific_r"><?=addslashes($lexic['contact']['novalid_field'])?>"'+mail.attr('placeholder')+'"</p>');return false;
  }
  if(!/\S/.test(name.val())){msg('<p class="notific_r"><?=addslashes($lexic['contact']['novalid_field'])?>"'+name.attr('placeholder')+'"</p>');return false;}
  if(!/\S/.test(text.val())){msg('<p class="notific_r"><?=addslashes($lexic['contact']['novalid_field'])?>"'+text.attr('placeholder')+'"</p>');return false;}
  //блокировать кнопку
  actions_box.find('button').attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i><?=addslashes($lexic['basic']['loading'])?>');
  //отправка
  $.ajax({
    url:'/do/send_mail',
    type:'post',
    data:f.serialize(),
    dataType:'text',
    success:function(resp){
     switch(resp){
      case'bot':msg('<p class="notific_r"><?=addslashes($lexic['basic']['bot'])?></p>');break;
      case'nomail':msg('<p class="notific_r"><?=addslashes($lexic['contact']['nomail'])?></p>');break;
      case'error':msg('<p class="notific_r"><?=addslashes($lexic['basic']['error'])?></p>');break;
      case'ok':mail.add(name).add(text).val('');msg('<p class="notific_g"><?=addslashes($lexic['contact']['send_ok'])?></p>');break;
      default:console.log(resp);
     }
    },
   error:function(){msg('<p class="notific_r"><?=addslashes($lexic['basic']['server_error'])?></p>');}
  });
 });
});
</script>
<?php }if($data['contacts'] && $gps){?>
<script src="https://maps.googleapis.com/maps/api/js?language=<?=$data['lang']?>&key=<?=$conf['gapi_key']?>"></script>
<script>
window.addEventListener('load',function(){
 var data=JSON.parse('<?=$data['contacts']?>'),//объект данных
     mapOptions={zoom:6,scrollwheel:false,center:new google.maps.LatLng(49.303,31.203),streetViewControl:true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}},
     map=new google.maps.Map($('#map')[0],mapOptions),
     infowindow=new google.maps.InfoWindow(),//создаю инфоокно
     markers=[];
 /////////////////////////////////////////////////создать маркеры, расставить по координатам
 var init=function(){
  for(var k in data){//обход данных
   var LL=data[k].gps.split(',');
   markers[k]=new google.maps.Marker({//записываем маркер и его опции
    map:map,
    position:new google.maps.LatLng(LL[0],LL[1])
   });
   iw(markers[k],data[k].address+(data[k].marker_desc?'<hr>'+data[k].marker_desc:''));
  }
 };
 /////////////////////////////////////////////////показываю\скрываю инфоокно маркера по клику
 var iw=function(marker,content){
  google.maps.event.addListener(marker,'click',function(){
   infowindow.setContent(content);//контент в окне
   infowindow.open(map,marker);//показать окно
   map.panTo(marker.getPosition());//маркер в центер карты
  });
  google.maps.event.addListener(map,'click',function(){infowindow.close();});//клик на карте скрывает все окна
 };
 /////////////////////////////////////////////////устанвливаю высоту и инициализирую карту
 $('#map').height(($(window).height())/1.5);init();
});
</script>
<?php }?>