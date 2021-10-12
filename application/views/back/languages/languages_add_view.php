<h1><?= $data['view_title'] ?></h1>

<div class="sheath">

    <!--
    ########### Справка по добавлению языка
    -->

    <div class="TUI_notice-b">
        Добавление языка состоит из двух этапов.&nbsp;
        <a href="#" onclick="TUI.Toggle('add_info');return false">
            Подробнее <i class="fas fa-angle-down"></i>
        </a>
        <div id="add_info" hidden>
            <h3>Этап первый</h3>
            <hr>
            <ul>
                <li>Выберите на этой странице настройки языка, нажмите <q>Сохранить и продолжить</q>.</li>
                <li>Система проверит корректность ваших настроек.</li>
                <li>Система создаст пустой каталог в файловом менеджере с именем тега данного языка. В этом каталоге
                    следует размещать ваши файлы и каталоги, относящиеся непосредственно к материалам данного языка.
                    Если вы решите удалить язык, система удалит языковой каталог со всем содержимым. Поэтому, если ваши
                    файлы будут общими для материалов разных языковых версий, вместо каталогов языков следует
                    использовать любое другое место.
                </li>
                <li>Система дублирует файл локализации интерфейса текущего языка по умолчанию. Вам нужно будет
                    только отредактировать его (перевести на добавляемый сейчас язык).
                </li>
                <li>Система создаст страницы <q>Главная</q> и <q>Контакты</q> для выбранной языковой версии. Эти
                    страницы можно будет только редактировать. Если вы решите удалить язык, эти страницы будут
                    удалены вместе с языком.
                </li>
            </ul>
            <h3>Этап второй</h3>
            <hr>
            <ul>
                <li>После удачного завершения первого этапа система перенаправит на страницу редактирования данного
                    языка.
                </li>
                <li>Отредактируйте файлы локализации интерфейса согласно данному языку, сохраните изменения.</li>
                <li>Отредактируйте страницы <q>Главная</q> и <q>Контакты</q> для данной языковой версии.
                </li>
                <li>Теперь вы можете приступить к созданию материалов для данной языковой версии.</li>
            </ul>
            <h3>Обратите внимание</h3>
            <hr>
            <ul>
                <li>Тег языка задается на первом этапе, и в дальнейшем, в отличие от других настроек, не может быть
                    изменен.
                </li>
                <li>Удаление языка влечет удаление: всех материалов, меню и комментариев связанных с ним, каталога
                    языка из файлового менеджера со всем содержимым, файла локализации удаляемого языка.
                </li>
                <li>Если в системе только один язык, он будет назначен языком по умолчанию.</li>
                <li>Язык назначенный по умолчанию, не может быть удален. Чтобы удалить этот язык, нужно сперва
                    назначить языком по умолчанию один из альтернативных.
                </li>
                <li>В системе не может быть более одного языка по умолчанию.</li>
            </ul>
            <a href="#" onclick="TUI.Toggle('add_info');return false">Скрыть справку</a>
        </div>
    </div>

    <form method="POST" action="/admin/language/add">
        <input type="hidden" name="id" value="<?= round(microtime(true) * 1000) ?>">
        <input type="hidden" name="creation_date" value="<?= date('Y-m-d H:i:s') ?>">
        <input type="hidden" name="last_mod_date" value="<?= date('Y-m-d H:i:s') ?>">

        <!--
        ########### Основное
        -->

        <div class="touch">
            Название языка <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Название будет отображено для переключения
                языков в пользовательской части ресурса.
                Используйте короткое и уникальное название
                чтобы не запутать пользователей и не
                запутаться самому.
                <b class="TUI_red">Обязательно для заполнения!</b>
            </pre>
            <label class="TUI_input">
                <input type="text" name="title"
                       placeholder="Пример: UA" oninput="TUI.Lim(this, 20)"
                       onchange="check_title(this, null, 'languages', 'Язык с таким названием уже существует!\nИзмените название и продолжайте.')">
            </label>

            Тег языка <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this, 'click')"></i>
            <pre class="TUI_Hint">
                Двухбуквенный код страны заданный
                стандартом <a href="https://ru.wikipedia.org/wiki/ISO_639-1" target="_blank">ISO 639-1</a>. Выберите из списка
                язык для которого будет установлен тег.
                <b class="TUI_red">Обязательно для заполнения!</b>
            </pre>
            <label class="TUI_select">
                <select name="tag" class="TUI_SelectSearch" size="3">
                    <option value="az">Азербайджанский [az]</option>
                    <option value="ay">Аймарский [ay]</option>
                    <option value="sq">Албанский [sq]</option>
                    <option value="en">Английский [en]</option>
                    <option value="ar">Арабский [ar]</option>
                    <option value="hy">Армянский [hy]</option>
                    <option value="as">Ассамский [as]</option>
                    <option value="af">Африканс [af]</option>
                    <option value="ba">Башкирский [ba]</option>
                    <option value="be">Белорусский [be]</option>
                    <option value="bn">Бенгальский [bn]</option>
                    <option value="bg">Болгарский [bg]</option>
                    <option value="br">Бретонский [br]</option>
                    <option value="cy">Валлийский [cy]</option>
                    <option value="hu">Венгерский [hu]</option>
                    <option value="vi">Вьетнамский [vi]</option>
                    <option value="gl">Галисийский [gl]</option>
                    <option value="nl">Голландский [nl]</option>
                    <option value="el">Греческий [el]</option>
                    <option value="ka">Грузинский [ka]</option>
                    <option value="gn">Гуарани [gn]</option>
                    <option value="da">Датский [da]</option>
                    <option value="zu">Зулу [zu]</option>
                    <option value="iw">Иврит [iw]</option>
                    <option value="ji">Идиш [ji]</option>
                    <option value="in">Индонезийский [in]</option>
                    <option value="ga">Ирландский [ga]</option>
                    <option value="is">Исландский [is]</option>
                    <option value="es">Испанский [es]</option>
                    <option value="it">Итальянский [it]</option>
                    <option value="kk">Казахский [kk]</option>
                    <option value="km">Камбоджийский [km]</option>
                    <option value="ca">Каталанский [ca]</option>
                    <option value="ks">Кашмирский [ks]</option>
                    <option value="qu">Кечуа [qu]</option>
                    <option value="ky">Киргизский [ky]</option>
                    <option value="zh">Китайский [zh]</option>
                    <option value="ko">Корейский [ko]</option>
                    <option value="co">Корсиканский [co]</option>
                    <option value="ku">Курдский [ku]</option>
                    <option value="lo">Лаосский [lo]</option>
                    <option value="lv">Латвийский, латышский [lv]</option>
                    <option value="la">Латынь [la]</option>
                    <option value="lt">Литовский [lt]</option>
                    <option value="mg">Малагасийский [mg]</option>
                    <option value="ms">Малайский [ms]</option>
                    <option value="mt">Мальтийский [mt]</option>
                    <option value="mi">Маори [mi]</option>
                    <option value="mk">Македонский [mk]</option>
                    <option value="mo">Молдавский [mo]</option>
                    <option value="mn">Монгольский [mn]</option>
                    <option value="na">Науру [na]</option>
                    <option value="de">Немецкий [de]</option>
                    <option value="ne">Непальский [ne]</option>
                    <option value="no">Норвежский [no]</option>
                    <option value="pa">Пенджаби [pa]</option>
                    <option value="fa">Персидский [fa]</option>
                    <option value="pl">Польский [pl]</option>
                    <option value="pt">Португальский [pt]</option>
                    <option value="ps">Пуштунский [ps]</option>
                    <option value="rm">Ретороманский [rm]</option>
                    <option value="ro">Румынский [ro]</option>
                    <option value="ru">Русский [ru]</option>
                    <option value="sm">Самоанский [sm]</option>
                    <option value="sa">Санскрит [sa]</option>
                    <option value="sr">Сербский [sr]</option>
                    <option value="sk">Словацкий [sk]</option>
                    <option value="sl">Словенский [sl]</option>
                    <option value="so">Сомали [so]</option>
                    <option value="sw">Суахили [sw]</option>
                    <option value="su">Суахили [su]</option>
                    <option value="tl">Тагальский [tl]</option>
                    <option value="tg">Таджикский [tg]</option>
                    <option value="th">Тайский [th]</option>
                    <option value="ta">Тамильский [ta]</option>
                    <option value="tt">Татарский [tt]</option>
                    <option value="bo">Тибетский [bo]</option>
                    <option value="to">Тонга [to]</option>
                    <option value="tr">Турецкий [tr]</option>
                    <option value="tk">Туркменский [tk]</option>
                    <option value="uz">Узбекский [uz]</option>
                    <option value="uk">Украинский [uk]</option>
                    <option value="ur">Урду [ur]</option>
                    <option value="fj">Фиджи [fj]</option>
                    <option value="fi">Финский [fi]</option>
                    <option value="fr">Французский [fr]</option>
                    <option value="fy">Фризский [fy]</option>
                    <option value="hr">Хорватский [hr]</option>
                    <option value="cs">Чешский [cs]</option>
                    <option value="sv">Шведский [sv]</option>
                    <option value="et">Эстонский [et]</option>
                </select>
            </label>

            <label class="TUI_checkbox inline" style="margin:0">
                <input type="checkbox" name="def" value="1">
                <span class="custom-checkbox"></span>
                Язык по умолчанию
            </label> <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Будет предложен по умолчанию посетителю
                пользовательской части ресурса если его
                язык не определен или не добавлен.
            </pre>
        </div>

        <div class="TUI_fieldset this_form_control">
            <button type="button" onclick="subm(form, req)">Сохранить и продолжить</button>
            <a href="/admin/language/get_list" class="TUI_btn-link">Отменить</a>
        </div>
    </form>
</div>

<script>
    // рег.выражения для проверки полей
    const req = {
        title: /[^\s]/,
        tag: /[^\s]/
    };

    $(function () {
        // блокировать в списке добавленные теги
        let k,
            list = $('select[name="tag"]'),
            added_tags = <?= $data['added_tags'] ?>;
        for (k in added_tags) {
            list.find('option[value="' + added_tags[k] + '"]').attr('disabled', true);
        }
    });
</script>

