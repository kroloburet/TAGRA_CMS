/**
 * Сценарии для административной части системы
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */

/**
 * Выполнить после готовности DOM
 */
$(function () {
    // загрузка альтернативного изображения
    $('img').on('error', function () {
        $(this).attr('src', '/img/noimg.jpg');
    });

    // показать в пункте меню (комментарии) количество новых комментариев
    $('#count_new_comments')
        .load(
            '/admin/comment/get_count_new',
            function (data) {
                let count = $('#count_new_comments');
                data > 0 ? count.addClass('fas fa-bell TUI_red') : count.remove();
            }
        );
});

/**
 * Управление справкой пользователю системы
 *
 * @returns {void}
 */
function off_notific() {
    document.getElementById('notific_work_info').style.display = 'none';
}

localStorage.getItem('notific_work_info') ? off_notific() : null;

//localStorage.removeItem('notific_work_info');

/**
 * Превью изображения по url из поля
 *
 * @param {object} targ Ссылка "this" на триггер события
 * @param {string} input Селектор поля с URL
 * @returns {void}
 */
function img_prev(targ, input) {
    let t = targ,
        i = $(input),
        h = $(targ).next('.TUI_Hint');
    if (i.val()) {
        // поле не пустое
        h.html(`<img src="${i.val()}" style="max-width:30vw">`);
        TUI.Hint(t);
    } else {
        // поле пустое
        h.empty();
        TUI.Hint(t);
    }
}

/**
 * Валидация полей и отправка формы
 *
 * @param {object} form Ссылка "form|this" на отправляемую форму
 * @param {object} req Обьект для валидации полей {имя поля:/рег.выражение/}
 * @returns {void|boolean}
 */
function subm(form, req) {
    const f = $(form),
        control = f.find('.this_form_control'),
        control_html = control.html(),
        msg = function (m, s, d, c) {
            m = m || 'Отмеченое красным поле некорректно заполнено!';
            s = s || 'TUI_notice-r';
            d = d || 4000;
            c = c || function () {
                control.html(control_html);
            };
            control.html(`<p class="${s}">${m}</p>`);
            setTimeout(c, d);
        };

    // поместить контент редактора в поле отправки
    typeof tinyMCE !== 'undefined' ? tinyMCE.triggerSave() : null;

    // валидация полей
    if (typeof req === 'object') {
        for (let key in req) {
            let el = f.find(`[name="${key}"]`);// проверяемое поле
            if (el.length > 1) {
                // группа полей типа name="name[]" или такие поля динамически создаются
                for (let i = 0; el.length > i; ++i) {
                    if (req[key].test(el[i].value)) {
                        // валидация пройдена
                        el[i].className = el[i].className.replace(/(\sTUI_novalid)/g, '');
                    } else {
                        // валидация не пройдена
                        el[i].className += ' TUI_novalid';
                        el[i].focus();
                        msg();
                        return false;
                    }
                }
            } else {
                // обычное поле типа name="name"
                // выделить поле. если поле - select или file - выделить родительский label
                if (req[key].test(el.val())) {
                    // валидация пройдена
                    el.is('select') || el.is('input:file')
                        ? el.parent('label').removeClass('TUI_novalid')
                        : el.removeClass('TUI_novalid');
                } else {
                    // валидация не пройдена
                    el.is('select') || el.is('input:file')
                        ? el.parent('label').addClass('TUI_novalid').focus()
                        : el.addClass('TUI_novalid').focus();
                    msg();
                    return false;
                }
            }
        }
    }

    // отправка
    control.html('<i class="fas fa-spin fa-spinner"></i>&nbsp;обработка...');
    $.ajax({
        url: f.attr('action'),
        type: 'post',
        data: f.serializeArray(),
        dataType: 'json',
        success: function (resp) {
            switch (resp.status) {
                case 'ok':
                    msg(
                        'Данные успешно сохранены!',
                        'TUI_notice-g',
                        1000,
                        (resp.redirect ? function () {
                            location.href = resp.redirect;
                        } : null)
                    );
                    break;
                case 'error':
                    resp.msg
                        ? msg(resp.msg)
                        : msg(`Ой! Ошибка..( Данные не сохранены.<br>Проверьте, правильно ли заполнены поля и повторите попытку.`);
                    break;
                default :
                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                    msg(`Ой! Что-то пошло не так..(<br>Сведения о неполадке выведены в консоль.`);
            }
        },
        error: function (xhr, status, thrown) {
            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
            msg(`Ой! Ошибка соединения..(<br>Сведения о неполадке выведены в консоль.<br>Возможно это проблемы на сервере или с сетью Интернет. Повторите попытку.`);
        }
    });
}

/**
 * Генерировать пароль и вставить в поле
 *
 * @param {string} id Селектор поля для вставки пароля
 * @returns {void}
 */
function gen_pass(el) {
    let passwd = '',
        chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~@#$[]_-';
    for (let i = 1; i < 11; i++) {
        let c = Math.floor(Math.random() * chars.length + 1);
        passwd += chars.charAt(c);
    }
    $(el).val(passwd);
}

/**
 * Проверка уникальности "title" в таблице БД
 *
 * @param {object} elem Ссылка "this" на поле name="title"
 * @param {string} id Идетификатор текущего материала
 * @param {string} tab Имя таблицы текущего материала
 * @param {string} msg Сообщение, если title не уникальный
 * @returns {void}
 */
function check_title(elem, id, tab, msg) {
    $.post(
        "/admin/check_title",
        {title: $(elem).val(), id: id, tab: tab},
        function (resp) {
            switch (resp) {
                case 'ok':
                    $(elem).removeClass('TUI_novalid');
                    $('.this_form_control button').attr('disabled', false);
                    break;
                case 'found':
                    $(elem).addClass('TUI_novalid').focus();
                    $('.this_form_control button').attr('disabled', true);
                    alert(msg);
                    break;
                default:
                    $(elem).addClass('TUI_novalid').focus();
                    $('.this_form_control button').attr('disabled', true);
                    alert(resp);
            }
        }
    );
}

/**
 * Переключить значение публикации материала
 *
 * @param {object} el Ссылка "this" на триггер
 * @param {string} id Идентификатор материала
 * @param {string} tab Имя таблицы материала
 * @returns {void}
 */
function toggle_public(el, id, tab) {
    let self = $(el),
        process = $('<i/>', {class: 'fas fa-spin fa-spinner'});
    self.replaceWith(process);
    $.ajax({
        url: '/admin/toggle_public',
        type: 'post',
        data: {id: id, tab: tab},
        dataType: 'text',
        success: function (resp) {
            switch (resp) {
                case '1':
                    process.replaceWith(self.removeClass().addClass('fas fa-eye'));
                    break;
                case '0':
                    process.replaceWith(self.removeClass().addClass('fas fa-eye-slash TUI_red'));
                    break;
                case 'error':
                    process.replaceWith(self);
                    alert('Ой! Ошибка..( Данные не сохранены.\nПопробуйте повторить попытку.');
                    break;
                default :
                    process.replaceWith(self);
                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                    alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
            }
        },
        error: function (xhr, status, thrown) {
            process.replaceWith(self);
            console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
        }
    });
}

/**
 * Запуск менеджера файлов
 *
 * @param {string|null} field Селектор/ы поля, куда будет вставлен путь к файлу
 * @param {string|null} lang Тег языка
 * @param {object} user_conf Пользовательская конфигурация
 * @returns {void}
 */
function files(field = null, lang = null, user_conf = {}) {
    let insert = !!field;
    let conf = {
        title: 'Менеджер файлов',
        view: 'thumbs',
        leftpanel: false,
        width: 720,
        height: 400,
        rootpath: lang ? `/upload/${lang}` : `/upload/`,
        fields: field,
        insert: insert,
        onopen: setTimeout(files_notice, 500)
    };
    $.extend(true, conf, user_conf);
    moxman.browse(conf);

    function files_notice() {
        $('.moxman-window-head').after(`<div style="padding:4px;background-color:#ffc0a2">Внимание! Не используйте кириллицу и пробелы в именах файлов и папок!</div>`);
    }
}
