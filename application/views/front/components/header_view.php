<!DOCTYPE html>
<html lang="<?= $data['lang'] ?>">
  <head>
    <meta name="generator" content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="<?= $data['robots'] ?>">
    <meta name="description" content="<?= htmlspecialchars($data['description']) ?>">
    <title><?= $data['title'] ?> | <?= $conf['site_name'] ?></title>

    <?php
    if ($conf['markup_data']) {
        $this->load->helper('front/markup_data');
        (new Markup_data())->print();
    } ?>

    <!-- презагрузка страницы -->
    <style>#preload_lay{display:block;position:fixed;z-index:99999;top:0;left:0;width:100%;height:100%;background-color:#fff}#preload{box-sizing:border-box;margin:-50px;position:absolute;top:50%;left:50%;border:7px solid rgba(180,180,180,0.2);border-left-color:var(--color-base);-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);-webkit-animation:load 1.1s infinite linear;animation:load 1.1s infinite linear}#preload,#preload:after{border-radius:50%;width:100px;height:100px}@-webkit-keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}</style>
    <script>window.onload = function() {document.getElementById("preload_lay").style.display = "none";};</script>

    <!-- шаблон -->
    <style>
      .container{max-width:<?= htmlspecialchars($conf['body_width']) ?>px;}
      #layout_l{width:<?= isset($data['layout_l_width'])
                            ? htmlspecialchars($data['layout_l_width'])
                            : htmlspecialchars($conf['layout_l_width']) ?>%;}
    </style>

    <?php if (isset($data['css']) && $data['css']) {
        echo '<!-- пользовательские стили материала -->'. PHP_EOL . $data['css'] . PHP_EOL;
    } ?>

  </head>
  <body id="body">

    <!--
    ########### Оно тебе надо?
    ########### Лучше бы Пушкина почитал..)
    -->

    <div id="preload_lay">
      <div id="preload"></div>
    </div>
    <noscript>
      <div class="notific_y"><?= $lexic['basic']['nojs'] ?></div>
    </noscript>

    <!--
    ########### Header
    -->

    <div class="header_wrapper">
      <div class="header_container container">
        <?php
        $this->load->helper('front/nav');
        $nav = new nav();
        $nav->langs();
        $nav->menu();
        ?>
      </div>
    </div>
