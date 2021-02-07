<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('administrator_control')) {

    /**
     * Управление данными администратора
     *
     * @return void
     */
    function administrator_control()
    {
        $CI = &get_instance();
        $q = $CI->db->where('status', 'administrator')->get('back_users')->result_array();
        if (empty($q)) {
            $a = '{}';
        } else {
            foreach ($q as $v) {
                $a[$v['id']] = $v;
            }
            $a = json_encode($a, JSON_FORCE_OBJECT);
        }
        unset($q);
        ?>

        <!--
        ########### Настройки администратора
        -->

        <div class="touch" id="administrator_control">
            <h2>Настройки администратора</h2>
            <hr>
            <!-- форма редактирования -->
            <div class="buc_form" hidden>
                <div class="buc_info"></div>
                Логин <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <pre class="TUI_Hint">
                    Строка длиной 5-20 символов
                    которыми могут быть строчные,
                    прописные латинские буквы,
                    цифры, специальные символы.
                    Пример: Va$ya_Pupkin
                </pre>
                <label class="TUI_input">
                    <input type="text" class="buc_login" placeholder="Не заполняйте если не хотите менять логин">
                </label>
                Пароль <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <pre class="TUI_Hint">
                    Строка длиной 8-20 символов
                    которыми могут быть строчные,
                    прописные латинские буквы,
                    цифры, специальные символы.
                    Пример: o4-slOjniY
                    <b class="TUI_red">Используйте сложный пароль,
                    или генерируйте его!</b>
                </pre>
                <a href="#" onclick="gen_pass('#ac_pass');return false" class="fas fa-sync-alt"
                   title="Генерировать пароль"></a>
                <label class="TUI_input">
                    <input type="text" class="buc_pass" id="ac_pass"
                           placeholder="Не заполняйте если не хотите менять пароль">
                </label>
                E-mail <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                <pre class="TUI_Hint">
                    На этот е-mail будут высланы логин
                    и парол, если вы их забудите.
                    Указывайте надежный почтовый ящик.
                    <b class="TUI_red">Обязательно для заполнения!</b>
                </pre>
                <label class="TUI_input">
                    <input type="text" class="buc_mail">
                </label>
                <div class="TUI_fieldset TUI_align-r">
                    <button type="button" class="buc_done_btn">Готово</button>
                    <button type="button" class="buc_cancel_btn">Отмена</button>
                </div>
            </div>
            <!-- блок превью -->
            <div class="buc_prev"></div>
        </div>

        <script>
            /**
             * Модуль управления данными администратора
             *
             * @returns {void}
             */
            ;(function ($) {
                let _ = $('#administrator_control'), // блок настроек
                    _form = _.find('.buc_form'), // форма редактирования
                    _info = _.find('.buc_info'), // блок информации
                    _login = _.find('.buc_login'), // поле "логин"
                    _pass = _.find('.buc_pass'), // поле "пароль"
                    _mail = _.find('.buc_mail'), // поле "email"
                    _done_btn = _.find('.buc_done_btn'), // кнопка "готово"
                    _cancel_btn = _.find('.buc_cancel_btn'), // кнопка "отмена"
                    _prev = _.find('.buc_prev'), // блок превью
                    _opt = <?= $a ?>;// объект данных администратора

                /**
                 * Валидация полей формы
                 *
                 * @returns {Boolean}
                 */
                const __validator = function () {

                    //валидация поля "E-mail"
                    _mail.val($.trim(_mail.val()));
                    if (!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(_mail.val())) {
                        alert(`В поле "E-mail" недопустимый символ либо оно не заполнено!`);
                        return false;
                    }

                    //проверка пройдена успешно
                    return true;
                };

                /**
                 * Открыть форму редактирования
                 *
                 * @param {string} id Идентификатор администратора
                 * @returns {void}
                 */
                const __get_edit_form = function (id) {
                    let last_mod = _opt[id].last_mod_date === _opt[id].creation_date ? 'не изменялся' : _opt[id].last_mod_date,
                        last_login = _opt[id].last_login_date === _opt[id].creation_date ? 'не входил' : _opt[id].last_login_date + ' с IP ' + _opt[id].ip;

                    __clear();
                    // событие на "готово" - редактировать
                    _done_btn.on('click.BUC', __edit);
                    // информация об администраторе
                    _info.html(`<p class="TUI_notice-b mini TUI_full">
                        Дата создания: ${_opt[id].creation_date}<br>
                        Дата изменения: ${last_mod}<br>
                        Дата последнего входа: ${last_login}
                    </p>`);
                    _mail.val(_opt[id].email);
                    _form.slideDown(200);
                };

                /**
                 * Скрыть, очистить форму
                 *
                 * @returns {void}
                 */
                const __clear = function () {
                    _done_btn.off();// удалить все события у кнопки "готово"
                    _info.empty();
                    _form.slideUp(200).find('input').val('');// очистить поля, скрыть форму
                };

                /**
                 * Отправить запрос на редактирование
                 *
                 * @returns {void}
                 */
                const __edit = function () {

                    // валидация полей
                    if (!__validator()) return;

                    let done_btn_text = _done_btn.text();

                    _cancel_btn.hide();
                    _done_btn.attr('disabled', true).html('<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...');
                    $.ajax({
                        url: '/admin/setting/edit_administrator',
                        type: 'post',
                        data: {login: _login.val(), password: _pass.val(), email: _mail.val()},
                        dataType: 'json',
                        success: function (resp) {
                            switch (resp.status) {
                                case'error':
                                    alert('Ой! Ошибка..( Данные не сохранены.\nПроверьте, правильно ли заполнены поля и повторите попытку.');
                                    break;
                                case'nomail':
                                    alert('E-mail некорректный!\nИзмените и повторите попытку.');
                                    break;
                                case'nounq':
                                    alert('В системе уже есть пользователь с такими данными!');
                                    break;
                                case'ok':
                                    if (!$.isEmptyObject(resp.opt)) {
                                        _opt = resp.opt;
                                        __clear();
                                        __show();
                                        TUI.GoTo('#administrator_control');
                                    }
                                    break;
                                default:
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                    alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                            }
                        },
                        error: function (xhr, status, thrown) {
                            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
                        }
                    });
                    _cancel_btn.show();
                    _done_btn.attr('disabled', false).html(done_btn_text);
                };

                /**
                 * Отобразить превью
                 *
                 * @returns {void}
                 */
                const __show = function () {

                    // если нет данных
                    if ($.isEmptyObject(_opt)) return;

                    _prev.empty();//очистить превью
                    for (let k in _opt) {//заполнять превью
                        let edit_btn = $('<div/>', {
                                class: 'prev_item_control fas fa-edit',
                                title: 'Редактировать'
                            }).data('id', k),
                            prev_str_box = $('<div/>', {class: 'prev_item_content', html: _opt[k].email}),
                            prev_item = $('<div/>', {class: 'prev_item', html: [prev_str_box, edit_btn]});
                        edit_btn.on('click.BUC', function () {
                            __get_edit_form($(this).data('id'));
                            TUI.GoTo('#administrator_control');
                        });
                        _prev.prepend(prev_item);
                    }
                };

                // отмена редактирования
                _cancel_btn.on('click.BUC', function () {
                    __clear();
                    TUI.GoTo('#administrator_control');
                });

                // отобразить превью
                __show();

            }(jQuery));
        </script>

        <?php
    }
}

// ------------------------------------------------------------------------

if (!function_exists('moderators_control')) {

    /**
     * Управление данными модераторов
     *
     * @return void
     */
    function moderators_control()
    {
        $CI = &get_instance();
        $q = $CI->db->where('status', 'moderator')->get('back_users')->result_array();
        if (empty($q)) {
            $m = '{}';
        } else {
            foreach ($q as $v) {
                $m[$v['id']] = $v;
            }
            $m = json_encode($m, JSON_FORCE_OBJECT);
        }
        unset($q);
        ?>

        <!--
        ########### Настройки модераторов
        -->

        <div class="touch" id="moderators_control">
            <h2>Настройки модераторов</h2>
            <hr>
            <button type="button" class="add_buc_btn">Добавить модератора</button>
            <!-- форма добавления/редактирования -->
            <div class="buc_form" hidden>
                <div class="buc_info"></div>
                <div class="TUI_row">
                    <div class="TUI_col-6">
                        Логин <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Строка длиной 5-20 символов
                            которыми могут быть строчные,
                            прописные латинские буквы,
                            цифры, специальные символы.
                            Пример: Va$ya_Pupkin
                        </pre>
                        <label class="TUI_input">
                            <input type="text" class="buc_login">
                        </label>
                        Пароль <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Строка длиной 8-20 символов
                            которыми могут быть строчные,
                            прописные латинские буквы,
                            цифры, специальные символы.
                            Пример: o4-slOjniY
                            <b class="TUI_red">Используйте сложный пароль,
                            или генерируйте его!</b>
                        </pre>
                        <a href="#" onclick="gen_pass('#mc_pass');return false" class="fas fa-sync-alt"
                           title="Генерировать пароль"></a>
                        <label class="TUI_input">
                            <input type="text" class="buc_pass" id="mc_pass">
                        </label>
                    </div>
                    <div class="TUI_col-6">
                        E-mail <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Указывайте надежный почтовый ящик.
                            <b class="TUI_red">Обязательно для заполнения!</b>
                        </pre>
                        <label class="TUI_input">
                            <input type="text" class="buc_mail">
                        </label>
                        Доступ <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Разрешить или запретить вход в
                            административную часть системы и
                            все действия от имени модератора
                            в пользовательской части системы.
                        </pre>
                        <label class="TUI_select">
                            <select class="buc_access">
                                <option value="1">Разрешен</option>
                                <option value="0">Запрещен</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="TUI_fieldset TUI_align-r">
                    <button type="button" class="buc_done_btn">Готово</button>
                    <button type="button" class="buc_cancel_btn">Отмена</button>
                </div>
            </div>
            <!-- блок превью -->
            <div class="buc_prev"></div>
        </div>

        <script>
            /**
             * Модуль управления данными модератора
             *
             * @returns {void}
             */
            ;(function ($) {
                let _ = $('#moderators_control'), // блок настроек
                    _add_btn = _.find('.add_buc_btn'), // кнопка "добавить модератора"
                    _form = _.find('.buc_form'), // форма "добавить\редактировать"
                    _info = _.find('.buc_info'), // блок информации
                    _login = _.find('.buc_login'), // поле "логин"
                    _pass = _.find('.buc_pass'), // поле "пароль"
                    _mail = _.find('.buc_mail'), // поле "email"
                    _access = _.find('.buc_access'), // поле "доступ"
                    _done_btn = _.find('.buc_done_btn'), // кнопка "готово"
                    _cancel_btn = _.find('.buc_cancel_btn'), // кнопка "отмена"
                    _prev = _.find('.buc_prev'), // блок превью
                    _opt = <?= $m ?>;// объект данных модераторов

                /**
                 * Валидация полей формы
                 *
                 * @param {string} id Идентификатор модератора
                 * @returns {Boolean}
                 */
                const __validator = function (id) {

                    // валидация логина и пароля если модератор добавляется
                    if (typeof id === "undefined") {
                        if (!/\S/.test(_login.val())) {
                            alert('Поле "Логин" не заполнено!');
                            return false;
                        }
                        if (!/\S/.test(_pass.val())) {
                            alert('Поле "Пароль" не заполнено!');
                            return false;
                        }
                    }

                    // валидация поля "E-mail"
                    _mail.val($.trim(_mail.val()));
                    if (!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(_mail.val())) {
                        alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');
                        return false;
                    }

                    //проверка пройдена успешно
                    return true;
                };

                /**
                 * Открыть форму добавления
                 *
                 * @returns {void}
                 */
                const __get_add_form = function () {
                    __clear();
                    // событие на "готово" - добавить
                    _done_btn.on('click.BUC', __add);
                    _form.slideDown(200);
                };

                /**
                 * Открыть форму редактирования
                 *
                 * @param {string} id Идентификатор модератора
                 * @returns {void}
                 */
                const __get_edit_form = function (id) {
                    let last_mod = _opt[id].last_mod_date === _opt[id].creation_date ? 'не изменялся' : _opt[id].last_mod_date,
                        last_login = _opt[id].last_login_date === _opt[id].creation_date ? 'не входил' : _opt[id].last_login_date + ' с IP ' + _opt[id].ip;

                    __clear();
                    // событие на "готово" - редактировать
                    _done_btn.on('click.BUC', function () {
                        __edit(id);
                    });
                    // информация о модераторе
                    _info.html(`<p class="TUI_notice-b mini TUI_full">
                        Дата создания: ${_opt[id].creation_date}<br>
                        Дата изменения: ${last_mod}<br>
                        Дата последнего входа: ${last_login}
                    </p>`);
                    _login.attr('placeholder', 'Не заполняйте если не хотите менять логин');
                    _pass.attr('placeholder', 'Не заполняйте если не хотите менять пароль');
                    _mail.val(_opt[id].email);
                    _access.find('option[value="' + _opt[id].access + '"]').attr('selected', true);
                    _form.slideDown(200);
                };

                /**
                 * Скрыть, очистить форму
                 *
                 * @returns {void}
                 */
                const __clear = function () {
                    _done_btn.off();// удалить все события у кнопки "готово"
                    _info.empty();
                    _access.find('option').removeAttr('selected');
                    _form.slideUp(200).find('input').removeAttr('placeholder').val('');// очистить поля, скрыть форму
                };

                /**
                 * Отправить запрос на добавление
                 *
                 * @returns {void}
                 */
                const __add = function () {

                    // валидация полей
                    if (!__validator()) return;

                    let done_btn_text = _done_btn.text();

                    _cancel_btn.hide();
                    _done_btn.attr('disabled', true).html('<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...');
                    $.ajax({
                        url: '/admin/setting/add_moderator',
                        type: 'post',
                        data: {login: _login.val(), password: _pass.val(), email: _mail.val(), access: _access.val()},
                        dataType: 'json',
                        success: function (resp) {
                            switch (resp.status) {
                                case'error':
                                    alert('Ой! Ошибка..( Данные не сохранены.\nПроверьте, правильно ли заполнены поля и повторите попытку.');
                                    break;
                                case'nomail':
                                    alert('E-mail некорректный!\nИзмените и повторите попытку.');
                                    break;
                                case'nounq':
                                    alert('В системе уже есть пользователь с такими данными!');
                                    break;
                                case'ok':
                                    if (!$.isEmptyObject(resp.opt)) {
                                        _opt = resp.opt;
                                        __clear();
                                        __show();
                                        TUI.GoTo('#moderators_control');
                                    }
                                    break;
                                default:
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                    alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                            }
                        },
                        error: function (xhr, status, thrown) {
                            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
                        }
                    });
                    _cancel_btn.show();
                    _done_btn.attr('disabled', false).html(done_btn_text);
                };

                /**
                 * Отправить запрос на редактирование
                 *
                 * @param {string} id Идентификатор модератора
                 * @returns {void}
                 */
                const __edit = function (id) {

                    // валидация полей
                    if (!__validator(id)) return;

                    let done_btn_text = _done_btn.text();

                    _cancel_btn.hide();
                    _done_btn.attr('disabled', true).html('<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...');
                    $.ajax({
                        url: '/admin/setting/edit_moderator/' + id,
                        type: 'post',
                        data: {login: _login.val(), password: _pass.val(), email: _mail.val(), access: _access.val()},
                        dataType: 'json',
                        success: function (resp) {
                            switch (resp.status) {
                                case'error':
                                    alert('Ой! Ошибка..( Данные не сохранены.\nПроверьте, правильно ли заполнены поля и повторите попытку.');
                                    break;
                                case'nomail':
                                    alert('E-mail некорректный!\nИзмените и повторите попытку.');
                                    break;
                                case'nounq':
                                    alert('В системе уже есть пользователь с такими данными!');
                                    break;
                                case'ok':
                                    if (!$.isEmptyObject(resp.opt)) {
                                        _opt = resp.opt;
                                        __clear();
                                        __show();
                                        TUI.GoTo('#moderators_control');
                                    }
                                    break;
                                default:
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                    alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                            }
                        },
                        error: function (xhr, status, thrown) {
                            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
                        }
                    });
                    _cancel_btn.show();
                    _done_btn.attr('disabled', false).html(done_btn_text);
                };

                /**
                 * Отправить запрос на удаление
                 *
                 * @param {string} id Идентификатор модератора
                 * @returns {void}
                 */
                const __del = function (id) {
                    $.ajax({
                        url: '/admin/setting/del_moderator',
                        type: 'post',
                        data: {id: id},
                        dataType: 'text',
                        success: function (resp) {
                            switch (resp) {
                                case'error':
                                    alert('Ой! Ошибка..( Данные не сохранены.\nПроверьте, правильно ли заполнены поля и повторите попытку.');
                                    break;
                                case'last':
                                    alert('Вы пытаетесь удалить единственного модератора!\nВ системе должен быть один или более модераторов.');
                                    break;
                                case'ok':
                                    delete _opt[id];
                                    __show();
                                    break;
                                default:
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                    alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                            }
                        },
                        error: function (xhr, status, thrown) {
                            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
                        }
                    });
                };

                /**
                 * Отобразить превью
                 *
                 * @returns {void}
                 */
                const __show = function () {

                    // если нет данных
                    if ($.isEmptyObject(_opt)) return;

                    _prev.empty();// очистить превью
                    for (let k in _opt) {// заполнять превью
                        let edit_btn = $('<div/>', {
                                class: 'prev_item_control fas fa-edit',
                                title: 'Редактировать'
                            }).data('id', k),
                            del_btn = $('<div/>', {
                                class: 'prev_item_control fas fa-trash-alt',
                                title: 'Удалить'
                            }).data('id', k),
                            title = $('<div/>', {
                                class: 'prev_item_content ' + (_opt[k].access === '0' ? 'TUI_red' : ''),
                                html: _opt[k].email
                            }),
                            prev_item = $('<div/>', {class: 'prev_item', html: [title, edit_btn, del_btn]});
                        edit_btn.on('click.BUC', function () {
                            __get_edit_form($(this).data('id'));
                            TUI.GoTo('#moderators_control');
                        });
                        del_btn.on('click.BUC', function () {
                            if (confirm('Этот модератор будет удален!\nВыполнить действие?'))
                                __del($(this).data('id'));
                        });
                        _prev.prepend(prev_item);
                    }
                };

                // открыть форму добавления
                _add_btn.on('click.BUC', __get_add_form);

                // отмена редактирования/добавления
                _cancel_btn.on('click.BUC', function () {
                    __clear();
                    TUI.GoTo('#moderators_control');
                });

                // отобразить превью
                __show();

            }(jQuery));
        </script>

        <?php
    }
} ?>

<style>
    .buc_form .TUI_row {
        margin: 0;
    }

    .buc_form .TUI_notice-b {
        margin-bottom: .5em;
    }
</style>
