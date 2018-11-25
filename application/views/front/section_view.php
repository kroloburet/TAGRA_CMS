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

<?php if($sub_sections){//если в разделе есть подразделы?>
<!--####### Раздел содержит подразделы #######-->
<h2>Раздел содержит подразделы:</h2>
<?php foreach($sub_sections as $v){?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?=base_url('section/'.$v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="Подраздел «<?=$v['title']?>»"></a>
<?php }else{?>
 <a href="<?=base_url('section/'.$v['alias'])?>" class="fa-copy section_sub_item_prev" title="Подраздел «<?=$v['title']?>»"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url('section/'.$v['alias'])?>">Подробнее</a>
 </div>
</div>
<?php }?>
<?php }?>

<?php if($sub_gallerys){//если в разделе есть галереи?>
<!--####### Раздел содержит галереи #######-->
<h2>Раздел содержит галереи:</h2>
<?php foreach($sub_gallerys as $v){
if($v['gallery_type']=='foto_folder'||$v['gallery_type']=='foto_desc'){$icon='fa-file-photo-o';}
if($v['gallery_type']=='audio'){$icon='fa-file-sound-o';}
if($v['gallery_type']=='video_yt'){$icon='fa-file-movie-o';}
?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?=base_url('gallery/'.$v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="Галерея «<?=$v['title']?>»"></a>
<?php }else{?>
 <a href="<?=base_url('gallery/'.$v['alias'])?>" class="<?=$icon?> section_sub_item_prev" title="Галерея «<?=$v['title']?>»"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url('gallery/'.$v['alias'])?>">Подробнее</a>
 </div>
</div>
<?php }?>
<?php }?>

<?php if($sub_pages){//если в разделе есть страницы?>
<!--####### Раздел содержит страницы #######-->
<h2>Раздел содержит страницы:</h2>
<?php foreach($sub_pages as $v){?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?= base_url($v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="Страница «<?=$v['title']?>»"></a>
<?php }else{?>
 <a href="<?=base_url($v['alias'])?>" class="fa-file-text-o section_sub_item_prev" title="Страница «<?=$v['title']?>»"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url($v['alias'])?>">Подробнее</a>
 </div>
</div>
<?php }?>
<?php }?>

<?php
//комментарии
$this->load->helper('front/comments');
$comm=new Comments(array_replace($conf_comments,array('form'=>$comments)));
$comm->print_comments();
?>

</div>
</div>