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
