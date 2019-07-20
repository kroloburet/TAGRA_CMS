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

<?php if($data['sub_sections']){//если в разделе есть подразделы?>
<!--####### Раздел содержит подразделы #######-->
<h2><?=$lexic['section']['sub_sections_title']?></h2>
<?php foreach($data['sub_sections'] as $v){?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?=base_url('section/'.$v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_section'].' "'.$v['title'].'"')?>"></a>
<?php }else{?>
 <a href="<?=base_url('section/'.$v['alias'])?>" class="fa-copy section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_section'].' "'.$v['title'].'"')?>"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url('section/'.$v['alias'])?>"><?=$lexic['section']['more']?></a>
 </div>
</div>
<?php }?>
<?php }?>

<?php if($data['sub_gallerys']){//если в разделе есть галереи?>
<!--####### Раздел содержит галереи #######-->
<h2><?=$lexic['section']['sub_gallerys_title']?></h2>
<?php foreach($data['sub_gallerys'] as $v){
if($v['gallery_type']=='foto_folder'||$v['gallery_type']=='foto_desc'){$icon='fa-file-photo-o';}
if($v['gallery_type']=='audio'){$icon='fa-file-sound-o';}
if($v['gallery_type']=='video_yt'){$icon='fa-file-movie-o';}
?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?=base_url('gallery/'.$v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_gallery'].' "'.$v['title'].'"')?>"></a>
<?php }else{?>
 <a href="<?=base_url('gallery/'.$v['alias'])?>" class="<?=$icon?> section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_gallery'].' "'.$v['title'].'"')?>"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url('gallery/'.$v['alias'])?>"><?=$lexic['section']['more']?></a>
 </div>
</div>
<?php }?>
<?php }?>

<?php if($data['sub_pages']){//если в разделе есть страницы?>
<!--####### Раздел содержит страницы #######-->
<h2><?=$lexic['section']['sub_pages_title']?></h2>
<?php foreach($data['sub_pages'] as $v){?>
<div class="section_sub_item clear">
<?php if($v['img_prev']){?>
 <a href="<?=base_url($v['alias'])?>" style="background-image:url(<?=$v['img_prev']?>);" class="section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_page'].' "'.$v['title'].'"')?>"></a>
<?php }else{?>
 <a href="<?=base_url($v['alias'])?>" class="fa-file-text-o section_sub_item_prev" title="<?=htmlspecialchars($lexic['section']['sub_page'].' "'.$v['title'].'"')?>"></a>
<?php }?>
 <div class="section_sub_item_desc">
  <h3><?=$v['title']?></h3>
  <div><?=$v['description']?>...</div>
  <a href="<?=base_url($v['alias'])?>"><?=$lexic['section']['more']?></a>
 </div>
</div>
<?php }?>
<?php }?>

<?php
$this->load->helper('front/comments');
$comm=new Comments(array_replace($conf['comments'],['form'=>$data['comments']]));
$comm->print_comments();
?>

</div>
</div>