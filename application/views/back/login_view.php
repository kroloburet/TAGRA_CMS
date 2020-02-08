<!DOCTYPE html>
<html>
  <head>
    <meta name="generator" content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/Tagra_UI/style.css" rel="stylesheet">
    <link href="/css/back/general.css" rel="stylesheet">
    <link href="/Tagra_UI/fonts/FontAwesome/css/all.min.css" rel="stylesheet">
    <title>Авторизация</title>

    <!--
    ########### Разметка структурированных данных
    -->

    <!-- google -->
    <meta itemprop="name" content="Tagra CMS">
    <meta itemprop="description" content="Авторизация в системе Tagra CMS">
    <meta itemprop="image" content="<?= base_url('img/tagra_share.jpg') ?>">
    <!-- twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="Tagra CMS">
    <meta name="twitter:title" content="Авторизация">
    <meta name="twitter:description" content="Авторизация в системе Tagra CMS">
    <meta name="twitter:image:src" content="<?= base_url('img/tagra_share.jpg') ?>">
    <meta name="twitter:domain" content="<?= base_url() ?>">
    <!-- facebook -->
    <meta property="og:title" content="Авторизация">
    <meta property="og:description" content="Авторизация в системе Tagra CMS">
    <meta property="og:image" content="<?= base_url('img/tagra_share.jpg') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:site_name" content="Tagra CMS">
    <!-- другие -->
    <link rel="image_src" href="<?= base_url('img/tagra_share.jpg') ?>">

    <style>
      form{width:40%;margin:15% auto 11% auto;}
      .button{margin-top:0;}
      input,button{text-align:center;font-size:120% !important;}
      button,input[type="submit"]{width:50%;float:left;}
      button{width:auto;}
      .opts_pan{text-align:right;margin-top:3px;}
      #see_pass{width:auto;margin:0 5px 0 0;}
      #sand_pass{display:none;}
      @media(max-width:800px){form{width:75%;}}
    </style>
  </head>
  <body>
    <div class="authoriz_box">

      <!--
      ########### Авторизация
      -->

      <form method="POST" id="login_form">
        <label class="input">
          <input type="text" name="lgn" placeholder="логин" required>
        </label>
        <label class="input">
          <input type="password" name="pswd" id="pass" placeholder="пароль" required>
        </label>
        <div id="login_msg"><?= isset($msg) && !empty($msg) ? $msg : '' ?></div>
        <input type="submit" value="Войти">
        <div class="opts_pan">
          <a href="#" class="fas fa-eye" title="Показать пароль" id="see_pass"></a>&nbsp;&nbsp;
          <a href="#" class="fas fa-unlock-alt" title="Восстановление доступа" id="show_sand_pass"></a>&nbsp;&nbsp;
          <a href="/" class="fas fa-home" title="Перейти на главную сайта"></a>
        </div>
      </form>

      <!--
      ########### Восстановление доступа
      -->

      <form id="sand_pass">
        Восстановление доступа
        <label class="input">
          <input type="email" name="send_pass_mail" placeholder="e-mail пользователя" required>
        </label>
        <input name="fuck_bot">
        <div id="sand_pass_msg"></div>
        <div class="button inline">
          <button type="submit">Отправить</button>
          <button type="button" id="no_subm_pass">Отмена</button>
        </div>
      </form>

      <!--
      ########### Footer
      -->

      <div id="copy">
        Веб-разработка и дизайн
        <a href="mailto:kroloburet@gmail.com">
          <img src="/img/i.jpg" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com
        </a><br>
        <img src="/img/tagra.svg" alt="Tagra CMS"> Tagra CMS
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
      // показать/скрыть пароль в поле
      $('#see_pass').on('click', function(e) {
        e.preventDefault();
        let btn = $(this),
            pass = $('#pass').get(0);
        if (pass.type === 'password') {
          pass.type = 'text';
          btn.addClass('fa-eye-slash').attr('title', 'Скрыть пароль');
        } else {
          pass.type = 'password';
          btn.removeClass('fa-eye-slash').attr('title', 'Показать пароль');
        }
      });
      // показать/скрыть форму "Восстановить доступ"
      $('#show_sand_pass, #no_subm_pass').on('click', function(e) {
        e.preventDefault();
        $('#login_form, #sand_pass').slideToggle(200);
      });
      // отправить запрос на восстановление доступа
      $('#sand_pass').on('submit', function(e) {
        e.preventDefault();
        let f = $(this),
            msg = f.find('#sand_pass_msg'),
            control = f.find('div.button'),
            control_html = control.html();
        control.html('<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...');
        $.ajax({
          url: '/do/change_login',
          type: 'post',
          data: f.serialize(),
          dataType: 'json',
          success: function(resp) {
            switch (resp.status) {
              case 'bot':
                f.find('[name=send_pass_mail]').val('');
                msg.html(`<div class="notific_r mini full">Ой! Вы робот?! Вам здесь не рады..(</div>`);
                break;
              case 'nomail':
                msg.html(`<div class="notific_r mini full">
                  Ой! Ошибка..(<br>Пользователя с таким email нет в системе.</div>`);
                break;
              case 'noaccess':
                msg.html(`<div class="notific_r mini full">
                  Упс! Администратор запретил вам вход и все действия от имени модератора.</div>`);
                break;
              case 'noedit':
                msg.html(`<div class="notific_r mini full">
                  Ой! Ошибка..(<br>Не удалось сбросить логин/пароль. Повторите попытку.</div>`);
                break;
              case 'nosend':
                msg.html(`<div class="notific_r mini full">
                  Ой! Ошибка..(<br>Не удалось отправить логин/пароль. Повторите попытку.</div>`);
                break;
              case 'ok':
                f.find('[name=send_pass_mail]').val('');
                msg.html(`<div class="notific_g mini full">${resp.html}</div>`);
                setTimeout(function() {// очистить сообщение об отправке, скрыть форму
                  msg.empty();
                  $('#login_form,#sand_pass').slideToggle();
                }, 5000);
            }
          },
          error: function(xhr, status, thrown) {
            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
          }
        });
        control.html(control_html);
      });
    </script>
  </body>
</html>
