<!--####### Mine #######-->
<div class="mine_wrapper">
<div class="container" style="max-width:<?=htmlspecialchars($conf['body_width'])?>px">

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

</div>
</div>