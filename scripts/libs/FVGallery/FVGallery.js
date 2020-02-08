/**
 * FVGallery scripts
 *
 * Плагин галереи пользовательской части
 *
 * @author Sergey Nizhnik <kroloburet@gmail.com>
 */

;(function($) {
  let _item,
      _conf,
      _content_box,
      _noactive;

  /**
   * Активировать окно просмотра
   *
   * Метод создает и активирует окно
   * просмотра изображений и видео.
   *
   * @returns {void}
   */
  const _builder = function() {
    _item = this;// текущий элемент галереи
    _content_box = $('<div/>', {// окно
      class: 'FVG_pop_content_box',
      html: '<div class="FVG_pop_loader"><i class="fas fa-spin fa-spinner"></i></div>'
    });
    let close = $('<div/>', {class: 'FVG_pop_close fas fa-times-circle'}), // кнопка деактивации окна
        prev = $('<div/>', {class: 'FVG_pop_prev fas fa-angle-left'})// кнопка "назад"
          .on('click.FVG', _prev),
        next = $('<div/>', {class: 'FVG_pop_next fas fa-angle-right'})// кнопка "вперед"
          .on('click.FVG', _next),
        pop = $('<div/>', {class: 'FVG_pop', html: [close, next, prev, _content_box]}), // корневой контейнер окна
        back = $('<div/>', {class: 'FVG_pop_back', html: pop});// фоновый контейнер окна
    back.add(close)// деактивация окна по клику
      .on('click.FVG', function(e) {
        if (e.target === this) back.remove();
      });
    // активировать окно
    $('body').append(back);
    // вставить в окно текущий элемент галереи
    _insert();
  };

  /**
   * Поместить в окно текущий элемент галереи
   *
   * @returns {void}
   */
  const _insert = function() {
    // запустить лоадер
    _content_box.html('<div class="FVG_pop_loader"><i class="fas fa-spin fa-spinner"></i></div>');
    const opt = JSON.parse(_item.find('.opt').val());// объект с опциями текущего элемента
    // тип галереи
    switch (_conf.type) {
      // галерея из папки с изображениями
      case 'foto_folder': {
        let img = $('<img/>', {class: 'FVG_pop_img', src: opt.f_url, alt: opt.f_url})
          .on('click.FVG', _next);
        _content_box.html(img);
      }
        break;
      // галерея изображений с описаниями
      case 'foto_desc': {
        let img = $('<img/>', {class: 'FVG_pop_img', src: opt.f_url, alt: opt.f_title})
            .on('click.FVG', _next),
          title = $('<h2/>', {class: 'FVG_pop_desc_title', text: opt.f_title}),
          desc = $('<div/>', {class: 'FVG_pop_desc_desc', text: opt.f_desc}),
          desc_box = opt.f_title || opt.f_desc
            ? $('<div/>', {class: 'FVG_pop_desc', html: [title, desc]})
            : null;
        img.on('load.FVG', function () {
          _content_box.html([img, desc_box]);
          desc_box.outerWidth(img.outerWidth());
        });
      }
        break;
      // галерея youtube
      case 'video_yt': {
        let yt_iframe_opt = '?autoplay=1&controls=2&rel=0&modestbranding=1',
          iframe = $('<iframe/>', {
            class: 'FVG_pop_video',
            src: 'https://www.youtube.com/embed/' + opt.yt_id + yt_iframe_opt,
            allowfullscreen: true
          }),
          pop = _content_box.parent('.FVG_pop');
        pop.css('display', 'block');
        _content_box.html(iframe).addClass('FVG_video_wrap');
        break;
      }
    }
  };

  /**
   * Предыдущий элемент галереи
   *
   * @returns {void}
   */
  const _prev = function() {
    let prev_item = _item.prev('.FVG_item');
    _item = prev_item.length > 0 ? prev_item : $('.FVG_item').last();
    _insert();
  };

  /**
   * Следующий элемент галереи
   *
   * @returns {void}
   */
  const _next = function() {
    let next_item = _item.next('.FVG_item');
    _item = next_item.length > 0 ? next_item : $('.FVG_item').first();
    _insert();
  };

  /**
   * Конфигурация галереи по умолчанию
   *
   * @type object
   */
  const def_conf = {
    type: 'foto_folder'
  };

  /**
   * Публичные методы плагина галереи
   *
   * @type object
   */
  const methods = {
    /**
     * Инициализация плагина
     *
     * @param {object} user_conf Пользовательская конфигурация
     * @returns {object}
     */
    init: function(user_conf) {
      $('head').eq(0).append('<link href="/scripts/libs/FVGallery/FVGallery.css" rel="stylesheet">');// загрузить стили
      _conf = $.extend({}, def_conf, user_conf);// получить конфигурацию

      let items = $('.FVG_item');// все элементы галереи
      items.find('img').each(function() {// отложеная загрузка изображений
        $(this).attr('src', $(this).data('src'));
      });
      //располагаются ли элементы в один столбец,
      //чтобы не отрабатывать всплывающее окно.
      //Добравить/убрать стиль деактивации.
      _noactive = function() {
        if ($(window).outerWidth(true) <= 600) {// ширина окна <= контрольной точки
          items.addClass('FVG_noactive');
          return true;
        } else {
          items.removeClass('FVG_noactive');
          return false;
        }
      };
      _noactive();
      $(window).on('resize.FVG', _noactive);// следить за шириной окна
      return this.each(function() {// добавить события на все элементы
        $(this).on('click.FVG', function(e) {// клик запускает окно
          if (_noactive() && _conf.type !== 'video_yt') return false;// элементы расположены в одну колонку
          e.preventDefault();
          _builder.apply($(this), null);
        });
      });
    }
  };

  /**
   * Точка входа плагина
   *
   * @param {object} method Вызываемый метод
   * @returns {object}
   */
  $.fn.FVGallery = function(method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || ! method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Метод с именем ' + method + ' не существует');
    }
  };
})(jQuery);
