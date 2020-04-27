<?php
/**
 * Дерево выпадающего списка
 *
 * Выводит дерево меню для выпадающего списка
 * "Родительский пункт".
 *
 * @param array $input Меню
 * @param int $level Уровень вложенности
 * @return void
 */
function print_pids_tree(array $input, int $level = 0)
{
    if (empty($input)) {
        return;
    }

    foreach ($input as $v) {
        echo '<option value="' . $v['id'] . '">' . str_repeat('&#183; ', $level) . $v['title'] . '</option>' . PHP_EOL;
        if (isset($v['nodes'])) {
            print_pids_tree($v['nodes'], $level + 1);
        }
    }
}

/**
 * Дерево меню
 *
 * Вывести дерево меню для управления пунктами
 *
 * @param array $input Меню
 * @return void
 */
function print_menu_tree(array $input)
{
    if (empty($input)) {
        return;
    }

    foreach ($input as $v) {
        $ext_link = $v['url']
            ? '<a href="' . $v['url']
            . '" target="_blank" class="fas fa-external-link-alt" '
            . 'title="Посмотреть на сайте"></a>&nbsp;&nbsp;'
            : '<i class="fas fa-external-link-alt TUI_gray"></i>&nbsp;&nbsp;';

        echo '<li>' . PHP_EOL;
        echo '
        <div class="m_item">
           ' . $v['title'] . '&nbsp;&nbsp;
           ' . $ext_link . '
           <a href="#" onclick="Menu.public_del(this, \'public\');return false"
              class="' . ($v['public'] ? 'fas fa-eye TUI_blue' : 'fas fa-eye-slash TUI_red') . '"
              title="Опубликовать/не опубликовывать"></a>&nbsp;&nbsp;
           <a href="#" class="fas fa-edit TUI_blue" title="Редактировать"
              onclick="Menu.show_edit_form(this);return false"></a>&nbsp;&nbsp;
           <a href="#" class="fas fa-trash-alt TUI_red" title="Удалить"
              onclick="Menu.public_del(this, \'del\');return false"></a>
           <textarea class="m_item_opt" hidden>' . json_encode($v, JSON_FORCE_OBJECT) . '</textarea>
        </div>
        ';

        if (isset($v['nodes'])) {
            echo '<ul>' . PHP_EOL;
            print_menu_tree($v['nodes']);
            echo '</ul>' . PHP_EOL;
        }
        echo '</li>' . PHP_EOL;
    }
}

?>

<h1><?= "{$data['view_title']} [{$data['lang']}]" ?></h1>

<!--
########### Добавить пункт меню
-->

<div class="sheath" id="m_area">
    <div class="touch">
        <h2>Добавить пункт меню</h2>
        <hr>
        <form class="m_form">
            <div class="TUI_row">
                <div class="TUI_col-6">
                    Родительский пункт
                    <label class="TUI_select">
                        <select class="m_pid" onchange="Menu.load_order(this)">
                            <option value="0">Нет родителя</option>
                            <?php print_pids_tree($data['menu']) ?>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-6">
                    Порядок
                    <label class="TUI_select">
                        <select class="m_order"></select>
                    </label>
                </div>
            </div>
            Название пункта
            <label class="TUI_input">
                <input type="text" class="m_title">
            </label>
            <div class="TUI_row">
                <div class="TUI_col-6">
                    Ссылка
                    <label class="TUI_input">
                        <input type="text" class="m_url" id="m_url"
                               placeholder="Оставьте пустым, если пункт — не ссылка">
                    </label>
                </div>
                <div class="TUI_col-6">
                    Материалы ресурса
                    <label class="TUI_select">
                        <select class="m_link" onchange="Menu.select_link(this)">
                            <option value="">Выбрать из предложенных:</option>
                            <option value="pages">Страница сайта</option>
                            <option value="sections">Раздел сайта</option>
                            <option value="gallerys">Галерея сайта</option>
                            <option value="home">Страница "Главная"</option>
                            <option value="contact">Страница "Контакты"</option>
                            <option value="file">Файл или папка</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="m_link_viewer"></div>
            <div class="TUI_row">
                <div class="TUI_col-6">
                    <label class="TUI_select">
                        <select class="m_target">
                            <option value="_self">Открывать в текущем окне</option>
                            <option value="_blank">Открывать в новом окне</option>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-6">
                    <label class="TUI_select">
                        <select class="m_public">
                            <option value="1">Опубликовать пункт</option>
                            <option value="0">Не опубликовывать пункт</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="TUI_fieldset m_control">
                <button type="button" onclick="Menu.add_edit(this, 'add')">Добавить пункт меню</button>
            </div>
        </form>
    </div>

    <!--
    ########### Дерево меню
    -->

    <ul class="m_tree">
        <?php print_menu_tree($data['menu']) ?>
    </ul>
</div>

<style>
    ul.m_tree, ul.m_tree ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    ul.m_tree ul {
        margin-left: 1em;
        position: relative;
    }

    ul.m_tree ul::before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: -1em;
        bottom: 0;
        left: 0;
        border-left: 1px solid var(--color-gray);
    }

    ul.m_tree li {
        margin: 1em 0;
        padding: 0;
    }

    ul.m_tree ul li {
        list-style: none;
        margin: 1em 0;
        padding: 0 0 0 1em;
        color: var(--color-font);
        position: relative;
    }

    ul.m_tree .m_item {
        display: inline-block;
        border: 1px solid var(--color-gray);
        padding: .2em .4em;
    }

    ul.m_tree .m_item form {
        border-top: 1px solid var(--color-gray);
        margin-top: .2em;
        padding-top: .4em;
    }

    ul.m_tree .m_item.edit {
        display: block;
        background-color: rgba(0, 0, 0, .1);
    }

    ul.m_tree ul li::before {
        content: "";
        display: block;
        width: 1em;
        height: 0;
        border-top: 1px solid var(--color-gray);
        position: absolute;
        top: 1em;
        left: 0;
    }

    ul.m_tree ul li:last-child::before {
        background-color: var(--color-base-bg);
        height: auto;
        top: 1em;
        bottom: 0;
    }
</style>

<script>
    /**
     * Управление меню
     */
    const Menu = {
        _mm:<?= json_encode($data['materials'], JSON_FORCE_OBJECT) ?>, // материалы ресурса
        _m:<?= json_encode($data['menu'], JSON_FORCE_OBJECT) ?>, // меню

        /**
         * Заполнение выпадающего списка "Порядок"
         *
         * @param {object} el Ссылка "this" на списке "Родительский пункт"
         * @param {string|null} id Идентификатор пункта исключаемого из списка "Порядок"
         * @returns {void}
         */
        load_order: function (el, id = null) {
            let f = $(el.form),// форма добавления/редактирования
                pid = $(el),// список "Родительский пункт"
                order = f.find('.m_order'),// список "Порядок"
                n = 1,// счетчик значения порядка пунктов
                filling = function (m) {// рекурсивное заполнение пунктами родителя
                    for (let i in m) {// проход по меню
                        if (pid.val() === m[i].pid) {// это значение списка "Родительский пункт"
                            if (!id || id !== m[i].id) {// это неисключаемый пункт
                                order.append('<option value="' + (n++) + '">После "' + m[i].title + '"</option>');
                            }
                        } else if (m[i].nodes) {// это подуровень
                            filling(m[i].nodes);// рекурсиивно заполнить подуровень
                        }
                    }
                };

            // добавить в список "Первый пункт"
            order.html('<option value="' + (n++) + '">Первый пункт</option>');

            // выйти если меню пустое
            if ($.isEmptyObject(this._m)) return;

            // заполнить список "Порядок" и выделить последний элемент
            filling(this._m);
            order.find('option').last().attr('selected', true);
        },

        /**
         * Работа списка "Материалы ресурса"
         *
         * @param {type} el Ссылка "this" на списке "Материалы ресурса"
         * @returns {void}
         */
        select_link: function (el) {
            let f = $(el.form),// форма добавления/редактирования
                link = $(el),// список "Материалы ресурса"
                viewer = f.find('.m_link_viewer'),// контейнер для выбора материала
                url = f.find('.m_url');// поле "Ссылка"

            // выбранно в списке "Материалы ресурса"
            switch (link.val()) {
                // страница "Главная"
                case 'home':
                    url.val('/');
                    viewer.empty();
                    break;
                // страница "Контакты"
                case 'contact':
                    url.val('/contact');
                    viewer.empty();
                    break;
                // файл
                case 'file':
                    files(url.attr('id'), '<?= $data['lang'] ?>', {no_host: true});
                    viewer.empty();
                    break;
                // страница
                case 'pages':
                    viewer.html(this.load_link_viewer('pages', url));
                    TUI.SelectSearch();
                    break;
                // раздел
                case 'sections':
                    viewer.html(this.load_link_viewer('sections', url));
                    TUI.SelectSearch();
                    break;
                // галерея
                case 'gallerys':
                    viewer.html(this.load_link_viewer('gallerys', url));
                    TUI.SelectSearch();
                    break;
            }
        },

        /**
         * Список выбора материала
         *
         * @param {string} material значение списка "Материалы ресурса"
         * @param {object} url поле "Ссылка"
         * @returns {object} Список материалов
         */
        load_link_viewer: function (material, url) {
            let mm = this._mm[material],// объект материалов
                label = $('<label/>', {class: 'TUI_select'}),// label списка
                section = $('<select/>', {class: 'TUI_SelectSearch', size: '5'}),// список
                preurl = '/';// сегменты url перед id

            // задать структуру списка по умолчанию
            label.html(section);
            if (!mm || $.isEmptyObject(mm)) {
                section.html($('<option/>', {text: 'Нет материалов..('}));
                return label;
            }

            // установить сегмент
            switch (material) {
                case 'pages':
                    preurl = '/page/';
                    break;
                case 'sections':
                    preurl = '/section/';
                    break;
                case 'gallerys':
                    preurl = '/gallery/';
                    break;
            }

            // заполнить список материалами
            for (let k in mm) {
                section.append($('<option/>', {value: preurl + mm[k].id, text: mm[k].title}));
            }

            // выбор материала в списке вставляет url в поле "Ссылка"
            section.on('change.M', function () {
                url.val(this.value);
            });

            return label;
        },

        /**
         * Открыть форму редактирования пункта
         *
         * @param {type} el Ссылка "this" на поле "Редактировать"
         * @returns {void}
         */
        show_edit_form: function (el) {

            // скрыть все открытые формы редактирования
            this.hide_edit_form($('.m_item'));

            let m_item = $(el).parent('.m_item'),// контейнер пункта меню
                clone = $('.m_form').clone(true),// клон формы добавления станет формой редактирования текущего пункта
                m_pid = clone.find('.m_pid'),// список "Родительский пункт"
                opt = $.parseJSON(m_item.find('.m_item_opt').val()),// данные редактируемого пункта с вложенными
                set_pids = function (m) {// удаление из списка "Родительский пункт" редактируемого пункта с вложенными
                    m_pid.find('option[value="' + m.id + '"]').remove();
                    if (m.nodes) {
                        for (let i in m.nodes) {
                            set_pids(m.nodes[i]);
                        }
                    }
                },
                btns = [// кнопки формы редактирования пункта
                    $('<button/>', {type: 'button', text: 'Сохранить изменения'})
                        .on('click.M', function () {
                            Menu.add_edit(this, 'edit');
                        }),
                    $('<button/>', {type: 'button', text: 'Отмена'})
                        .on('click.M', function () {
                            Menu.hide_edit_form(m_item);
                        })
                ];

            // заполнить поля формы, открыть форму
            set_pids(opt);
            m_pid.val(opt.pid);
            this.load_order(m_pid[0], opt.id);
            clone.find('.m_order').val(opt.order);
            clone.find('.m_link_viewer').empty();
            clone.find('.m_title').val(opt.title);
            clone.find('.m_url').attr('id', opt.id).val(opt.url);
            clone.find('.m_target').val(opt.target);
            clone.find('.m_public').val(opt.public);
            clone.find('.m_control').html(btns);
            clone.prepend($('<input/>', {class: 'm_id', type: 'hidden', value: opt.id}));
            m_item.addClass('edit').append(clone);
        },

        /**
         * Скрыть форму редактирования пункта
         *
         * @param {object} item Контейнер редактируемого пункта
         * @returns {void}
         */
        hide_edit_form: function (item) {
            item.removeClass('edit');
            item.find('.m_form').remove();
        },

        /**
         * Отображение сообщений
         *
         * @param {string} style CSS класс сообщения
         * @param {string} msg HTML сообщение
         * @param {object} targ Элемент для отображения сообщения
         * @param {callback} call Что делать после отображения сообщения
         * @returns {void}
         */
        msg: function (style, msg, targ, call) {
            style = style || 'TUI_notice-r';// css сласс контейнера сообщения
            msg = msg || 'Поле "Название пункта" должно быть заплнено!';// сообщение
            let box = $('<p/>', {class: 'TUI_full ' + style, html: msg});// контейнер
            targ.html(box);// поместить контейнер в елемент отображения сообщения в форме
            setTimeout(function () {// вывести и удалить сообщение, запустить коллбэк
                box.remove();
                call();
            }, 3000);
            return;
        },

        /**
         * Обновление данных на странице
         *
         * @param {object} data json ответ сервера
         * @returns {void}
         */
        update: function (data) {
            // подменить DOM страницы ответом сервера
            $('#m_area').replaceWith($(data.html).filter('#m_area'));

            this._mm = data.materials;// обновить объект материалов ресурса
            this._m = data.menu;// обновить объект меню

            // заполнить список "Порядок" в форме добавления
            this.load_order($('.m_form').find('.m_pid')[0]);
        },

        /**
         * Добавить/редактировать пункт
         *
         * Универсальный метод отправляет запрос на редактирование
         * или добавление пункта меню.
         *
         * @param {object} el Ссылка "this" на кнопку "Добавить пункт меню" или "Сохранить изменения"
         * @param {string} action Действие над пунктом "add" или "edit"
         * @returns {void}
         */
        add_edit: function (el, action) {

            // проверка аргументов
            if (!el || (action !== 'add' && action !== 'edit')) return;

            let f = $(el.form),// текущая форма
                title = f.find('.m_title').val(),// поле "Название пункта"
                process = '<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...',// лодер
                control = f.find('.m_control'),// контейнер кнопок отправки/отмены
                btn = control.html(),// кнопки
                err = action === 'add'// сообщение ошибки по умолчанию
                    ? 'Ошибка! Не удалось добавить пункт меню..('
                    : 'Ошибка! Не удалось изменить пункт меню..(';

            // валидация поля "Название пункта"
            if (!/[^\s]/.test(title)) {
                return this.msg(null, null, control, function () {
                    control.html(btn);
                });
            }

            // лодер
            control.html(process);

            // отправить запрос
            $.ajax({
                url: '/admin/menu/' + (action === 'add' ? 'add_item' : 'edit_item'),
                type: 'post',
                data: {
                    lang: '<?= $data['lang'] ?>',
                    id: f.find('.m_id').val() || null,
                    pid: f.find('.m_pid').val(),
                    order: f.find('.m_order').val(),
                    title: title,
                    url: f.find('.m_url').val(),
                    target: f.find('.m_target').val(),
                    public: f.find('.m_public').val()
                },
                dataType: 'json',
                success: function (resp) {
                    switch (resp.status) {
                        case 'ok':
                            Menu.update(resp);
                            break;
                        case 'error':
                            Menu.msg(null, err, control, function () {
                                control.html(btn);
                            });
                            break;
                        default :
                            console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                            Menu.msg(
                                null,
                                `Ой! Что-то пошло не так..(<br>Сведения о неполадке выведены в консоль.`,
                                control,
                                function () {
                                    control.html(btn);
                                });
                    }
                },
                error: function (xhr, status, thrown) {
                    console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                    Menu.msg(
                        null,
                        `Ой! Ошибка соединения..(<br>Сведения о неполадке выведены в консоль.<br>Возможно это проблемы на сервере или с сетью Интернет. Повторите попытку.`,
                        control,
                        function () {
                            control.html(btn);
                        });
                }
            });
        },

        /**
         * Переключить публикацию или удалить пункт
         *
         * Универсальный метод отправляет запрос на переключение
         * публикации или удаление пункта меню.
         *
         * @param {object} el Ссылка "this" на триггер "Опубликовать/не опубликовывать" или "Удалить"
         * @param {string} action Действие над пунктом "public" или "del"
         * @returns {void}
         */
        public_del: function (el, action) {

            // проверка аргументов
            if (!el || (action !== 'public' && action !== 'del')) return;

            // подтверждение удаления
            if (
                action === 'del'
                && !confirm('Пункт меню будет удален вместе с вложенными пунктами!\nВыполнить действие?')
            ) return;

            let self = $(el),// триггер
                process = $('<i/>', {class: 'fas fa-spin fa-spinner'});// лодер

            // замена триггера лодером
            self.replaceWith(process);

            // отправить запрос
            $.ajax({
                url: '/admin/menu/' + (action === 'public' ? 'public_item' : 'del_item'),
                type: 'post',
                data: $.parseJSON(process.siblings('.m_item_opt').val()),
                dataType: 'json',
                success: function (resp) {
                    switch (resp.status) {
                        case 'ok':
                            Menu.update(resp);
                            break;
                        case 'error':
                            process.replaceWith(self);
                            alert(action === 'public'
                                ? 'Ошибка! Не удалось переключить публикацию..('
                                : 'Ошибка! Не удалось удалить пункт меню..(');
                            break;
                        default :
                            process.replaceWith(self);
                            console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                            alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                    }
                },
                error: function (xhr, status, thrown) {
                    console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                    alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
                    process.replaceWith(self);
                }
            });
        }

    };

    // заполнить список "Порядок" в форме добавления
    Menu.load_order($('.m_form').find('.m_pid')[0]);
</script>
