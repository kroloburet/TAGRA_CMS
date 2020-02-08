<!DOCTYPE html>
<html>
  <head>
    <meta name="generator" content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/Tagra_UI/style.css" rel="stylesheet">
    <link href="/Tagra_UI/fonts/FontAwesome/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Установка системы управления контентом</title>
  </head>
  <body>
    <div id="content">
      <h1>Установка системы управления контентом</h1>
      <p>Заполните все поля (одиночные и двойные кавычки не допускаются) и нажмите на кнопку <q>Установить систему</q> Если ви все правильно сделали и не возникло каких-либо программных сбоев, вы перейдете на страницу, где сможете увидеть статус установки. Если на этом или другом этапе у вас возникли проблемы &mdash; обратитесь к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p>

      <form class="instal_form container" action="process.php" method="post">

        <!--
        ########### Основное
        -->

        <div class="touch">
          <h2>База данных</h2>
          <label class="input">
            <div>Имя базы данных</div>
            <input type="text" name="db_name" required>&nbsp;
            <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              База с этим именем уже должна существовать
            </pre>
          </label>
          <label class="input">
            <div>Хост базы данных</div>
            <input type="text" name="db_host" value="localhost" required>
          </label>
          <label class="input">
            <div>Пользлватель базы данных</div>
            <input type="text" name="db_user" required>
          </label>
          <label class="input">
            <div>Пароль пользователя базы данных</div>
            <input type="text" name="db_pass" required>
          </label>
          <label class="input">
            <div>Префикс таблиц базы данных</div>
            <input type="text" name="db_tabl_prefix" required>&nbsp;
            <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              В процессе установки система создаст необходимые
              для ее работы таблицы в указанной базе данных.
              Чтобы имена создаваемых таблиц были уникальны и
              не возникло конфликтов с уже существующими таблицами
              &mdash; введите короткий префикс на латинице,
              не используя специальные символы.
              Пример: good
            </pre>
          </label>
        </div>

        <!--
        ########### Начальные настройки
        -->

        <div class="touch">
          <h2>Начальные настройки</h2>
          <label class="input">
            <div>Имя сайта</div>
            <input type="text" name="site_name" placeholder="Пример: Мой официальный сайт"
                   value="<?= $_SERVER['SERVER_NAME'] ?>" required>
          </label>
          <label class="input">
            <div>Домен</div>
            <input type="url" name="domen" value="<?= 'http'
                    . (empty($_SERVER['HTTPS']) ? '' : 's') . '://'
                    . $_SERVER['SERVER_NAME'] . '/' ?>" required>&nbsp;
            <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              URL к директории в которой будет запущена система
              (куда вы выгрузили все файлы)
              Внимание! Обязательно с <q>http<?= empty($_SERVER['HTTPS']) ? '' : 's' ?>://</q> и <q>/</q> в конце.
            </pre>
          </label>
          <label class="input">
            <div>Логин администратора</div>
            <input type="text" name="admin_name" value="admin" required>
          </label>
          <label class="input">
            <div>Пароль администратора</div>
            <input type="text" name="admin_pass" id="admin_pass" value="admin" required>&nbsp;
            <a href="#" onclick="gen_pass('#admin_pass');return false" class="fas fa-sync-alt" title="Генерировать пароль"></a>
          </label>
          <label class="input">
            <div>E-mail администратора</div>
            <input type="email" name="admin_mail" required>
          </label>
          <label class="input">
            <div>Логин модератора</div>
            <input type="text" name="moder_name" value="moderator" required>
          </label>
          <label class="input">
            <div>Пароль модератора</div>
            <input type="text" name="moder_pass" id="moder_pass" value="moderator" required>&nbsp;
            <a href="#" onclick="gen_pass('#moder_pass');return false" class="fas fa-sync-alt" title="Генерировать пароль"></a>
          </label>
          <label class="input">
            <div>E-mail модератора</div>
            <input type="email" name="moder_mail" required>
          </label>
        </div>
        <div class="button">
          <button type="submit">Установить систему</button>
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
    <script src="/Tagra_UI/script.js"></script>
    <script>
        /**
         * Генерировать пароль и вставить в поле
         *
         * @param {string} id Селектор поля для вставки пароля
         * @returns {void}
         */
        function gen_pass(id) {
          let passwd = '',
              chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~@#$[]_-';
          for (let i = 1; i < 11; i ++) {
            let c = Math.floor(Math.random() * chars.length + 1);
            passwd += chars.charAt(c);
          }
          $(id).val(passwd);
        }

        /**
         * Удалять одиночные и двойные кавычки в полях
         */
        $('.instal_form input').on('keyup change', function() {
          let el = $(this);
          if (! /"|'/.test(el.val())) return;
          el.val(el.val().replace(/"|'/g, ""));
        });
    </script>
  </body>
</html>
