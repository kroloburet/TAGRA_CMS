<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('gallery')) {

    /**
     * Выбор типа галереи и управления мультимедиа
     *
     * @return void
     */
    function gallery()
    {
        $CI = &get_instance();
        $data = $CI->app('data');
        $type = isset($data['gallery_type']) ? $data['gallery_type'] : '';
        $opt = isset($data['gallery_opt']) ? $data['gallery_opt'] : '';
        $lang = $data['lang'];
        ?>

        <!--
        ########### Тип галереи и добавление мультимедиа
        -->

        <div class="touch" id="gallery">
            <h2>Тип галереи и добавление мультимедиа</h2>
            <hr>
            Тип галереи <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Выберите, какое мультимедиа вы хотите
                отображать в галерее.
            </pre>
            <label class="TUI_select">
                <select name="gallery_type" id="g_type">
                    <option value="img_folder" <?= $type == 'img_folder' ? 'selected' : '' ?>>Каталог с изображениями
                    </option>
                    <option value="img_desc" <?= $type == 'img_desc' ? 'selected' : '' ?>>Изображения с описанием
                    </option>
                    <option value="video_yt" <?= $type == 'video_yt' ? 'selected' : '' ?>>Видео c YouTube</option>
                    <option value="audio" <?= $type == 'audio' ? 'selected' : '' ?>>Аудио</option>
                </select>
            </label>

            <!-- Каталог с изображениями -->
            <div class="type_section" data-type="img_folder" hidden>
                Путь к каталогу <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                <pre class="TUI_Hint">
                    Нажмите на значок папки и выберите
                    каталог с изображениями в менеджере.
                    Все изображения в выбранном каталоге
                    будут отображены в этой галерее.
                </pre>
                <div class="TUI_fieldset">
                    <label class="TUI_input">
                        <input type="text" id="g_f_folder_url" class="g_field">
                    </label>
                    <a href="#" class="fas fa-folder-open fa-2x TUI_blue"
                       onclick="files('g_f_folder_url', null, {no_host: true});return false"></a>
                </div>
            </div>

            <!-- Изображения с описанием -->
            <div class="type_section" data-type="img_desc" hidden>
                <div class="TUI_row">
                    <div class="TUI_col-3">
                        Порядок <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Порядковый номер изображения в галерее.
                        </pre>
                        <label class="TUI_number">
                            <input type="number" class="TUI_InputNumber order" min="1">
                        </label>
                    </div>
                    <div class="TUI_col-9">
                        Путь к изображению <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Нажмите на значок папки и выберите
                            файл изображения в менеджере файлов.
                            Можно указать абсолютный путь к изображению,
                            который доступен из Интернета.
                        </pre>
                        <div class="TUI_fieldset">
                            <label class="TUI_input">
                                <input type="text" id="g_f_url" class="g_field">
                            </label>
                            <a href="#" class="fas fa-folder-open fa-2x TUI_blue"
                               onclick="files('g_f_url');return false"></a>
                            <i class="fas fa-eye fa-2x TUI_blue" onmouseover="img_prev(this, '#g_f_url')"></i>
                            <pre class="TUI_Hint"></pre>
                        </div>
                    </div>
                </div>
                Заголовок
                <label class="TUI_input">
                    <input type="text" class="g_field" id="g_f_title" oninput="TUI.Lim(this, 75)"
                           placeholder="Заголовок описания изображения">
                </label>
                Описание
                <label class="TUI_textarea">
                    <textarea class="no-emmet g_field" id="g_f_desc" rows="4"
                              oninput="TUI.Lim(this, 1000)" placeholder="Описание изображения"></textarea>
                </label>
                <div class="g_control">
                    <button type="button" class="g_add_btn">Добавить изображение</button>
                </div>
                <!-- Превью для изображений -->
                <div class="g_preview"></div>
            </div>

            <!-- Видео c YouTube -->
            <div class="type_section" data-type="video_yt" hidden>
                <div class="TUI_row">
                    <div class="TUI_col-3">
                        Порядок <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Порядковый номер видео в галерее.
                        </pre>
                        <label class="TUI_number">
                            <input type="number" class="TUI_InputNumber order" min="1">
                        </label>
                    </div>
                    <div class="TUI_col-9">
                        Путь к видео YouTube <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Ссылка на видео из YouTube.
                        </pre>
                        <label class="TUI_input">
                            <input type="text" id="g_v_yt_url" class="g_field"
                                   placeholder="Пример: https://www.youtube.com/watch?v=xLQ6OCT5XUU">
                        </label>
                    </div>
                </div>
                <div class="g_control">
                    <button type="button" class="g_add_btn">Добавить видео</button>
                </div>
                <!-- Превью для видео -->
                <div class="g_preview"></div>
            </div>

            <!-- Аудио -->
            <div class="type_section" data-type="audio" hidden>
                <div class="TUI_row">
                    <div class="TUI_col-3">
                        Порядок <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Порядковый номер аудио трека в галерее.
                        </pre>
                        <label class="TUI_number">
                            <input type="number" class="TUI_InputNumber order" min="1">
                        </label>
                    </div>
                    <div class="TUI_col-9">
                        Путь к треку <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Нажмите на значок папки и выберите файл
                            трека в менеджере файлов. Можно указать
                            абсолютный путь к треку, который доступен
                            из Интернета.
                            ВНИМАНИЕ! Чтобы ваш трек можно было прослушать
                            в разных браузерах, сохраните его
                            в форматах .mp3, .ogg, .wav с одинаковым
                            именем файла. Все три файла должны находиться
                            в одном каталоге. Если браузер не поддерживает
                            один из форматов &mdash; он загружает другой.
                        </pre>
                        <div class="TUI_fieldset">
                            <label class="TUI_input">
                                <input type="text" id="g_a_url" class="g_field">
                            </label>
                            <a href="#" class="fas fa-folder-open fa-2x TUI_blue"
                               onclick="files('g_a_url');return false"></a>
                        </div>
                    </div>
                </div>
                Название трека <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                <pre class="TUI_Hint">
                    Оставьте поле пустым если хотите чтобы
                    трек в плейлисте назывался именем файла.
                </pre>
                <label class="TUI_input">
                    <input type="text" id="g_a_title" class="g_field" oninput="TUI.Lim(this, 75)"
                           placeholder="Пример: Лунная соната">
                </label>
                <div class="g_control">
                    <button type="button" class="g_add_btn">Добавить трек</button>
                </div>
                <!-- Превью для аудио -->
                <div class="g_preview"></div>
            </div>
            <textarea id="g_opt" class="no-emmet" name="gallery_opt" hidden><?= $opt ?></textarea>
        </div>

        <style>
            #gallery .g_preview {
                max-height: 130px;
                overflow-x: auto;
                margin: .5em 0 0 0;
            }

            #gallery .type_section[data-type="img_desc"] .g_preview, #gallery .type_section[data-type="video_yt"] .g_preview {
                margin-right: -1%;
            }

            #gallery .type_section[data-type="img_desc"] .prev_item, #gallery .type_section[data-type="video_yt"] .prev_item {
                display: inline-flex;
                justify-content: flex-end;
                align-items: flex-start;
                margin: 0 1% 1% 0;
                width: 19%;
                height: 120px;
                min-width: 150px;
            }

            #gallery .type_section[data-type="audio"] .prev_item_control.player_control {
                color: var(--color-form);
                border: none;
                background: none;
            }

            #gallery .type_section[data-type="audio"] .prev_item_control.player_control:hover {
                color: var(--color-form-border);
            }
        </style>

        <script>
            /**
             * Модуль выбора типа галереи и управления мультимедиа
             *
             * @returns {void}
             */
            ;(function ($) {
                let _type = $('#g_type').val() || 'img_folder', // тип галереи
                    _section = $('[data-type=' + _type + ']'), // секция типа галереи
                    _g_opt = $('#g_opt'), // textarea с json данными галереи
                    _opt = !_g_opt.val() ? {} : JSON.parse(_g_opt.val());// объект данных галереи

                /**
                 * Валидация полей формы
                 *
                 * @param {string} id Идентификатор мультимедиа
                 * @returns {boolean}
                 */
                const _validator = function (id) {
                    let opt_count = Object.keys(_opt).length, // всего в галерее
                        order = _section.find('.order');// поле "порядок"

                    // валидация поля "порядок"
                    if (order[0]) {
                        if (!/^[1-9]\d*$/.test(order.val())) {
                            alert('Поле "Порядок" не заполнено или заполнено некорректно!\nТолько целое число больше нуля!');
                            return false;
                        }
                        if (typeof id !== "undefined" && parseInt(order.val(), 10) > opt_count) {
                            alert(`Поле "Порядок" заполнено некорректно!\nТолько целое число в пределах 1- ${opt_count}!`);
                            return false;
                        }
                    }

                    switch (_type) {

                        // валидация полей типа "Каталог с изображениями"
                        case 'img_folder':
                            // поле "путь к каталогу"
                            if (!/\S/.test($('#g_f_folder_url').val())) {
                                alert('Выберите каталог!');
                                return false;
                            }
                            break;

                        // валидация полей типа "Изображения с описанием"
                        case 'img_desc':
                            // поле "путь к изображению"
                            if (!/^(https?:\/\/)[\(\)\s\w\.\/-]+\.(png|jpg|jpeg|gif|webp|svg)$/i.test($('#g_f_url').val())) {
                                alert('Выберите изображение!');
                                return false;
                            }
                            // поле "заголовок" и "описание"
                            if (!/\S/.test($('#g_f_title').val()) && !/\S/.test($('#g_f_desc').val())) {
                                alert('Заполните поля: "Заголовок" и "Описание", либо одно из них');
                                return false;
                            }
                            break;

                        // валидация полей типа "Видео c YouTube"
                        case 'video_yt':
                            // поле "путь к видео YouTube"
                            if (!/\S/.test($('#g_v_yt_url').val())) {
                                alert('Заполните поле "Путь к видео YouTube"!');
                                return false;
                            }
                            break;

                        // валидация полей типа "Аудио"
                        case 'audio':
                            // поле "путь к треку"
                            if (!/^(https?:\/\/)[\(\)\s\w\.\/-]+\.(mp3|wav|ogg)$/i.test($('#g_a_url').val())) {
                                alert('Выберите аудио файл!');
                                return false;
                            }
                            break;
                    }

                    // валидация пройдена успешно
                    return true;
                };

                /**
                 * Открыть форму добавления
                 *
                 * @param {string} type Тип галереи
                 * @returns {void}
                 */
                const _get_add_form = function (type) {
                    let s = $('[data-type=' + type + ']');// секция типа
                    $('.type_section').hide();// скрыть формы всех типов
                    s.slideDown(200);// открыть форму типа
                    _type = type;// записать тип в глобальную переменную
                    _section = s;// записать секцию в глобальную переменную
                    _clear();// очистить и подготовить форму типа к добавлению
                };

                /**
                 * Открыть форму редактирования
                 *
                 * @param {string} id Идентификатор мультимедиа
                 * @returns {void}
                 */
                const _get_edit_form = function (id) {
                    let control = _section.find('.g_control'), // блок кнопок секции: "добавить", "редактировать", "удалить все"...
                        edit_btn = $('<button/>', {
                            class: 'g_edit_btn',
                            type: 'button',
                            text: 'Редактировать мультимедиа'
                        })
                            .on('click.G', function () {
                                _edit(id);
                            }),
                        cancel_btn = $('<button/>', {class: 'g_cancel_btn', type: 'button', text: 'Отмена'})
                            .on('click.G', _clear);

                    // задать порядковый номер и разблокировать поле
                    _section.find('.order').val(id).attr({'readonly': false, 'max': Object.keys(_opt).length});
                    // добавить кнопки "редактировать", "отмена" к форме
                    control.html([edit_btn, cancel_btn]);
                    // заполнить поля типа
                    switch (_type) {

                        case 'img_folder':
                            $('#g_f_folder_url').val(_opt.f_folder);
                            break;

                        case 'img_desc':
                            $('#g_f_url').val(_opt[id].f_url);
                            $('#g_f_title').val(_opt[id].f_title);
                            $('#g_f_desc').val(_opt[id].f_desc);
                            break;

                        case 'video_yt':
                            $('#g_v_yt_url').val(_opt[id].yt_url);
                            break;

                        case 'audio':
                            $('#g_a_url').val(_opt[id].a_url);
                            $('#g_a_title').val(_opt[id].a_title);
                            break;
                    }
                };

                /**
                 * Получить id ролика youtube
                 *
                 * @param {string} url URL ролика
                 * @returns {string}
                 */
                const _get_yt_id = function (url) {
                    let match = url.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
                    if (match && match[7].length === 11) return match[7];
                    alert(`Некорректный путь к видео:\n${url}`);
                    return;
                };

                /**
                 * Добавить мультимедиа
                 *
                 * @returns {void}
                 */
                const _add = function () {

                    // валидация полей
                    if (!_validator()) return;

                    let order = _section.find('.order');

                    switch (_type) {

                        case 'img_folder':
                            _opt = {
                                f_folder: $('#g_f_folder_url').val()
                            };
                            break;

                        case 'img_desc':
                            _opt[order.val()] = {
                                f_url: $('#g_f_url').val(),
                                f_title: $('#g_f_title').val(),
                                f_desc: $('#g_f_desc').val()
                            };
                            _clear();
                            break;

                        case 'video_yt':
                            let yt_url = $('#g_v_yt_url'),
                                yt_id = _get_yt_id(yt_url.val());
                            if (yt_id) {
                                _opt[order.val()] = {
                                    yt_url: yt_url.val(),
                                    yt_id: yt_id
                                };
                            } else {
                                return;
                            }
                            _clear();
                            break;

                        case 'audio':
                            let a_url = $('#g_a_url').val(),
                                a_title = !$('#g_a_title').val()
                                    ? a_url.split('/').pop().replace(/\.(mp3|wav|ogg)$/i, '')
                                    : $('#g_a_title').val();
                            _opt[order.val()] = {
                                a_url: a_url,
                                // a_ext: a_url.split('.').pop(),
                                // a_file: a_url.replace(/\.(mp3|wav|ogg)$/i, ''),
                                a_title: a_title
                            };
                            _clear();
                            break;
                    }

                    _g_opt.val(JSON.stringify(_opt));
                    _show();
                };

                /**
                 * Редактировать мультимедиа
                 *
                 * @param {string} id Идентификатор мультимедиа
                 * @returns {void}
                 */
                const _edit = function (id) {

                    // валидация полей
                    if (!_validator(id)) return;

                    let order = _section.find('.order').val(), // порядок
                        int_order = parseInt(order, 10), // новый порядковый номер в число
                        int_id = parseInt(id, 10), // текущий порядковый номер в число
                        temp = {};// если изменится порядковый номер - будет хранить объект с новым порядком

                    // проверка числа в диапазоне
                    Number.prototype.between = function (a, b) {
                        let min = Math.min(a, b), max = Math.max(a, b);
                        return this >= min && this <= max;
                    };

                    // если порядковый номер изменился
                    if (order !== id) {

                        for (let k in _opt) {
                            let int_k = parseInt(k, 10);// текущий порядковый номер (ключ) в число
                            if (int_k.between(int_id, int_order)) {// текущий ключ входит в диапазон
                                if (int_k === int_id) {// текущий ключ и текущий порядковый номер совпали
                                    switch (_type) {// записать измененный элемент и назначить новый порядковый номер

                                        case 'img_desc':
                                            temp[order] = {
                                                f_url: $('#g_f_url').val(),
                                                f_title: $('#g_f_title').val(),
                                                f_desc: $('#g_f_desc').val()
                                            };
                                            break;

                                        case 'video_yt':
                                            let yt_url = $('#g_v_yt_url'),
                                                yt_id = _get_yt_id(yt_url.val());
                                            if (yt_id) {
                                                temp[order] = {
                                                    yt_url: yt_url.val(),
                                                    yt_id: yt_id
                                                };
                                            } else {
                                                return;
                                            }
                                            break;

                                        case 'audio':
                                            let a_url = $('#g_a_url').val(),
                                                a_title = !$('#g_a_title').val()
                                                    ? a_url.split('/').pop().replace(/\.(mp3|wav|ogg)$/i, '')
                                                    : $('#g_a_title').val();
                                            temp[order] = {
                                                a_url: a_url,
                                                a_title: a_title
                                            };
                                            break;
                                    }
                                } else {// не совпали - увеличить или уменьшить номер в диапазоне чтобы сдвинуть
                                    int_id > int_order ? temp[int_k + 1] = _opt[k] : temp[int_k - 1] = _opt[k];
                                }
                            } else {//текущий ключ не входит в диапазон - оставить как есть
                                temp[k] = _opt[k];
                            }
                        }

                        _opt = temp;
                        _g_opt.val(JSON.stringify(_opt));
                        _clear();
                        _show();
                        return;
                    }

                    // порядковый номер не изменился
                    _add();
                };

                /**
                 * Очистить поля формы
                 *
                 * @returns {void}
                 */
                const _clear = function () {
                    let order = Object.keys(_opt).length + 1, // следующий порядковый номер элемента
                        control = _section.find('.g_control'), // блок кнопок секции: "добавить", "редактировать", "удалить все"...
                        add_btn = $('<button/>', {class: 'g_add_btn', type: 'button', text: 'Добавить мультимедиа'})
                            .on('click.G', _add),
                        clear_btn = $('<button/>', {class: 'g_clear_btn', type: 'button', text: 'Удалить все'})
                            .on('click.G', function () {
                                if (confirm('Все мультимедиа будут удалены!\nВыполнить действие?')) _del_all();
                            });

                    // очистить поля формы типа
                    _section.find('.g_field').val('');
                    // задать порядковый номер и заблокировать поле
                    _section.find('.order').val(order).attr({'readonly': true, 'max': order});
                    // добавить кнопк(у|и)
                    if ($.isEmptyObject(_opt)) {
                        control.html(add_btn);
                    } else {
                        control.html([add_btn, clear_btn]);
                    }
                };

                /**
                 * Удалить мультимедиа
                 *
                 * @param {string} id Идентификатор мультимедиа
                 * @returns {void}
                 */
                const _del = function (id) {
                    let int_id = parseInt(id, 10);

                    for (let k in _opt) {
                        let int_k = parseInt(k, 10);
                        int_k > int_id ? _opt[int_k - 1] = _opt[k] : null;
                        int_k === Object.keys(_opt).length ? delete _opt[k] : null;
                    }

                    if ($.isEmptyObject(_opt)) {
                        _del_all();
                    } else {
                        _g_opt.val(JSON.stringify(_opt));
                        _clear();
                        _show();
                    }
                };

                /**
                 * Удалить все мультимедиа
                 *
                 * @returns {void}
                 */
                const _del_all = function () {
                    _opt = {};
                    _g_opt.val('');
                    $('.g_preview').empty();
                    _clear();
                };

                /**
                 * Отобразить превью
                 *
                 * @returns {void}
                 */
                const _show = function () {

                    // если нет данных
                    if ($.isEmptyObject(_opt)) return;

                    let g_preview = _section.find('.g_preview').empty();
                    switch (_type) {

                        case 'img_folder':
                            $('#g_f_folder_url').val(_opt.f_folder);
                            break;

                        case 'img_desc':
                            for (let k in _opt) {
                                let info = _opt[k].f_title || _opt[k].f_desc
                                    ? $('<span/>', {class: 'prev_item_control fas fa-info-circle'})
                                    : $(),
                                    desc = $('<div/>', {
                                        class: 'TUI_Hint',
                                        html: '<h3>' + _opt[k].f_title + '</h3>' + _opt[k].f_desc
                                    }),
                                    edit_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-edit',
                                        title: 'Редактировать'
                                    }).data('id', k),
                                    del_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-trash-alt',
                                        title: 'Удалить'
                                    }).data('id', k),
                                    prev_item = $('<div/>', {
                                        class: 'prev_item',
                                        css: {'background-image': 'url(' + _opt[k].f_url + ')'},
                                        html: [info, edit_btn, del_btn]
                                    });
                                edit_btn.on('click.G', function () {
                                    _get_edit_form($(this).data('id'));
                                });
                                del_btn.on('click.G', function () {
                                    if (confirm('Этот элемент будет удален!\nВыполнить действие?')) _del($(this).data('id'));
                                });
                                info.after(desc).on('mouseover.G', function () {
                                    TUI.Hint(this);
                                });
                                g_preview.append(prev_item);
                            }
                            break;

                        case 'video_yt':
                            for (let k in _opt) {
                                let edit_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-edit',
                                        title: 'Редактировать'
                                    }).data('id', k),
                                    del_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-trash-alt',
                                        title: 'Удалить'
                                    }).data('id', k),
                                    prev_item = $('<div/>', {
                                        class: 'prev_item',
                                        css: {'background-image': 'url(https://img.youtube.com/vi/' + _opt[k].yt_id + '/mqdefault.jpg)'},
                                        html: [edit_btn, del_btn]
                                    });
                                edit_btn.on('click.G', function () {
                                    _get_edit_form($(this).data('id'));
                                });
                                del_btn.on('click.G', function () {
                                    if (confirm('Этот элемент будет удален!\nВыполнить действие?')) _del($(this).data('id'));
                                });
                                g_preview.append(prev_item);
                            }
                            break;

                        case 'audio':
                            $.each(_opt, function (k) {
                                let audio = $('<audio/>', {src: _opt[k].a_url}),
                                    play = $('<i/>', {class: 'prev_item_control player_control fas fa-play'}),
                                    pause = $('<i/>', {class: 'prev_item_control player_control fas fa-pause'}),
                                    title = $('<span/>', {
                                        class: 'prev_item_content',
                                        text: _opt[k].a_title.length > 100
                                            ? _opt[k].a_title.substring(0, 100) + '...'
                                            : _opt[k].a_title
                                    }),
                                    edit_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-edit',
                                        title: 'Редактировать'
                                    }).data('id', k),
                                    del_btn = $('<span/>', {
                                        class: 'prev_item_control fas fa-trash-alt',
                                        title: 'Удалить'
                                    }).data('id', k),
                                    prev_item = $('<div/>', {
                                        class: 'prev_item',
                                        html: [play, pause, title, edit_btn, del_btn]
                                    });
                                edit_btn.on('click.G', function () {
                                    _get_edit_form($(this).data('id'));
                                });
                                del_btn.on('click.G', function () {
                                    if (confirm('Этот элемент будет удален!\nВыполнить действие?')) _del($(this).data('id'));
                                });
                                audio.on('error.G', function () {
                                    title.before('<span class="TUI_red">[FILE ERROR!] </span>');
                                });
                                play.on('click.G', function () {
                                    audio[0].play();
                                    if (!audio[0].paused) play.addClass('TUI_green');
                                });
                                pause.on('click.G', function () {
                                    audio[0].pause();
                                    if (audio[0].paused) play.removeClass('TUI_green');
                                });
                                g_preview.append(prev_item);
                            });
                            break;
                    }
                };

                // выбор типа галереи
                $('#g_type').on('change.G', function () {
                    if (!_g_opt.val()) {
                        _get_add_form($(this).val());
                    } else {
                        if (!confirm('Мультимедиа в галерее могут быть только одного типа.\nИзменить тип галереи и удалить добавленные мультимедиа?')) {
                            $(this).html($(this).html());
                            return;
                        } else {
                            _del_all();
                            _get_add_form($(this).val());
                        }
                    }
                });

                // добавить "каталог с изображениями"
                $('#g_f_folder_url').on('change.G', _add);

                // открыть форму добавления
                _get_add_form(_type);

                // отобразить превью
                _show();

            }(jQuery));
        </script>

        <?php
    }
}
