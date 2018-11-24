<!--####### Mine #######-->
<div class="mine_wrapper">
<div class="container" style="max-width:<?=$conf_body_width?>px">

<!--####### Breadcrumbs #######-->
<?php
if($conf_breadcrumbs_public=='on'){
 $this->load->helper('front/breadcrumbs');
 breadcrumbs($conf_breadcrumbs_home);
}
?>

<!--####### Headline #######-->
<div id="headline">
<h1><?=$title?></h1>
<?php if($conf_addthis_share&&$addthis_share=='on'){?>
<div class="addthis_layout noprint"><?=$conf_addthis_share?></div>
<?php }?>
</div>

<?php if($layout_l||$layout_r||$layout_t||$layout_b){//если заполнен один из сегментов макета?>
<!--####### Контент материала #######-->
<div id="layouts">
<?php if($layout_t){//если заполнен верхний?>
<div id="layout_t"><?=$layout_t?></div>
<?php }?>
<?php if($layout_l||$layout_r){//если заполнен правый или левый?>
<div id="layout_l" style="width:<?=$layout_l_width?>%;"><?=$layout_l?></div>
<div id="layout_r"><?=$layout_r?></div>
<?php }?>
<?php if($layout_b){//если заполнен нижний?>
<div id="layout_b"><?=$layout_b?></div>
<?php }?>
</div>
<?php }?>

<?php if($links){//есть связанные ссылки
$l_opt=json_decode($links,true);
echo '<!--####### Связанные ссылки #######-->'.PHP_EOL;
echo '<div id="links_block" class="noprint">'.PHP_EOL;
echo $l_opt['title']?'<h3>'.$l_opt['title'].'</h3>'.PHP_EOL:FALSE;
foreach($l_opt as $k=>$v){
 echo $k!=='title'?'<a href="'.$v['url'].'" class="links_item fa-chain">&nbsp;'.$v['title'].'</a>'.PHP_EOL:FALSE;
}
echo '</div>'.PHP_EOL;
}?>

<!--####### Галерея #######-->
<div id="FVGallery_layout">
<?php
switch($gallery_type){

 case 'foto_folder'://галерея из папки с изображениями
  if($gallery_opt){//есть опции
   $opt=json_decode($gallery_opt,true);
   $directory=$opt['f_folder'];//папка с изображениями
   $allowed_types=array('png','jpg','jpeg','gif','webp','svg');//разрешеные типы изображений
   $file_parts=array();
   $ext='';
   $i=0;
   $dir_handle=@opendir('.'.$directory) or die('Неверный путь к папке изображений!');//пробуем открыть папку
   while($file=readdir($dir_handle)){//поиск по файлам
    if($file=='.'||$file=='..')
     continue;//пропустить ссылки на другие папки
    $file_parts=explode('.',$file);//разделить имя файла и поместить его в массив
    $ext=strtolower(array_pop($file_parts));//последний элеменет - это расширение
    if(in_array($ext,$allowed_types)){//строю костяк html
     echo '<div class="FVG_item FVG_item_f_folder">'.PHP_EOL;
     echo '<img src="'.$directory.'/'.$file.'" alt="'.$file.'">'.PHP_EOL;
     echo '<textarea class="opt" hidden>{"f_url":"'.$directory.'/'.$file.'"}</textarea>'.PHP_EOL;
     echo '</div>'.PHP_EOL;
     $i++;
    }
   }
   closedir($dir_handle);//закрыть папку
  }else{echo '<div class="notific_b">Галерея не может быть отображена! В галерее нет ни одного изображения</div>'.PHP_EOL;}
 break;

 case 'foto_desc'://галерея изображений с описаниями
  if($gallery_opt){//есть опции
   foreach(json_decode($gallery_opt,true) as $v){//читаю json и строю костяк html
    echo '<div class="FVG_item FVG_item_f_desc">'.PHP_EOL;
    echo $v['f_title']||$v['f_desc']?'<div class="FVG_item_f_desc_preview"><h3>'.$v['f_title'].'</h3>'.$v['f_desc'].'</div>'.PHP_EOL:FALSE;
    echo '<img src="'.$v['f_url'].'" alt="'.$v['f_title'].'">'.PHP_EOL;
    echo '<textarea class="opt" hidden>'.json_encode($v).'</textarea>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
   }
  }else{echo '<div class="notific_b">Галерея не может быть отображена! В галерее нет ни одного изображения</div>'.PHP_EOL;}
 break;

 case 'video_yt'://галерея youtube
  if($gallery_opt){//есть опции
   foreach(json_decode($gallery_opt,true) as $v){
    echo '<div class="FVG_item FVG_item_v_yt">'.PHP_EOL;
    echo '<img src="http://img.youtube.com/vi/'.$v['yt_id'].'/mqdefault.jpg">'.PHP_EOL;
    echo '<textarea class="opt" hidden>'.json_encode($v).'</textarea>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
   }
  }else{echo '<div class="notific_b">Галерея не может быть отображена! В галерее нет ни одного видео</div>'.PHP_EOL;}
  break;

 case 'audio'://галерея audio
  if($gallery_opt){//есть опции?>
 <div id="a_player">
  <div class="a_controls">
   <div class="a_now_play">
    <span class="a_action"></span>
    <span class="a_title"></span>
   </div>
   <div class="a_box">
    <audio class="a_audio" preload controls>
     <source class="a_ogg" type="audio/ogg">
     <source class="a_mp3" type="audio/mpeg">
     <source class="a_wav" type="audio/wav">
     Ваш браузер устарел и не поддерживает HTML5 Audio!
    </audio>
    <div class="a_nav">
     <a class="a_prev">&laquo;</a>
     <a class="a_next">&raquo;</a>
    </div>
   </div>
  </div>
  <ul class="a_items">
   <?php $num=1;foreach(json_decode($gallery_opt,true)as$v){$num_=$num<=9?'0'.$num++.'.':$num++.'.';?>
   <li class="a_item">
    <div class="a_item_num"><?=$num_?></div>
    <div class="a_item_title"><?=$v['a_title']?></div>
   </li>
   <?php }?>
  </ul>
 </div>
<?php }else{echo '<div class="notific_b">Галерея не может быть отображена! В галерее нет ни одного аудио</div>'.PHP_EOL;}}?>
</div>

<?php
//комментарии
$this->load->helper('front/comments');
$comm=new Comments(array_replace(json_decode($conf_comments,TRUE),array('form'=>$comments)));
$comm->print_comments();
?>

</div>
</div>