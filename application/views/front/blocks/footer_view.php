
<!--####### Footer #######-->
<div class="footer_wrapper">
 <footer class="container noprint" style="max-width:<?=htmlspecialchars($conf['body_width'])?>px">
  <div class="copy">
   <?=$conf['site_name']?>&nbsp;&copy;&nbsp;<?=date('Y')?><hr>
   <img src="/img/i.jpg" alt="kroloburet">&nbsp;development by
   <br><a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a>
  </div>
  <div class="follow_box">
   <?php if($conf['addthis_follow']&&$data['addthis_follow']=='on'){?>
    <h5><?=$lexic['basic']['follow_title']?></h5><hr>
    <?=$conf['addthis_follow']?>
   <?php }?>
  </div>
 </footer>
</div>
<div id="scrol_btn" onclick="scrll('body');" class="fa-angle-up noprint"></div>

<!--####### Отложенная загрузка CSS #######-->
<script>
var doc_head=document.getElementsByTagName("head")[0];
var ms=document.createElement("link");ms.rel="stylesheet";//иконочный шрифт
ms.href="/UI_fraimwork/fonts/FontAwesome/style.css";doc_head.insertBefore(ms,doc_head.firstChild);
var ms=document.createElement("link");ms.rel="stylesheet";//главная таблица стилей
ms.href="/css/front/general.css";doc_head.insertBefore(ms,doc_head.firstChild);
var ms=document.createElement("link");ms.rel="stylesheet";//UI_fraimwork
ms.href="/UI_fraimwork/css.css";doc_head.insertBefore(ms,doc_head.firstChild);
</script>

<!--####### Отложенная загрузка JS #######-->
<script src="<?=htmlspecialchars($conf['jq'])?>"></script>
<script src="/UI_fraimwork/js.js" defer></script>

<?php if(isset($data['js'])&&$data['js']){echo '<!--####### Пользовательский JS к этому документу #######-->'.PHP_EOL.$data['js'].PHP_EOL;}?>

<?php if((isset($data['gallery_type'])&&$data['gallery_type'])&&(isset($data['gallery_opt'])&&$data['gallery_opt'])){//если галерея

if($data['gallery_type']=='foto_folder'||$data['gallery_type']=='foto_desc'||$data['gallery_type']=='video_yt'){//фото или видео?>
<!--####### JS для фото или видео галереи #######-->
<script src="/scripts/libs/FVGallery/FVGallery.js" defer></script>
<script>
window.addEventListener('load',function(){
 $('.FVG_item').FVGallery({type:'<?=$data['gallery_type']?>'});
});
</script>
<?php }?>

<?php if($data['gallery_type']=='audio'){//аудио?>
<!--####### JS для аудио галереи #######-->
<script>
;(function($){
 var i=0,
     playing=false,
     p=$('#a_player'),
     action=p.find('.a_action'),
     title=p.find('.a_title'),
     tracks=[
         <?php if($data['gallery_opt']){foreach(json_decode($data['gallery_opt'],true)as$v){?>
      {
       "name":"<?=addslashes($v['a_title'])?>",
       "file":"<?=$v['a_file']?>"
      },
     <?php }}?>
     ],
     count=tracks.length,
     audio=p.find('.a_audio')
      .on('play.AP',function(){playing=true;action.text('<?=addslashes($lexic['gallery']['playing'])?>');})
      .on('pause.AP',function(){playing=false;action.text('<?=addslashes($lexic['gallery']['paused'])?>');})
      .on('ended.AP',function(){if((i+1)<count){i++;audio.play();}else{audio.pause();i=0;}action.text('<?=addslashes($lexic['gallery']['paused'])?>');loadTrack(i);}).get(0);
 ////////////////////////////загрузка
 var loadTrack=function(id){
  p.find('.a_ready').removeClass('a_ready');
  p.find('.a_item:eq('+id+')').addClass('a_ready');
  title.text(tracks[id].name);
  i=id;
  p.find('.a_ogg').attr('src',tracks[id].file+'.ogg');
  p.find('.a_mp3').attr('src',tracks[id].file+'.mp3');
  p.find('.a_wav').attr('src',tracks[id].file+'.wav');
  audio.load();
 };
 ////////////////////////////воспроизведение
 var playTrack=function(id){loadTrack(id);audio.play();};
 ////////////////////////////события
 p.find('.a_prev').on('click.AP',function(){i=(i-1)>-1?i-1:count-1;loadTrack(i);if(playing){audio.play();}}),
 p.find('.a_next').on('click.AP',function(){i=(i+1)<count?i+1:0;loadTrack(i);if(playing){audio.play();}}),
 p.find('.a_item').on('click.AP',function(){var id=$(this).index();if(id!==i){playTrack(id);}}),
 ///////////////////////////после загрузки модуля
 loadTrack(i);
}(jQuery));
</script>
<?php }}?>

<!--####### HTML5-теги и медиа-запросы для IE9 и ниже #######-->
<!--[if lt IE 9]>
<script src="/scripts/libs/html5shiv.min.js"></script>
<script src="/scripts/libs/respond.min.js"></script>
<![endif]-->

<?php if((isset($data['addthis_share'])||isset($data['addthis_follow']))&&($data['addthis_share']=='on'||$data['addthis_follow']=='on')){//Кнопки "Поделиться"
if($conf['addthis_js']){echo '<!--####### JS для кнопок соцсетей #######-->'.PHP_EOL.$conf['addthis_js'].PHP_EOL;}
}?>

<!--
########### Вот ты сейчас в этой уйне копошишься,
########### а жизнь мимо летит..)
-->

</body>
</html>