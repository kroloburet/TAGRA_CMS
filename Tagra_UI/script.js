'use strict';
/**
 * Tagra_UI scripts
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */
const TUI = (function () {

    /**
     * Private
     */

    /**
     * Точка входа для плагинов
     *
     * @param methods Методы плагина
     * @return {object} Метод плагина
     * @private
     */
    function _is(methods) {
        if (this[methods]) {
            // метод существует
            return this[methods].apply(this, [].slice.call(arguments, 1));
        } else if (typeof methods === 'object' || !methods) {
            // метод не передан - запустить "init"
            return this.init.apply(this, arguments);
        } else {
            // метод не существует
            return console.error(`Вы о чем?! Здесь нет метода "${methods}"`);
        }
    }

    /**
     * Проверка метки активации плагина на элементе.
     *
     * @param {object} el Проверяемый элемент
     * @param {string} selfName Имя плагина
     * @return {boolean}
     * @private
     */
    function _isActivate(el, selfName) {
        return el.classList.contains(`TUI_${selfName}-activated`);
    }

    /**
     * Установить метку активации плагина на элементе
     *
     * @param {object} el Элемент для установки метки
     * @param {string} selfName Имя плагина
     * @private
     */
    function _markActivate(el, selfName) {
        return el.classList.add(`TUI_${selfName}-activated`);
    }

    /**
     * Удалить метку активации плагина на элементе
     * вместе с динамически созданными классами
     *
     * @param {object} el Элемент для удаления метки
     * @param {string} selfName Имя плагина
     * @private
     */
    function _unmarkActivate(el, selfName) {
        el.querySelectorAll(`[class^=TUI_${selfName}]`).forEach(child => {
            let list = [...child.classList].filter(cssClass => new RegExp(`TUI_${selfName}`).test(cssClass));
            child.classList.remove(...list);
        });
        return el.classList.remove(`TUI_${selfName}-activated`);
    }

    /**
     * Public
     */

    return {

        /**
         * Helpers
         */

        /**
         * Переключение отображения элемента
         *
         * @param {string} id Идентификатор элемента
         * @param {string} display Значение CSS-свойства display видимого элемента
         * @return {object} Элемент
         * @constructor
         */
        Toggle(id, display = 'block') {
            const selfName = 'Toggle';
            const content = document.getElementById(id);
            if (!content) return;
            // обработка появления/скрытия
            const visible = content.style.display;
            if (visible === 'none' || content.hidden) {
                content.style.display = display;
                content.hidden = false;
            } else {
                content.style.display = 'none';
            }
            return content;
        },

        /**
         * Всплывающая подсказка
         *
         * @param {object} trigger "this" Ссылка на триггер
         * @param {string} hideEvent Событие для скрытия подсказки без префикса "on"
         * @return {object} Элемент подсказки
         */
        Hint(trigger, hideEvent = 'mouseout') {
            const selfName = 'Hint';
            const hint = trigger.nextElementSibling;
            if (!hint) return;
            const w = hint.offsetWidth;
            const h = hint.offsetHeight;
            const win = window;
            const hide = () => {
                hint.classList.remove(`TUI_${selfName}-show`);
                hint.style.left = 0;
            }
            // обработка положения подсказки
            trigger.addEventListener('mousemove', e => {
                const cursor = {x: e.pageX, y: e.pageY};
                const distance = { // дистанция указателя до правого и нижнего края
                    right: win.innerWidth - (cursor.x - win.pageXOffset),
                    bottom: win.innerHeight - (cursor.y - win.pageYOffset)
                };
                // разместить слева указателя если близко к правому краю
                hint.style.left = distance.right < w
                    ? cursor.x - w < 0
                        ? 0 // закрепить у левого края если значение отрицательное
                        : cursor.x - w + 'px'
                    : (cursor.x + 15) + 'px';
                // разместить над указателем если близко к нижнему краю
                hint.style.top = distance.bottom < (h + 15)
                    ? (cursor.y - 15) - h + 'px'
                    : (cursor.y + 15) + 'px';
            });
            // показать подсказку
            hint.classList.add(`TUI_${selfName}-show`);
            // обработка скрытия подсказки
            document.addEventListener(hideEvent, hide);
            return hint;
        },

        /**
         * Лимит ввода символов в поле
         *
         * @param {object} trigger "this" Ссылка на поле
         * @param {number|string} limit Лимит символов
         * @return {object} Поле
         */
        Lim(trigger, limit = 50) {
            const selfName = 'Lim';
            if (typeof limit !== 'number') limit = parseInt(limit);
            let val = trigger.value;
            let counter = trigger.parentElement.querySelector(`span.TUI_${selfName}`);
            const cut = () => trigger.value = trigger.value.substr(0, limit);
            // создать и прикрепить счетчик если не определен
            if (!counter) {
                counter = document.createElement('span');
                let paddingR = trigger.style.paddingRight; // отступ в поле (начальный)
                counter.classList.add(`TUI_${selfName}`);
                counter.textContent = limit.toString();
                trigger.after(counter);
                trigger.addEventListener('blur', () => {
                    cut(); // обрезать значение до лимита
                    counter.remove(); // удалить счетчик
                    trigger.style.paddingRight = paddingR; // вернуть начальный отступ полю
                });
                trigger.style.paddingRight = counter.offsetWidth + 'px'; // отступ полю на ширину счетчика
            }
            // обработка длины значения поля
            if (val.length <= limit) {
                counter.textContent = (limit - val.length).toString();
            } else {
                cut();
                counter.textContent = '0';
            }
            return trigger;
        },

        /**
         * Переход к элементу
         *
         * @param {string|null} selectorId Селектор id элемента или ничего
         * @return {object} Элемент
         * @constructor
         */
        GoTo(selectorId = null) {
            const selfName = 'GoTo';
            const target = selectorId || location.hash; // id элемента или хеш в url
            if (!target) return;
            const el = document.getElementById(target.replace(/^#/, ''));
            setTimeout(() => {
                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }, 200);
            return el;
        },

        // /**
        //  * Шаблон Хелпера TUI
        //  */
        // Helper(prop) {
        //     const selfName = 'Helper'; // arguments.callee.name
        //     console.log('Hello from TUI.Helper()', this, arguments);
        // },

        /**
         * Plugins
         */

        /**
         * Всплывающее окно
         *
         * @param {string|null} id Идентификатор элемента или ничего
         * @return {object} Элемент
         * @constructor
         */
        Popup(id = null) {
            const selfName = 'Popup';
            if (!id || !document.getElementById(id)) {

                // Активация плагина на элементах коллекции

                const collection = document.querySelectorAll(`.TUI_${selfName}`);
                collection.forEach(pop => {
                    // обрабатывать только неактивированный элемент
                    if (_isActivate(pop, selfName)) return;
                    // добавить обертку, кнопки и события
                    const box = document.createElement('div');
                    const close = document.createElement('span');
                    const hide = () => {
                        box.classList.remove(`TUI_${selfName}-show`);
                        document.body.classList.remove(`TUI_${selfName}-body`);
                    }
                    box.classList.add(`TUI_${selfName}-box`);
                    box.onclick = e => e.target === box ? hide() : null;
                    close.classList.add(`TUI_${selfName}-close`, 'fas', 'fa-times-circle');
                    close.onclick = hide;
                    pop.prepend(close);
                    box.prepend(pop);
                    document.body.append(box);
                    // пометить элемент как активированный
                    _markActivate(pop, selfName);
                });
                return collection;
            } else {

                // Показать popup по id

                const pop = document.getElementById(id);
                document.body.classList.add(`TUI_${selfName}-body`);
                pop.closest(`.TUI_${selfName}-box`).classList.add(`TUI_${selfName}-show`);
                return pop;
            }
        },

        /**
         * Меню
         */
        Menu() {
            const selfName = 'Menu';
            const defConf = {
                selector: `.TUI_${selfName}`,
                icon: '&#8801;',
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'UL');
                    collection.forEach(menu => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(menu, selfName)) methods.kill(conf.selector);
                        // прикрепить элементы управления и классы, прослушку событий
                        const toggle = el => el.classList.toggle(`TUI_${selfName}-show`);
                        const btn = document.createElement('i');
                        btn.classList.add(`TUI_${selfName}-btn`);
                        btn.innerHTML = conf.icon;
                        btn.onclick = () => toggle(menu);
                        menu.before(btn);
                        menu.querySelectorAll('ul').forEach(ul => {
                            const subBtn = document.createElement('i');
                            subBtn.classList.add(`TUI_${selfName}-sub-btn`);
                            subBtn.innerHTML = conf.icon;
                            subBtn.onclick = () => toggle(ul);
                            ul.previousElementSibling.classList.add(`TUI_${selfName}-sub-link`);
                            ul.before(subBtn);
                        });
                        // пометить корневой "li" с дочерней ссылкой на текущую страницу
                        menu.querySelectorAll('a').forEach(a => {
                            const l = location;
                            if (l.pathname === a.href || l.pathname + l.search === a.href || l.href === a.href)
                                a.closest(`${conf.selector} > li`).classList.add(`TUI_${selfName}-mark`);
                        });
                        // пометить как активированный
                        _markActivate(menu, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'UL' && _isActivate(el, selfName));
                    collection.forEach(menu => {
                        // удалить динамически созданные кнопки и классы
                        menu.previousElementSibling.remove();
                        menu.querySelectorAll(`.TUI_${selfName}-sub-btn`).forEach(el => el.remove());
                        // удалить метку активации
                        _unmarkActivate(menu, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        /**
         * Tабы
         */
        Tab() {
            const selfName = 'Tab';
            const defConf = {
                selector: `.TUI_${selfName}`,
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'DL');
                    collection.forEach(tab => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(tab, selfName)) methods.kill(conf.selector);
                        // добавить классы и прослушку событий
                        const dtAll = tab.querySelectorAll('dt');
                        let visibleFlag = false;
                        dtAll.forEach(dt => {
                            dt.onclick = ({target}) => {
                                target.parentElement.querySelectorAll('dt')
                                    .forEach(dt => dt.classList.remove(`TUI_${selfName}-show`));
                                target.classList.add(`TUI_${selfName}-show`);
                            };
                            if (dt.classList.contains(`TUI_${selfName}-show`)) visibleFlag = true;
                        });
                        if (!visibleFlag) dtAll[0].classList.add(`TUI_${selfName}-show`);
                        // пометить элемент как активированный
                        _markActivate(tab, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'DL' && _isActivate(el, selfName));
                    collection.forEach(tab => {
                        // удалить привязку событий
                        tab.querySelectorAll('dt')
                            .forEach(dt => dt.onclick = null);
                        // удалить метку активации
                        _unmarkActivate(tab, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        /**
         * Input type="file"
         */
        InputFile() {
            const selfName = 'InputFile';
            const defConf = {
                selector: `.TUI_${selfName}`,
                icon: '<i class="fas fa-folder-open">',
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'file');
                    collection.forEach(inputFile => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(inputFile, selfName)) methods.kill(conf.selector);
                        // добавить элементы, классы и прослушку событий
                        const info = document.createElement('span');
                        const innerInfo = () => {
                            let files = inputFile.files;
                            let name = '';
                            let size = 0;
                            if (files && files.length) {
                                // файлы выбраны
                                [...files].forEach(file => {
                                    name += files.length > 1 ? `<span class="TUI_${selfName}-val">${file.name}</span>` : file.name;
                                    size += file.size;
                                });
                                // общий размер файлов в mb
                                size = (size / 1048576).toFixed(3);
                                // отобразить список файлов и размер в информационном элементе
                                info.innerHTML = `${name} (${size} Mb)`;
                            } else {
                                // файлы не выбраны
                                info.innerHTML = conf.icon;
                            }
                        };
                        // прикрепить инфо-элемент
                        info.classList.add(`TUI_${selfName}-info`);
                        info.innerHTML = conf.icon;
                        inputFile.after(info);
                        innerInfo(); // отобразить информацию если файлы уже выбраны
                        inputFile.onchange = innerInfo;
                        // пометить элемент как активированный
                        _markActivate(inputFile, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'file' && _isActivate(el, selfName));
                    collection.forEach(inputFile => {
                        // вернуть элемент к состоянию до активации
                        inputFile.nextElementSibling.remove();
                        inputFile.onchange = null;
                        _unmarkActivate(inputFile, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        /**
         * Input type="range"
         */
        InputRange() {
            const selfName = 'InputRange';
            const defConf = {
                selector: `.TUI_${selfName}`,
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'range');
                    collection.forEach(inputRange => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(inputRange, selfName)) methods.kill(conf.selector);
                        // добавить элементы, классы и прослушку событий
                        const info = document.createElement('span');
                        info.classList.add(`TUI_${selfName}-info`);
                        info.innerText = inputRange.value || '0';
                        inputRange.after(info);
                        inputRange.onchange = () => info.innerText = inputRange.value;
                        // пометить элемент как активированный
                        _markActivate(inputRange, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'range' && _isActivate(el, selfName));
                    collection.forEach(inputRange => {
                        // вернуть элемент к состоянию до активации
                        inputRange.nextElementSibling.remove();
                        inputRange.onchange = null;
                        _unmarkActivate(inputRange, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        /**
         * Input type="number"
         */
        InputNumber() {
            const selfName = 'InputNumber';
            const defConf = {
                selector: `.TUI_${selfName}`,
                incIcon: '&plus;',
                decIcon: '&minus;',
                info: 'Поставьте курсор в поле и крутите колесико мыши ;)',
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'number');
                    collection.forEach(inputNumber => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(inputNumber, selfName)) methods.kill(conf.selector);
                        // добавить элементы, валидацию и прослушку событий
                        const event = new Event('change');
                        const label = inputNumber.parentElement;
                        const inc = document.createElement('span');
                        const dec = document.createElement('span');
                        const opt = {
                            step: () => parseFloat(inputNumber.step) || 1,
                            max: () => parseFloat(inputNumber.max),
                            min: () => parseFloat(inputNumber.min),
                            val: () => parseFloat(inputNumber.value),
                            up: () => opt.val() + opt.step(),
                            down: () => opt.val() - opt.step(),
                            setValid: () => label.classList.remove('TUI_novalid'),
                            setNoValid: () => label.classList.add('TUI_novalid'),
                            initVal: () => isNaN(opt.val())
                                ? inputNumber.value = (inputNumber.getAttribute('value') || opt.min() || opt.max() || 0)
                                : null,
                            setVal(action) {
                                if (inputNumber.hasAttribute('disabled') || inputNumber.hasAttribute('readonly')) return;
                                opt.initVal();
                                opt.setValid();
                                if (action === 'inc') {
                                    let max = opt.max();
                                    let up = opt.up();
                                    inputNumber.value = isNaN(max) ? up : max > up ? up : max;
                                } else if (action === 'dec') {
                                    let min = opt.min();
                                    let down = opt.down();
                                    inputNumber.value = isNaN(min) ? down : min < down ? down : min;
                                }
                                inputNumber.dispatchEvent(event);
                            }
                        };
                        opt.initVal();
                        inc.classList.add(`TUI_${selfName}-inc`);
                        dec.classList.add(`TUI_${selfName}-dec`);
                        inc.innerHTML = conf.incIcon;
                        dec.innerHTML = conf.decIcon;
                        inputNumber.after(inc);
                        inputNumber.after(dec);
                        inc.addEventListener('click', e => {
                            e.preventDefault();
                            opt.setVal('inc');
                        });
                        dec.addEventListener('click', e => {
                            e.preventDefault();
                            opt.setVal('dec');
                        });
                        inputNumber.oninput = () => {
                            let max = opt.max();
                            let min = opt.min();
                            let val = opt.val();
                            opt.setValid();
                            if (isNaN(val)) opt.setNoValid();
                            else if (!isNaN(max) && val > max) inputNumber.value = max;
                            else if (!isNaN(min) && val < min) inputNumber.value = min;
                        };
                        label.title = conf.info;
                        // пометить элемент как активированный
                        _markActivate(inputNumber, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'INPUT' && el.type === 'number' && _isActivate(el, selfName));
                    collection.forEach(inputNumber => {
                        // вернуть элемент к состоянию до активации
                        inputNumber.parentElement.querySelectorAll('span').forEach(el => el.remove());
                        inputNumber.oninput = null;
                        _unmarkActivate(inputNumber, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        /**
         * Поиск по списку "select"
         */
        SelectSearch() {
            const selfName = 'SelectSearch';
            const defConf = {
                selector: `.TUI_${selfName}`,
                placeholder: 'Поиск по списку',
            };
            const methods = {

                /**
                 * Активация плагина на элементе/тах
                 *
                 * @param {object} userConf Пользовательская конфигурация
                 * @return {object} Коллекция из conf.selector
                 */
                init(userConf = {}) {
                    const conf = Object.assign(defConf, userConf);
                    const collection = [...document.querySelectorAll(conf.selector)]
                        .filter(el => el.tagName === 'SELECT');
                    collection.forEach(select => {
                        // деактивировать текущий чтобы переписать конфиг
                        if (_isActivate(select, selfName)) methods.kill(conf.selector);
                        // добавить элементы, классы и прослушку событий
                        let input = document.createElement('input');
                        let options = select.querySelectorAll('option');
                        let search = () => {
                            if (select.disabled) return;
                            let val = input.value.toLowerCase();
                            options.forEach(option => {
                                option.hidden = !(option.textContent.toLowerCase().indexOf(val) > -1);
                            });
                        };
                        input.classList.add(`TUI_${selfName}-input`);
                        input.type = 'text';
                        input.placeholder = conf.placeholder;
                        input.oninput = search;
                        select.before(input);
                        select.parentElement.addEventListener('focusout', e => {
                            if (!e.relatedTarget) {
                                input.value = '';
                                options.forEach(option => option.hidden = false);
                            }
                        });
                        // пометить элемент как активированный
                        _markActivate(select, selfName);
                    });
                    return collection;
                },

                /**
                 * Деактивация плагина на элементе/тах
                 *
                 * @param {string} selector Селектор элемента/тов
                 * @return {object} Коллекция элементов
                 */
                kill(selector = defConf.selector) {
                    const collection = [...document.querySelectorAll(selector)]
                        .filter(el => el.tagName === 'SELECT' && _isActivate(el, selfName));
                    collection.forEach(select => {
                        // вернуть элемент к состоянию до активации
                        const box = select.parentElement;
                        const e = new Event('focusout');
                        box.dispatchEvent(e);
                        box.querySelector(`.TUI_${selfName}-input`).remove();
                        _unmarkActivate(select, selfName);
                    });
                    return collection;
                },
            };
            return _is.apply(methods, arguments);
        },

        // /**
        //  * Шаблон плагина TUI
        //  */
        // Plugin() {
        //     const selfName = 'Plugin'; // arguments.callee.name
        //     const defConf = {selector: '.myElement'};
        //     const methods = {
        //         init(useConf = {}) {
        //             const conf = Object.assign(defConf, userConf);
        //             const collection = document.querySelectorAll(conf.selector);
        //             collection.forEach(element => {
        //                 if (_isActivate(element, selfName)) methods.kill(conf.selector);
        //
        //                 element.classList.add(`TUI_${selfName}`);
        //                 console.log('Init TUI.Plugin() on element:', element);
        //
        //                 _markActivate(element, selfName);
        //             });
        //             return collection
        //         },
        //         kill(selector = defConf.selector) {
        //             const collection = document.querySelectorAll(selector);
        //             collection.forEach(element => {
        //                 if (!_isActivate(element, selfName)) return;
        //
        //                 element.classList.remove(`TUI_${selfName}`);
        //                 console.log('Kill TUI.Plugin() on element:', element);
        //
        //                 _unmarkActivate(element, selfName);
        //             });
        //             return collection;
        //         }
        //     };
        //     return _is.apply(methods, arguments);
        // },
    }
})();

/**
 * Автозапуск плагинов TUI
 *
 * Закомментируй или удали вызов
 * неиспользуемых плагинов.
 * Конечно, плагины могут быть вызваны
 * динамически в любом файле с подключенным TUI
 *
 */
document.addEventListener('DOMContentLoaded', () => {
    TUI.GoTo();
    TUI.Popup();
    TUI.Tab();
    TUI.Menu();
    TUI.InputFile();
    TUI.InputRange();
    TUI.InputNumber();
    TUI.SelectSearch();
});
