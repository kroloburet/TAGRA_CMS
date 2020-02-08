/**
 * Tagra_UI scripts
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */

const TUI = {

  /**
   * Точка входа для плагинов
   *
   * @param {object} methods Методы плагина
   * @return {object} Метод плагина
   */
  _is: function(methods) {
    if (this[methods]) {
      // метод существует
      return this[methods].apply(this, Array.prototype.slice.call(arguments, 1));

    } else if (typeof methods === 'object' || ! methods) {
      // метод не передан - запустить "init"
      return this.init.apply(this, arguments);

    } else {
      // метод не существует
      return console.error(`Вы о чем?! У TUI нет метода "${methods}"`);
    }
  },

  /**
   * Меню
   *
   * @param {object} user_conf Пользовательская конфигурация
   * @return {object} Набор объектов
   */
  Menu: function(user_conf) {
    const def_conf = {icon: '&#8801;'}, // конфиг по умолчанию
          conf = $.extend({}, def_conf, user_conf);// слияние в глобальный конфиг

    // обработка и возврат набора
    return $('ul.menu').each(function() {

      if ($(this).data('activate')) return;// если уже активировано

      const ul = $(this), // текущий список
            btn = $('<i/>', {class: 'menu_btn', html: conf.icon})// кнопка для мобильной версии
              .on('click.TUI', function() {// показать\скрыть меню
                ul.toggleClass('active');
              }),
            sub_btn = $('<i/>', {class: 'menu_sub_btn', html: conf.icon})// кнопка для вложенных списков
              .on('click.TUI', function() {// показать\скрыть субменю;
                $(this).prev('ul').toggleClass('active');
              });

      ul.before(btn);// добавить кнопку
      ul.find('ul').prev('a,span').addClass('menu_sub_link').end().after(sub_btn);// добавить кнопки субменю
      ul.find('a').each(function() {// выделение ссылок с url совпавшими с url текущей страницы
        const l = window.location,
              h = $(this).attr('href');
        if (l.pathname === h || l.pathname + l.search === h || l.href === h)
          $(this).parents('li').addClass('mark');// пометить пункты
      });

      ul.data('activate', true);// метка активации
    });
  },

  /**
   * Input type="file"
   *
   * @param {object} user_conf Пользовательская конфигурация
   * @return {object} Набор объектов
   */
  InputFile: function(user_conf) {
    const def_conf = {placeholder: '<i class="fas fa-folder-open"/>'}, // конфиг по умолчанию
          conf = $.extend({}, def_conf, user_conf);// слияние в глобальный конфиг

    // обработка и возврат набора
    return $('.file input[type="file"]').each(function() {

      if ($(this).data('activate')) return;// если уже активировано

      const field = $(this), // текущее поле
            info = $('<span/>', {class: 'file_vals', html: conf.placeholder}), // информационный элемент
            inner = function(files) {// отображение информации
              let name = '',
                  size = 0,
                  i = 0;
              if (typeof (files) !== 'undefined' && files.length) {
                // файлы выбраны
                for (i in files) {
                  if (isNaN(i)) continue;
                  name += files.length > 1 ? `<span class="file_val">${files[i].name}</span>` : files[i].name;
                  size += files[i].size;
                }
                size = (size / 1048576).toFixed(3);// общий размер файлов в mb
                info.html(name + ' (' + size + 'mb)');// отобразить список файлов и размер в информационном элементе
              } else {
                // файлы не выбраны
                info.html(conf.placeholder);// сбросить информационный элемент
              }
            };

      field.after(info);// прикрепить к полю информационный элемент
      inner(this.files);// отобразить информацию если файлы уже выбраны
      field.on('change.TUI', function() {// отображать информацию с каждым изменением
        inner(this.files);
      });

      field.data('activate', true);// метка активации
    });
  },

  /**
   * Input type="range"
   *
   * @return {object} Набор объектов
   */
  InputRange: function() {
    // обработка и возврат набора
    return $('.range input[type="range"]').each(function() {

      if ($(this).data('activate')) return;// если уже активировано

      const field = $(this), // текущее поле
            info = $('<span/>', {class: 'range_val', text: field.val() || '0'});// информационный элемент
      field.after(info);// прикрепить к полю информационный элемент
      field.on('change.TUI', function() {// отображать информацию с каждым изменением
        info.text(field.val());
      });

      field.data('activate', true);// метка активации
    });
  },

  /**
   * Переход к элементу
   *
   * @param {string} el Селектор id элемента
   * @return {object} Элемент
   */
  GoTo: function(el) {
    const target = el || window.location.hash;// id элемента или хеш в url
    if (! target) return;// аргумент не передан
    $('body,html').animate({scrollTop: $(target).offset().top}, 800);// анимированый переход
    return $(target);
  },

  /**
   * Адаптивные табы
   *
   * @return {void}
   */
  Tabs: function() {
    $('.tabs dt').on('click.TUI', function() {// клик по вкладке
      return $(this).siblings().removeClass('tab_active').end().next('dd').addBack().addClass('tab_active');
    });
  },

  /**
   * Адаптивные таблицы
   *
   * @return {object}
   */
  Tabl: function() {
    // обработка и возврат набора
    return $('table.tabl').each(function() {

      if ($(this).data('activate')) return;// если уже активировано

      const table = $(this), // текущая таблица
            h_sells = table.find('thead th,thead td');// хедер таблицы

      table.find('tbody tr').each(function() {// добавить title к ячейкам
        $(this).find('th,td').each(function(i) {
          $(this).attr('title', h_sells.eq(i).text());
        });
      });

      table.data('activate', true);// метка активации
    });
  },

  /**
   * Всплывающие окна
   *
   * @param {string} el Cелектор id элемента
   * @return {object} Элемент
   */
  Popup: function(el) {
    // если id не передан
    if (! el || ! $(el)[0]) {

      // обработка и возврат набора
      return $('.popup').each(function() {

        if ($(this).data('activate')) return;// если уже активировано

        const pop = $(this), // текущий элемент окна
              lay = $('<div/>', {class: 'popup_lay'}) // задний фон окна
                .on('click.TUI', function(e) {
                  e.target === this ? $(this).fadeOut() : false;
                }),
              close_btn = $('<span/>', {class: 'popup_close_btn fas fa-times-circle'})
                .on('click.TUI', function() {
                  $(this).closest('.popup_lay').fadeOut();
                });

        pop.prepend(close_btn).wrapAll(lay).css('display', 'inline-block');// построение структуры

        pop.data('activate', true);// метка активации
      });
    }

    // id передан - показать окно
    $(el).parent('.popup_lay').fadeIn();
    return $(el);
  },

  /**
   * Свернуть/развернуть контент
   *
   * @param {string} el Cелектор id элемента с контентом
   * @return {void}
   */
  Toggle: function(el) {
    return $(el).slideToggle(200);
  },

  /**
   * Всплывающая подсказка
   *
   * @param {object} el "this" Ссылка на триггер
   * @param {string} hide_event Cобытие для сокрытия подсказки без префикса "on"
   * @return {object} Элемент подсказки
   */
  Hint: function(el, hide_event) {
    let hint = $(el).next('.hint'), // подсказка
        w = hint.width(),
        h = hint.height(),
        win = $(window),
        hide = function() {// скрытие подсказки
          hint.css({'visibility': 'hidden', 'z-index': '-1000'});
        };

    el.onmousemove = function(e) {// позиционирование подсказки
      let mouse = {x: e.pageX, y: e.pageY}, // положение курсора
          distance = {//дистанция до правого и нижнего края
            right: win.width() - (mouse.x - win.scrollLeft()),
            bottom: win.height() - (mouse.y - win.scrollTop())
          };
      // разместить слева указателя если близко к правому краю
      distance.right < w ? hint.css('left', mouse.x - w - 20 + 'px') : hint.css('left', 20 + mouse.x + 'px');
      // разместить над указателем если близко к нижнему краю
      distance.bottom < h + 30 ? hint.css('top', mouse.y - h - 15 + 'px') : hint.css('top', 15 + mouse.y + 'px');
    };

    hint.css({'visibility': 'visible', 'z-index': '1000'});// показать подсказку

    if (hide_event) {// переданное событие прячет подсказку
      $(document).on(hide_event, hide);
    } else {// прячет по умолчанию
      $(el).on('mouseout.TUI', hide);
    }

    return hint;
  },

  /**
   * Лимит ввода символов в поле
   *
   * @param {object} el "this" Ссылка на поле
   * @param {int} limit Лимит символов
   * @return {object} Элемент счетчика
   */
  Lim: function(el, limit) {
    var field = $(el), // поле
        val = field.val(), // значение поля
        l = field.next('.lim'), // счетчик (уже созданный)
        cut = function() {// обрезать строку, вернуть счетчик
          field.val(field.val().substr(0, parseInt(limit, 10)));
          return l;
        };

    if (! l.length) {// счетчик еще не создан
      var l = $('<span/>', {class: 'lim', text: + limit}), // счетчик
          padd = field.css('padding-right');// отступ в поле (начальный)
      field.after(l)
        .on('blur.TUI', function() {// вставка в DOM и потеря фокуса
          cut().remove();// обрезать строку, удалить счетчик
          field.css('padding-right', padd);// вернуть начальный отступ в поле
        })
        .css('padding-right', l.outerWidth(true));// отступ в поле на длину счетчика
    }

    if (val.length <= parseInt(limit, 10)) {// лимит не превышен
      l.text(parseInt(limit, 10) - val.length);// обновить счетчик
    } else {// лимит превышен
      cut().text('0');// обрезать строку, обнулить счетчик
    }

    return l;
  },

  /**
   * Поиск по выпадающему списку
   *
   * @return {object}
   */
  SelectSearch: function() {
    const def_conf = {// конфиг по умолчанию
          el: 'select.SelectSearch', // набор списков
          placeholder: 'Поиск по списку', // сообщение в поле поиска
          noresult: 'Ничего не найдено'// сообщение об отсутствии результатов поиска
    };

    const methods = {
      /**
       * Активация плагина на элементе/тах списка
       *
       * @param {object} user_conf Пользовательская конфигурация
       * @return {object} Набор списков
       */
      init: function(user_conf) {
        const conf = $.extend({}, def_conf, user_conf);// слияние в глобальный конфиг

        // обработка и возврат набора
        return $(conf.el).each(function() {

          if (! $(this).is('select')) return;// если не список

          if ($(this).data('activate')) {// деактивировать текущий чтобы переписать конфиг
            methods.kill($(this));
          }

          let select = $(this), // текущий список для поиска
              input = $('<input/>', {// поле поска
                type: 'text',
                class: 'SelectSearch_input',
                placeholder: conf.placeholder
              }),
              options = select.find('option'), // все элементы списка
              val, // значение в поле поиска
              result = {}, // результат поиска (отфильтрованные элементы списка)
              search = function() {// поиск в списке
                if (select.is(':disabled')) return;// не искать если список заблокирован
                val = input.val();
                result = options.map(function() {// записать все найденые элементы
                  if (new RegExp(val, 'i').test($(this).text())) return this;
                });
                if (result.length > 0) {// нашло - наполнить список нашедшим
                  select.html($.extend({}, options, result));
                } else {// не нашло - выдать "Не найдено"
                  select.html($('<option/>', {text: conf.noresult, disabled: true}));
                }
              };

          if (select.attr('multiple')) {
            select.closest('label.select')
              .on('focusout.TUI', function() {// вернуть исходный список
                select.html(options);
              });
          }

          input.on('input.TUI', search);// работа поиска
          select.before(input);// прикрепить поле поиска к списку
          select.addClass('SelectSearch').data('activate', true);// метка активации
        });
      },
      /**
       * Деактивация плагина на элементе/тах списка
       *
       * @param {string} el Селектор списка/ков
       * @return {object} Набор списков
       */
      kill: function(el) {
        // обработка и возврат набора
        return $(el || def_conf.el).each(function() {

          if (! $(this).is('select.SelectSearch')) return;// если не список

          $(this).siblings('.SelectSearch_input').val('').keyup().remove();// сбросить поиск, удалить поле
          $(this).data('activate', false);// пометить список как деактивированый
        });
      }
    };

    return this._is.apply(methods, arguments);
  }

  /**
   * Шаблон метода TUI
   */
//    Method: function(prop) {
//      return console.log('Hello from TUI.Method()', this, arguments);
//    },

  /**
   * Шаблон плагина TUI
   */
//    Plugin: function(prop) {
//      const def_conf = {};
//      const methods = {
//        init: function(user_conf){
//          const conf = $.extend({}, def_conf, user_conf);
//          init: function(user_conf){return console.log('Hello from TUI.Plugin()', this, arguments);
//        }
//      };
//      return this._is.apply(methods, arguments);
//    }

};

window.addEventListener('load', function() {
  /**
   * Автозапуск методов TUI
   */
  TUI.Menu();
  TUI.InputFile();
  TUI.InputRange();
  TUI.GoTo();
  TUI.Tabs();
  TUI.Tabl();
  TUI.Popup();
  TUI.SelectSearch();
});
