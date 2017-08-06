<!DOCTYPE html>
<html>
<head>
<meta name="generator" content="Powered by «Tagra CMS» Development and design by kroloburet@gmail.com">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="<?=$robots?>">
<meta name="description" content="<?=$description?>">
<?php if(!empty($img_prev)){//метатеги для соцсетей?>
<meta itemprop="image" content="<?=$img_prev//картинка для google+?>">
<meta property="og:image" content="<?=$img_prev//картинка для facebook?>">
<meta name="twitter:image:src" content="<?=$img_prev//картинка для twitter?>"/>
<link rel="image_src" href="<?=$img_prev//картинка для vk?>">
<?php }?>
<title><?=$title?> | <?=$conf_site_name?></title>

<!--####### Презагрузка страницы #######-->
<style>#preload_lay{display:block;position:fixed;z-index:99999;top:0;left:0;width:100%;height:100%;background:#fff}#preload{box-sizing:border-box;margin:-50px;position:absolute;top:50%;left:50%;border:7px solid rgba(180,180,180,0.2);border-left-color:#E7D977;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);-webkit-animation:load 1.1s infinite linear;animation:load 1.1s infinite linear}#preload,#preload:after{border-radius:50%;width:100px;height:100px}@-webkit-keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}</style><script>window.onload=function(){document.getElementById("preload_lay").style.display="none";};</script>

<?php if(isset($css)&&$css){echo '<!--####### Пользовательские стили к этому документу #######-->'.PHP_EOL.$css.PHP_EOL;}?>
<!--[if lt IE 8]><link href="<?=base_url('UI_fraimwork/fonts/FontAwesome/ie7/ie7.css')?>" rel="stylesheet"><![endif]-->
</head>
<body id="body">

<!--
########### Оно тебе надо?
########### Лучше бы Пушкина почитал..)
-->

<div id="preload_lay"><div id="preload"></div></div>
<noscript><div class="notific_y">Внимание! В вашем браузере выключен Javascript. Для корректной работы сайта рекоммендуем включить Javascript.</div></noscript>
 
<?php if(!empty($front_menu_list)){//если массив меню не пустой?>
<!--####### Главное меню #######-->
<span class="mobile_menu noprint">&#8801;</span>
<nav><ul class="menu" style="max-width:<?=$conf_body_width?>px">
<?php 
$this->load->helper('front/menu');
display_menu_list($front_menu_list);
?>
</ul></nav>
<?php }?>

<!--####### Контент #######-->
<div id="content" style="max-width:<?=$conf_body_width?>px">
 