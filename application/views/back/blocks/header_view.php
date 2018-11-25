<!DOCTYPE html>
<html>
 <head>
  <meta name="generator" content="Powered by «Tagra CMS» Development and design by kroloburet@gmail.com">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="none">
  <link href="<?=base_url('UI_fraimwork/css.css')?>" rel="stylesheet">
  <link href="<?=base_url('css/back/general.css')?>" rel="stylesheet">
  <link href="<?=base_url('UI_fraimwork/fonts/FontAwesome/style.css')?>" rel="stylesheet">
  <!--[if lt IE 8]>
	  <link href="<?=base_url('UI_fraimwork/fonts/FontAwesome/ie7/ie7.css')?>" rel="stylesheet">
	 <!--<![endif]-->
  <script src="<?=$conf_jq?>"></script>
  <?php if(current_url()==base_url('admin/contact/edit_form')){//если текущая страница — "Контакты"?>
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
  <script src="https://maps.googleapis.com/maps/api/js?language=ru&key=<?=$conf_gapi_key?>"></script>
  <?php }?>
  <!--HTML5-теги и медиа-запросы для IE9 и ниже-->
  <!--[if lt IE 9]>
   <script src="<?=base_url('scripts/libs/html5shiv.min.js')?>"></script>
   <script src="<?=base_url('scripts/libs/respond.min.js')?>"></script>
  <![endif]-->
  <title><?=$conf_title?> | <?=$conf_site_name?></title>

<!--####### Презагрузка страницы #######-->
<style>#preload_lay{display:block;position:fixed;z-index:99999;top:0;left:0;width:100%;height:100%;background:#fff}#preload{box-sizing:border-box;margin:-50px;position:absolute;top:50%;left:50%;border:7px solid rgba(180,180,180,0.2);border-left-color:#E7D977;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);-webkit-animation:load 1.1s infinite linear;animation:load 1.1s infinite linear}#preload,#preload:after{border-radius:50%;width:100px;height:100px}@-webkit-keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}</style><script>window.onload=function(){document.getElementById("preload_lay").style.display="none";};</script>

 </head>
 <body id="body">

  <div id="preload_lay"><div id="preload"></div></div>
  <noscript><div class="notific_y">Внимание! В вашем браузере выключен Javascript. Для корректной работы сайта рекоммендуем включить Javascript.</div></noscript>

<!--#######################
Оно тебе надо?
Лучше бы Пушкина почитал..)
########################-->
    <span class="mobile_menu">&#8801;</span>
    <nav>
     <ul class="menu">
      <li><a href="<?=base_url('admin')?>">Конфигурация</a>
       <ul class="sub_menu">
        <li><a href="<?=base_url('admin/sitemap')?>">Генератор карты сайта</a></li>
       </ul>
      </li>
      <li><a href="<?=base_url('admin/menu/edit_form')?>">Меню</a></li>
      <li><span>Материал</span>
       <ul class="sub_menu">
        <li><a href="<?=base_url('admin/page/get_list')?>">Управление страницами</a>
         <ul class="sub_menu">
          <li><a href="<?=base_url('admin/page/add_form')?>">Добавить страницу</a></li>
         </ul>
        </li>
        <li><a href="<?=base_url('admin/section/get_list')?>">Управление разделами</a>
         <ul class="sub_menu">
          <li><a href="<?=base_url('admin/section/add_form')?>">Добавить раздел</a></li>
         </ul>
        </li>
        <li><a href="<?=base_url('admin/gallery/get_list')?>">Управление галереями</a>
         <ul class="sub_menu">
          <li><a href="<?=base_url('admin/gallery/add_form')?>">Добавить галерею</a></li>
         </ul>
        </li>
        <li><a href="<?=base_url('admin/contact/edit_form')?>">Страница «Контакты»</a></li>
        <li><a href="<?=base_url('admin/home/edit_form')?>">Страница «Главная»</a></li>
        <li><a href="#" onclick="files();return false">Менеджер файлов</a></li>
       </ul>
      </li>
      <li><a href="<?=base_url('admin/comment/get_list')?>">Комментарии <b id="count_new_comments"></b></a></li>
      <li class="user_nav"><span><?=$conf_status?> &#8801;</span>
       <ul class="sub_menu">
     <li><?=anchor('/','Перейти на сайт','target="_blank"')?></li>
     <li><?=anchor('UI_fraimwork/info.html','О системе','target="_blank"')?></li>
     <li><?=anchor('admin/logout','Выйти','class="red"')?></li>
    </ul>
   </li>
  </ul>
 </nav>
<!--########### CONTAINER ###########-->
<div class="container">
 <!--Короткая справка по работе с системой-->
 <div class="notific_b" id="notific_work_info">Здравствуйте, <?=$conf_status?>!<br>Это короткая справка-рекомендация о том, как начать работу в системе.<br>
  <a href="#" onclick="opn_cls('work_info');return false">Показать справку</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="off_notific();return false">Не сейчас</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="localStorage.setItem('notific_work_info','off');off_notific();return false">Больше не предлагать</a>
  <div id="work_info" class="opn_cls">
   <h3>Начало</h3>
   <hr>
   <p>Задайте основные настройки сайта на странице <q>Конфигурация</q>. Хорошо продумайте структуру материалов на сайте. На любом этапе создания материалов Вы можете загружать файлы в формате: jpg, jpeg, gif, png, pdf, svg, mp3, wav, mp4, ogg, webm, rar, zip, txt, doc, docx, xls, xlsx, csv с помощью менеджера файлов (в главном меню: Материал/Менеджер файлов). В менеджере файлов можно создавать папки с неограниченной вложенностью структурируя Ваши файлы, просматривать, фильтровать, редактировать применяя простые фильтры, изменять размер, обрезать, отражать и поварачивать. <span class="red">Создавая и переименовывая папки и файлы, не используйте кириллицу и пробелы в именах!</span></p>
   <h3>Разделы</h3>
   <hr>
   <p>Если на сайте планируется много материалов — начните с разделов (в главном меню: Материал/Управление разделами/Добавить раздел). Разделы могут содержать неограниченное количество других разделов, страницы и галереи.</p>
   <h3>Галереи</h3>
   <hr>
   <p>Если у Вас есть много фото, видео или аудио — создайте галереи (в главном меню: Материал/Управление галереями/Добавить галерею). Посетители Ваших галерей смогут слушать аудио, просматривать фото и видео с любого устройства.</p>
   <h3>Страницы</h3>
   <hr>
   <p>Затем создавайте страницы (в главном меню: Материал/Управление страницами/Добавить страницу). Страницы <q>Главная</q> и <q>Контакты</q> (в главном меню: Материал/Страница «Главная», Страница «Контакты») уже созданы и нуждаются только в наполнении Вашим контентом. В отличие от обычных страниц и разделов, созданных Вами, <q>Главная</q> и <q>Контакты</q> не могут быть удалены, их можно только редактировать.</p>
   <h3>Меню</h3>
   <hr>
   <p>Когда Вы создадите все необходимые материалы, время создать меню (в главном меню: Меню). В хорошо продуманном меню так же важна структура. Каждый пункт меню может содержать другие пункты. Это дает Вам возможность создать структуру и порядок ссылок на материалы по Вашему усмотрению. Однако следует учесть, что слишком глубокая вложенность пунктов может повлечь трудности в поике пункта у посетителей Вашего сайта, громоздкость. Рекомендуемая вложенность — не более 3-х уровней от самого верхнего (родительского) пункта.</p>
   <h3>Комментарии</h3>
   <hr>
   <p>Управляйте комментариями Ваших материалов (в главном меню: Комментарии). Создавая и редактируя материал, Вы можете разрешать или запрещать комментировать материал. Новые комментарии нуждаются в Вашей премодерации, комментарий не будет отображен на странице материала до тех пор, пока Вы не опубликуете его. Так Вы сможете отсекать спам и нежелательные отзывы. Если хотите чтобы уведомление о каждом новом комментарии приходило Вам на e-mail, выберите эту опцию на странице конфигурации. Вы так же можете запретить премодерацию новых комментариев, в этом случае новые комментарии будут сразу отображены в материале.</p>
   <h3>Генератор карты сайта</h3>
   <hr>
   <p>Чтобы новые материалы быстрее попадали в Google, Yandex и т.п, система генерирует файл sitemap.xml и изменяет его каждый раз, когда Вы создаете, изменяете или удаляете материал. Все это происходит автоматически. Вы можете настроить генератор и просматривать карту сайта (в главном меню: Конфигурация/Генератор карты сайта).</p>
   <h3>Напоследок</h3>
   <hr>
   <p>Информация о системе, используемых скриптах и руководство для верстальщика (в главном меню: <?=$conf_status?>/О системе).</p>
   <div class="algn_r"><em>Приятного использования!</em></div>
   <a href="#" onclick="opn_cls('work_info');return false">Скрыть справку</a>
  </div>
 </div>
