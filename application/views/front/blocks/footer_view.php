<!--####### Footer #######-->
<div class="footer_wrapper">
 <footer class="container noprint" style="max-width:<?=$conf_body_width?>px">
  <div class="copy">
   <?=$conf_site_name?>&nbsp;&copy;&nbsp;<?=date('Y')?><hr>
   Веб-разработка и дизайн&nbsp;<img src="<?=base_url('img/kroloburet_18_18.jpeg')?>" width="18" height="18" alt="Разработка и дизайн сайтов">
   <br><a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a>
  </div>
  <div class="follow_box">
   <?php if($conf_addthis_follow&&$addthis_follow=='on'){?>
    <h5>Мы в соцсетях</h5><hr>
    <?=$conf_addthis_follow?>
   <?php }?>
  </div>
 </footer>
</div>
<div id="scrol_btn" onclick="scrll('body');" class="fa-angle-up noprint"></div>

<!--####### Отложенная загрузка CSS #######-->
<script>
var doc_head=document.getElementsByTagName("head")[0];
var ms=document.createElement("link");ms.rel="stylesheet";//UI_fraimwork
ms.href="/UI_fraimwork/fonts/FontAwesome/style.css";doc_head.insertBefore(ms,doc_head.firstChild);
var ms=document.createElement("link");ms.rel="stylesheet";//главная таблица стилей
ms.href="/css/front/general.css";doc_head.insertBefore(ms,doc_head.firstChild);
var ms=document.createElement("link");ms.rel="stylesheet";//иконочный шрифт
ms.href="/UI_fraimwork/css.css";doc_head.insertBefore(ms,doc_head.firstChild);
<?php if(isset($type)&&$type){//если галерея
if($type==='foto_folder'||$type==='foto_desc'||$type==='video_yt'){//фото или видео?>
var ms=document.createElement("link");ms.rel="stylesheet";//
ms.href="/scripts/libs/FVGallery/FVGallery.css";doc_head.insertBefore(ms,doc_head.firstChild);
<?php }if($type==='audio'){//аудио?>
var ms=document.createElement("link");ms.rel="stylesheet";//
ms.href="/scripts/libs/html5_audio/html5_audio.css";doc_head.insertBefore(ms,doc_head.firstChild);
<?php }}?>
</script>

<!--####### Отложенная загрузка JS #######-->
<script src="<?=$conf_jq?>"></script>
<script src="/UI_fraimwork/js.js" defer></script>
<?php if(isset($type)&&$type){//если галерея
if($type=='foto_folder'||$type=='foto_desc'||$type=='video_yt'){//фото или видео?>
<script src="/scripts/libs/FVGallery/FVGallery.js" defer></script>
<?php }?>
<?php if($type=='audio'){//если галерея видео или аудио - поддержка старыми браузерами <audio> и <video>?>
<script src="/scripts/libs/html5_audio/html5media_1.2.2_min.js" defer></script>
<?php }}?>

<?php if(isset($js)&&$js){echo '<!--####### Пользовательский JS к этому документу #######-->'.PHP_EOL.$js.PHP_EOL;}?>

<?php if(isset($type)&&($type=='foto_folder'||$type=='foto_desc'||$type=='video_yt')){//если галерея: фото или видео?>

<!--####### JS для фото или видео галереи #######-->
<script>
window.addEventListener('load',function(){
 $('.FVG_item').FVGallery({type:'<?=$type?>'});
});
</script>
<?php }if(isset($type)&&$type=='audio'){//аудио галерея?>

<!--####### JS для аудио галереи #######-->
<script>
// html5media enables <video> and <audio> tags in all major browsers
// External File: http://api.html5media.info/1.2.2/html5media.min.js
// Add user agent as an attribute on the <html> tag...
// Inspiration: http://css-tricks.com/ie-10-specific-styles/
var b=document.documentElement;
b.setAttribute('data-useragent',navigator.userAgent);
b.setAttribute('data-platform',navigator.platform );
// HTML5 audio player + playlist controls...
// Inspiration: http://jonhall.info/how_to/create_a_playlist_for_html5_audio
jQuery(function($){
var supportsAudio=!!document.createElement('audio').canPlayType;
if(supportsAudio){
 var index=0,
 playing=false,
 tracks=[
<?php if($opt){$num=1;foreach(json_decode($opt,true) as $v){?>
  {
   "track":<?=$num?>,
   "name":"<?=$v['a_title']?>",
   //"length":"time",
   "file":"<?=$v['a_file']?>"
  },
<?php }}?>
 ],
 trackCount=tracks.length,
 npAction=$('#npAction'),
 npTitle=$('#npTitle'),
 audio=$('#audio1').on('play',function(){
  playing=true;
  npAction.text('Воспроизводится...');
  }).on('pause',function(){
   playing=false;
   npAction.text('Пауза...');
  }).on('ended',function(){
   npAction.text('Пауза...');
   if((index+1)<trackCount){
    index++;
    loadTrack(index);
    audio.play();
   }else{
    audio.pause();
    index=0;
    loadTrack(index);
   }
  }).get(0),
  btnPrev=$('#a_btnPrev').click(function(){
   if((index-1)>-1){
    index--;
    loadTrack(index);
    if(playing){
     audio.play();
    }
   }else{
    audio.pause();
    index=0;
    loadTrack(index);
   }
  }),
  btnNext=$('#a_btnNext').click(function(){
   if((index+1)<trackCount){
    index++;
    loadTrack(index);
    if(playing){
     audio.play();
    }
   }else{
    audio.pause();
    index=0;
    loadTrack(index);
   }
  }),
  li=$('#plList li').click(function(){
   var id=parseInt($(this).index());
   if(id!==index){
    playTrack(id);
   }
  }),
  loadTrack=function(id){
   $('.plSel').removeClass('plSel');
   $('#plList li:eq(' + id + ')').addClass('plSel');
   npTitle.text(tracks[id].name);
   index = id;
   $('#source_ogg').attr('src',tracks[id].file+'.ogg');
   $('#source_mp3').attr('src',tracks[id].file+'.mp3');
   $('#source_wav').attr('src',tracks[id].file+'.wav');
   audio.load();
  },
  playTrack=function(id){
   loadTrack(id);
   audio.play();
  };
  loadTrack(index);
  audio.volume=0.3;
}
});
</script>
<?php }?>

<!--####### HTML5-теги и медиа-запросы для IE9 и ниже #######-->
<!--[if lt IE 9]>
<script src="<?=base_url('scripts/libs/html5shiv.min.js')?>"></script>
<script src="<?=base_url('scripts/libs/respond.min.js')?>"></script>
<![endif]-->

<?php if((isset($addthis_share)||isset($addthis_follow))&&($addthis_share=='on'||$addthis_follow=='on')){//Кнопки "Поделиться"
if($conf_addthis_js){echo '<!--####### JS для кнопок соцсетей #######-->'.PHP_EOL.$conf_addthis_js.PHP_EOL;}
}?>

<!--
########### Вот ты сейчас в этой уйне копошишься,
########### а жизнь мимо летит..)
-->

</body>
</html>
