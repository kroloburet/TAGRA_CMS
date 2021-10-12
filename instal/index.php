<!DOCTYPE html>
<html>
<head>
    <meta name="generator"
          content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/Tagra_UI/style.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Установка системы управления контентом</title>
</head>
<body>
<div id="content">
    <h1>Установка системы управления контентом</h1>
    <?php ini_get('date.timezone') ? date_default_timezone_set(ini_get('date.timezone')) : null ?>
    <p><b>Временная зона:</b> <?= date_default_timezone_get() ?><br>
        <b>Дата и время:</b> <?= date('Y-m-d H:i:s') ?><br>
        Чтобы система правильно работала с датой и временем, на вашем сервере должна быть выбрана ваша временная зона.
        Если текущая временная зона вам не подходит, установите желаемую в файле <q>php.ini</q>.
        <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
        <span class="TUI_Hint">
        В файле конфигурации php.ini найдите
        строку <q>date.timezone=</q> и укажите
        свою временную зону после <q>=</q>.
        Убедитесь, что в этой строке нет знака
        <q>;</q> вначале. Или обратитесь
        к поставщику хостинг услуг.
    </span>
    </p>
    <p>Заполните все поля (одиночные и двойные кавычки не допускаются) и нажмите на кнопку <q>Установить систему</q>
        Если ви все правильно сделали и не возникло каких-либо программных сбоев, вы перейдете на страницу, где сможете
        увидеть статус установки. Если на этом или другом этапе у вас возникли проблемы и вы не в силах решить их
        самостоятельно &mdash; обратитесь к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a>
    </p>

    <form class="instal_form container" action="process.php" method="post">

        <!--
        ########### Основное
        -->

        <div class="touch">
            <h2>База данных</h2>
            <label class="TUI_input">
                <span>Имя базы данных</span>
                <input type="text" name="db_name" required>&nbsp;
                <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <span class="TUI_Hint">
                    База с этим именем уже должна существовать
                </span>
            </label>
            <label class="TUI_input">
                <span>Хост базы данных</span>
                <input type="text" name="db_host" value="localhost" required>
            </label>
            <label class="TUI_input">
                <span>Пользователь базы данных</span>
                <input type="text" name="db_user" required>
            </label>
            <label class="TUI_input">
                <span>Пароль пользователя базы данных</span>
                <input type="text" name="db_pass" required>
            </label>
            <label class="TUI_input">
                <span>Префикс таблиц базы данных</span>
                <input type="text" name="db_tabl_prefix" required>&nbsp;
                <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <span class="TUI_Hint">
                    В процессе установки система создаст необходимые
                    для ее работы таблицы в указанной базе данных.
                    Чтобы имена создаваемых таблиц были уникальны и
                    не возникло конфликтов с уже существующими таблицами
                    &mdash; введите короткий префикс на латинице,
                    не используя специальные символы.
                    Пример: new
                </span>
            </label>
        </div>

        <!--
        ########### Начальные настройки
        -->

        <div class="touch">
            <h2>Начальные настройки</h2>
            <label class="TUI_input">
                <span>Имя сайта</span>
                <input type="text" name="site_name" placeholder="Пример: Мой официальный сайт"
                       value="<?= $_SERVER['SERVER_NAME'] ?>" required>
            </label>
            <label class="TUI_input">
                <span>Домен</span>
                <input type="url" name="domain" value="<?= 'http'
                . (empty($_SERVER['HTTPS']) ? '' : 's') . '://'
                . $_SERVER['SERVER_NAME'] . '/' ?>" required>&nbsp;
                <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <span class="TUI_Hint">
                    URL к директории в которой будет запущена система
                    (куда вы выгрузили все файлы)
                    Внимание! Обязательно с <q>http<?= empty($_SERVER['HTTPS']) ? '' : 's' ?>://</q> и <q>/</q> в конце.
                </span>
            </label>
            <label class="TUI_input">
                <span>Логин администратора</span>
                <input type="text" name="admin_name" value="admin" required>
            </label>
            <label class="TUI_input">
                <span>Пароль администратора</span>
                <input type="text" name="admin_pass" id="admin_pass" value="admin" required>&nbsp;
                <a href="#" onclick="gen_pass('admin_pass');return false" class="fas fa-sync-alt"
                   title="Генерировать пароль"></a>
            </label>
            <label class="TUI_input">
                <span>E-mail администратора</span>
                <input type="email" name="admin_mail" required>
            </label>
        </div>
        <div class="TUI_fieldset">
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

<script src="/Tagra_UI/script.js"></script>
<script src="https://kit.fontawesome.com/bacee63d78.js"></script>
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
        for (let i = 1; i < 11; i++) {
            let c = Math.floor(Math.random() * chars.length + 1);
            passwd += chars.charAt(c);
        }
        document.getElementById(id).value = passwd;
    }

    /**
     * Удалять одиночные и двойные кавычки в полях
     */
    document.querySelectorAll('.instal_form input').forEach(input => {
        input.onchange = () => {
            if (!/"|'/.test(input.value)) return;
            input.value = input.value.replace(/"|'/g, "");
        }
    });
</script>
</body>
</html>
