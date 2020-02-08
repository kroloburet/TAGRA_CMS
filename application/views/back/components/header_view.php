<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta name="generator" content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/Tagra_UI/style.css" rel="stylesheet">
    <link href="/Tagra_UI/fonts/FontAwesome/css/all.min.css" rel="stylesheet">
    <link href="/css/back/general.css" rel="stylesheet">
    <script src="<?= htmlspecialchars($conf['jq']) ?>"></script>
    <title><?= $data['view_title'] ?> | <?= $conf['site_name'] ?></title>

    <!-- презагрузка страницы -->
    <style>#preload_lay{display:block;position:fixed;z-index:99999;top:0;left:0;width:100%;height:100%;background-color:#fff}#preload{box-sizing:border-box;margin:-50px;position:absolute;top:50%;left:50%;border:7px solid rgba(180,180,180,0.2);border-left-color:var(--color-base);-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0);-webkit-animation:load 1.1s infinite linear;animation:load 1.1s infinite linear}#preload,#preload:after{border-radius:50%;width:100px;height:100px}@-webkit-keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes load{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}</style><script>window.onload = function() {document.getElementById("preload_lay").style.display = "none";};</script>

  </head>
  <body id="body">

    <!--
    ########### Оно тебе надо?
    ########### Лучше бы Пушкина почитал..)
    -->

    <div id="preload_lay"><div id="preload"></div></div>
    <noscript>
        <div class="notific_y">
          Внимание! В вашем браузере выключен Javascript. Для корректной работы сайта рекоммендуем включить Javascript.
        </div>
    </noscript>

    <!--
    ########### Header
    -->

    <div class="container">
      <ul class="menu">
        <li><a href="/admin">Конфигурация</a>
          <ul>
            <li><a href="/admin/sitemap">Генератор карты сайта</a></li>
          </ul>
        </li>
        <li><span>Языки</span>
          <ul>
            <li><a href="/admin/language/get_list">Управление языками</a>
              <ul>
                <li><a href="/admin/language/add_form">Добавить язык</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li><a href="/admin/menu/edit_form">Меню</a></li>
        <li><span>Материал</span>
          <ul>
            <li><a href="/admin/home/get_list">Управление страницами <q>Главная</q></a></li>
            <li><a href="/admin/contact/get_list">Управление страницами <q>Контакты</q></a></li>
            <li><a href="/admin/page/get_list">Управление страницами</a>
              <ul>
                <li><a href="/admin/page/add_form">Добавить страницу</a></li>
              </ul>
            </li>
            <li><a href="/admin/section/get_list">Управление разделами</a>
              <ul>
                <li><a href="/admin/section/add_form">Добавить раздел</a></li>
              </ul>
            </li>
            <li><a href="/admin/gallery/get_list">Управление галереями</a>
              <ul>
                <li><a href="/admin/gallery/add_form">Добавить галерею</a></li>
              </ul>
            </li>
            <li><a href="#" onclick="files();return false">Менеджер файлов</a></li>
          </ul>
        </li>
        <li><a href="/admin/comment/get_list">Комментарии <b id="count_new_comments"></b></a></li>
        <li class="user_nav">
          <span><i class="fas fa-user-circle" title="А пальцем тыкать некультурно! ;Р"></i>&nbsp;<?= $conf['status'] ?></span>
          <ul>
            <li><a href="/" target="_blank">Перейти на сайт</a></li>
            <li><a href="/Tagra_UI/index.html" target="_blank">О системе</a></li>
            <li><a href="/admin/logout" class="red">Выйти</a></li>
          </ul>
        </li>
      </ul>

      <!-- короткая справка по работе с системой -->
      <div class="notific_b" id="notific_work_info">Привет, <?= $conf['status'] ?>!<br>
        Это короткая справка-рекомендация о том, как начать работу в системе.<br>
        <a href="#" onclick="TUI.Toggle('#work_info');return false">Показать справку</a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="#" onclick="off_notific();return false">Не сейчас</a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="#" onclick="localStorage.setItem('notific_work_info', 'off');off_notific();return false">Больше не предлагать</a>
        <div id="work_info" hidden>
          <h3>Начало</h3>
          <hr>
          <p>Задайте основные настройки сайта на странице <q>Конфигурация</q>. Хорошо продумайте структуру материалов на сайте. На любом этапе создания материалов вы можете загружать файлы в формате: jpg, jpeg, gif, png, pdf, svg, mp3, wav, mp4, ogg, webm, rar, zip, txt, doc, docx, xls, xlsx, csv с помощью менеджера файлов (в главном меню: Материал/Менеджер файлов). В менеджере файлов можно создавать папки с неограниченной вложенностью структурируя ваши файлы, просматривать, фильтровать, редактировать применяя простые фильтры, изменять размер, обрезать, отражать и поварачивать. <span class="red">Создавая и переименовывая папки и файлы, не используйте кириллицу и пробелы в именах!</span></p>
          <h3>Языки</h3>
          <hr>
          <p>Ваш сайт может быть мультиязычным. Вы можете управлять и добавлять один или более языков на любом этапе (в главном меню: Языки/Управление языками). Языковые версии сайта могут отличаться не только локализацией интерфейса и материалами, но и меню.</p>
          <h3>Разделы</h3>
          <hr>
          <p>Если на сайте планируется много материалов &mdash; начните с разделов (в главном меню: Материал/Управление разделами/Добавить раздел). Разделы могут содержать неограниченное количество других разделов, страницы и галереи.</p>
          <h3>Галереи</h3>
          <hr>
          <p>Если у вас есть много фото, видео или аудио &mdash; создайте галереи (в главном меню: Материал/Управление галереями/Добавить галерею). Посетители ваших галерей смогут слушать аудио, просматривать фото и видео с любого устройства.</p>
          <h3>Страницы</h3>
          <hr>
          <p>Затем создавайте страницы (в главном меню: Материал/Управление страницами/Добавить страницу). Страницы <q>Главная</q> и <q>Контакты</q> (в главном меню: Материал/Страница <q>Главная</q>, Страница <q>Контакты</q>) уже созданы и нуждаются только в наполнении вашим контентом. В отличие от обычных страниц и разделов, созданных вами, <q>Главная</q> и <q>Контакты</q> не могут быть удалены, их можно только редактировать.</p>
          <h3>Меню</h3>
          <hr>
          <p>Когда вы создадите все необходимые материалы, время создать меню (в главном меню: Меню). В хорошо продуманном меню так же важна структура. Каждый пункт меню может содержать другие пункты. Это дает вам возможность создать структуру и порядок ссылок на материалы по вашему усмотрению. Однако следует учесть, что слишком глубокая вложенность пунктов может повлечь трудности в поике пункта у посетителей вашего сайта. Рекомендуемая вложенность &mdash; не более 3-х уровней от самого верхнего (родительского) пункта.</p>
          <h3>Комментарии</h3>
          <hr>
          <p>Управляйте комментариями ваших материалов (в главном меню: Комментарии). Создавая и редактируя материал, вы можете разрешать или запрещать комментировать материал. Новые комментарии нуждаются в вашей премодерации, комментарий не будет отображен на странице материала до тех пор, пока вы не опубликуете его. Так вы сможете отсекать спам и нежелательные отзывы. Если хотите чтобы уведомление о каждом новом комментарии приходило вам на e-mail, выберите эту опцию на странице конфигурации. Вы так же можете запретить премодерацию новых комментариев, в этом случае новые комментарии будут сразу отображены в материале.</p>
          <h3>Генератор карты сайта</h3>
          <hr>
          <p>Чтобы новые материалы быстрее попадали в Google, Yandex и т.п, система генерирует файл sitemap.xml и изменяет его каждый раз, когда вы создаете, изменяете или удаляете материал. Все это происходит автоматически. Вы можете настроить генератор и просматривать карту сайта (в главном меню: Конфигурация/Генератор карты сайта).</p>
          <h3>Напоследок</h3>
          <hr>
          <p>Информация о системе, используемых скриптах и руководство для верстальщика (в главном меню: <?= $conf['status'] ?>/О системе).</p>
          <div class="algn_r"><em>Приятного использования!</em></div>
          <a href="#" onclick="TUI.Toggle('#work_info');return false">Скрыть справку</a>
        </div>
      </div>
