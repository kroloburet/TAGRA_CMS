<!--####### Mine #######-->
<div class="mine_wrapper">
<div class="container" style="max-width:<?=htmlspecialchars($conf['body_width'])?>px">

<?php if($conf['breadcrumbs']['public']=='on'){
 $this->load->helper('front/breadcrumbs');
 new breadcrumbs();
}?>

<!--####### Headline #######-->
<div id="headline">
<h1><?=$data['title']?></h1>
<?php if($conf['addthis_share']&&$data['addthis_share']=='on'){?>
<div class="addthis_layout noprint"><?=$conf['addthis_share']?></div>
<?php }?>
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

<?php if($data['links']){//есть связанные ссылки
$l_opt=json_decode($data['links'],true);
echo '<!--####### Связанные ссылки #######-->'.PHP_EOL;
echo '<div id="links_block" class="noprint">'.PHP_EOL;
echo $l_opt['title']?'<h2>'.$l_opt['title'].'</h2>'.PHP_EOL:FALSE;
foreach($l_opt as $k=>$v){
 echo $k!=='title'?'<a href="'.$v['url'].'" class="links_item fa-chain">&nbsp;'.$v['title'].'</a>'.PHP_EOL:FALSE;
}
echo '</div>'.PHP_EOL;
}?>

<!--####### Галерея #######-->
<div id="FVGallery_layout">
<?php
switch($data['gallery_type']){

 case 'foto_folder'://галерея из папки с изображениями
  if($data['gallery_opt']){//есть опции
   $dir=json_decode($data['gallery_opt'],true)['f_folder'];//папка с изображениями
   $dir_handle=@opendir('.'.$dir) or die($lexic['gallery']['error_path_img_dir']);//попытка открыть папку
   while($file=readdir($dir_handle)){//поиск по файлам
    if($file=='.'||$file=='..')continue;//пропустить ссылки на другие папки
    if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png|\.svg)$/i',$file))continue;//если тип не разрешен
     echo '<div class="FVG_item FVG_item_f_folder">'.PHP_EOL;
    echo '<img src="/img/noimg.jpg" alt="'.$file.'" data-src="'.$dir.'/'.$file.'">'.PHP_EOL;
    echo '<textarea class="opt" hidden>{"f_url":"'.$dir.'/'.$file.'"}</textarea>'.PHP_EOL;
     echo '</div>'.PHP_EOL;
    }
   closedir($dir_handle);//закрыть папку
  }else{echo '<div class="notific_b">'.$lexic['gallery']['noimgs'].'</div>'.PHP_EOL;}
 break;

 case 'foto_desc'://галерея изображений с описаниями
  if($data['gallery_opt']){//есть опции
   foreach(json_decode($data['gallery_opt'],true) as $v){
    echo '<div class="FVG_item FVG_item_f_desc">'.PHP_EOL;
    echo $v['f_title']||$v['f_desc']?'<div class="FVG_item_f_desc_preview"><h3>'.$v['f_title'].'</h3>'.$v['f_desc'].'</div>'.PHP_EOL:FALSE;
    echo '<img src="/img/noimg.jpg" alt="'.$v['f_title'].'" data-src="'.$v['f_url'].'">'.PHP_EOL;
    echo '<textarea class="opt" hidden>'.json_encode($v).'</textarea>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
   }
  }else{echo '<div class="notific_b">'.$lexic['gallery']['noimgs'].'</div>'.PHP_EOL;}
 break;

 case 'video_yt'://галерея youtube
  if($data['gallery_opt']){//есть опции
   foreach(json_decode($data['gallery_opt'],true) as $v){
    echo '<div class="FVG_item FVG_item_v_yt">'.PHP_EOL;
    echo '<img src="/img/noimg.jpg" data-src="https://img.youtube.com/vi/'.$v['yt_id'].'/mqdefault.jpg">'.PHP_EOL;
    echo '<textarea class="opt" hidden>'.json_encode($v).'</textarea>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
   }
  }else{echo '<div class="notific_b">'.$lexic['gallery']['novideos'].'</div>'.PHP_EOL;}
  break;

 case 'audio'://галерея audio
  if($data['gallery_opt']){//есть опции?>
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
     <?=htmlspecialchars($lexic['gallery']['nohtml5_audio'])?>
    </audio>
    <div class="a_nav">
     <a class="a_prev">&laquo;</a>
     <a class="a_next">&raquo;</a>
    </div>
   </div>
  </div>
  <ul class="a_items">
   <?php $num=1;foreach(json_decode($data['gallery_opt'],true)as$v){$num_=$num<=9?'0'.$num++.'.':$num++.'.';?>
   <li class="a_item">
    <div class="a_item_num"><?=$num_?></div>
    <div class="a_item_title"><?=$v['a_title']?></div>
   </li>
   <?php }?>
  </ul>
 </div>
<?php }else{echo '<div class="notific_b">'.$lexic['gallery']['noaudios'].'</div>'.PHP_EOL;}

}?>
</div>

<?php
$this->load->helper('front/comments');
$comm=new Comments(array_replace($conf['comments'],['form'=>$data['comments']]));
$comm->print_comments();
?>

</div>
</div>