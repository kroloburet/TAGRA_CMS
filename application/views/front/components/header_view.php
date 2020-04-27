<!DOCTYPE html>
<html lang="<?= $data['lang'] ?>">
<head>
    <meta name="generator"
          content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="<?= $data['robots'] ?>">
    <meta name="description" content="<?= htmlspecialchars($data['description']) ?>">
    <title><?= $data['title'] ?> | <?= $conf['site_name'] ?></title>

    <!--
    ########### Нreflangs
    -->

    <link rel="alternate" hreflang="<?= $data['lang'] ?>" href="<?= current_url() ?>">
    <?php
    if (isset($data['versions']) && $data['versions']) {
        $versions = json_decode($data['versions'], true);
        foreach ($versions as $lang => $v) {
            echo '<link rel="alternate" hreflang="' . $lang . '" href="' . base_url($v['url']) . '">' . PHP_EOL;
        }
    }
    ?>

    <?php
    if ($conf['markup_data']) {
        $this->load->helper('front/markup_data');
        (new Markup_data())->print();
    } ?>

    <!--
    ########### Презагрузка страницы
    -->

    <style>
        #pagePreloaderBox {
            display: block;
            position: fixed;
            z-index: 99999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
        }

        #pagePreloader {
            box-sizing: border-box;
            margin: -50px;
            position: absolute;
            top: 50%;
            left: 50%;
            border: 7px solid rgba(180, 180, 180, .2);
            border-left-color: var(--color-base);
            transform: translateZ(0);
            animation: preloader 1s infinite linear;
        }

        #pagePreloader, #pagePreloader::after {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        @keyframes preloader {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        window.onload = () => document.getElementById('pagePreloaderBox').style.display = 'none';
    </script>

    <!--
    ########### Макет
    -->

    <style>
        .container {
            max-width: <?= htmlspecialchars($conf['body_width']) ?>px;
        }

        #layouts {
            grid-template: auto/<?= isset($data['layout_l_width'])
            ? htmlspecialchars($data['layout_l_width']) . '% 1fr'
            : htmlspecialchars($conf['layout_l_width']) . '% 1fr' ?>;
        }
    </style>

    <?php if (isset($data['css']) && $data['css']) {
        echo "
    <!--
    ########### Пользовательский CSS для материала
    -->
    \n{$data['css']}\n";
    } ?>

</head>
<body id="body">

<!--
########### Оно тебе надо?
########### Лучше бы Пушкина почитал..)
-->

<div id="pagePreloaderBox">
    <div id="pagePreloader"></div>
</div>
<noscript>
    <div class="TUI_notice-y"><?= $lexic['basic']['nojs'] ?></div>
</noscript>

<!--
########### Header
-->

<header>
    <div class="container header_container TUI_noprint">
        <?php
        $this->load->helper('front/nav');
        $nav = new nav();
        $nav->langs();
        $nav->menu();
        ?>
    </div>
</header>

