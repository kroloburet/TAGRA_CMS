<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('versions')) {

    /**
     * Выбор языковых версий материала
     *
     * Выводит модуль выбора версий материалов
     * на которые система будет перенаправлять
     * пользователя если тот изменит язык текущего
     * материала.
     *
     * @param string $material Имя таблицы материалов в БД
     * @return void
     */
    function versions(string $material)
    {
        $CI = &get_instance();

        // если в системе один язык
        if (count($CI->app('conf.langs')) < 2) {
            echo '<input type="text" name="versions" value="" hidden>';
            return;
        }

        $data = $CI->app('data');
        $m = $CI->db->where('lang !=', $data['lang'])
            ->select('title,id,lang')
            ->order_by('title', 'ASC')
            ->get($material)->result_array();

        // формировать объект данных материалов языков системы
        foreach ($m as $k => $v) {
            switch ($material) {
                case 'pages':$url = '/page/' . $v['id'];
                    break;
                case 'sections':$url = '/section/' . $v['id'];
                    break;
                case 'gallerys':$url = '/gallery/' . $v['id'];
                    break;
            }
            $m[$v['lang']][$v['id']] = ['id' => $v['id'], 'title' => $v['title'], 'url' => $url];
            unset($m[$k]);
        }
        $m = json_encode($m, JSON_FORCE_OBJECT);
        ?>

<!--
########### Версии материала
-->

<div class="touch" id="versions">
  <h2 class="float_l">Версии материала</h2> <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
  <pre class="hint">
    Здесь вы можете выбрать версии этого
    материала на других языках ресурса.
    Когда пользователь изменит язык ресурса,
    находясь на странице этого материала,
    он будет перенаправлен на выбранный вами
    материал.
  </pre>
  <hr>
  <button type="button" class="v_add_btn">Добавить версии</button>
  <!-- блок выбора версий -->
  <div class="v_box">
    <label class="select">
      <select class="v_lang">
        <?php
        foreach ($CI->app('conf.langs') as $v) {
        if ($v['tag'] !== $data['lang']) { ?>
        <option value="<?= $v['tag'] ?>"><?= "{$v['title']} [{$v['tag']}]" ?></option>
        <?php }
        } ?>
      </select>
    </label>
    <label class="select v_viewer"></label>
    <div class="button algn_r">
      <button type="button" class="v_done_btn">Готово</button>
    </div>
  </div>
  <!-- поле отправки данных -->
  <textarea class="v_opt no-emmet"
            name="versions" hidden><?= isset($data['versions']) ? $data['versions'] : '' ?></textarea>
  <!-- превью выбранных версий -->
  <div class="v_prev"></div>
</div>

<script>
    /**
     * Модуль выбора и управления
     *
     * @returns {void}
     */
    ;(function($) {
      let _ = $('#versions'), // контейнер модуля
          _add_btn = _.find('.v_add_btn'), // кнопка "Добавить версии"
          _del_all_btn = $('<button/>', {type: 'button', text: 'Удалить все'}),// кнопка "Удалить все"
          _box = _.find('.v_box'), // контейнер полей
          _lang = _.find('.v_lang'), // список языков
          _viewer = _.find('.v_viewer'), // label списка материалов языка
          _prev = _.find('.v_prev'), // превью версий
          _v_opt = _.find('.v_opt'), // поле для отправки
          _opt = ! _v_opt.val() ? {} : JSON.parse(_v_opt.val()), // объект версий материала
          _m =<?= $m ?>;// объект материалов языков

      /**
       * Скрыть, очистить форму
       *
       * @returns {void}
       */
      const __clear = function() {
            _box.slideUp(200);
            TUI.GoTo('#versions');
            _lang.attr('disabled', false);
          };

      /**
       * Открыть форму добавления
       *
       * @returns {void}
       */
      const __add_form = function() {
            __clear();
            _lang.change();// показать список материалов языка
            _box.slideDown(200);
            TUI.GoTo('#versions');
         };

      /**
       * Открыть форму редактирования
       *
       * @param {string} tag Тег языка
       * @returns {void}
       */
      const __edit_form = function(tag) {
            _lang.val(tag).attr('disabled', true);// установить язык и заблокировать список
            _lang.change();// показать список материалов языка
            _box.slideDown(200);
            TUI.GoTo('#versions');
          };

      /**
       * Добавить/редактировать версию
       *
       * @returns {void}
       */
      const __set = function() {
            let l = _lang.val(), // выбранный язык
                m = _viewer.find('select').val();// выбранный материал
            if (! _m[l] || ! m) return;
            _opt[l] = _m[l][m];// записать или перезаписать версию языка
            _v_opt.val(JSON.stringify(_opt));// обновить поле отправки
            __show();
          };

      /**
       * Удалить версию
       *
       * @param {string} tag Тег языка
       * @returns {void}
       */
      const __del = function(tag) {
            if (! _opt[tag] || ! confirm('Эта версия будет удалена!\nВыполнить действие?')) return;
            __clear();
            delete _opt[tag];
            if ($.isEmptyObject(_opt)) {
              _prev.empty();
              _v_opt.val('');
              _del_all_btn.detach();
            } else {
              _v_opt.val(JSON.stringify(_opt));
              __show();
            }
          };

      /**
       * Удалить все версии
       *
       * @returns {void}
       */
      const __del_all = function() {
            if (! confirm('Все версии будут удалены!\nВыполнить действие?')) return;
            __clear();
            _opt = {};
            _prev.empty();
            _v_opt.val('');
            _del_all_btn.detach();
          };


      /**
       * Отобразить превью
       *
       * @returns {void}
       */
      const __show = function() {
            // если нет данных
            if ($.isEmptyObject(_opt)) return;
            _add_btn.after(_del_all_btn).on('click.Versions', __del_all);// добавить кнопку "удалить все"
            _prev.empty();// очистить превью
            for (let i in _opt) {// заполнять превью
              let str = '<span class="v_prev_lang">' + i + '</span><span>' + _opt[i].title.substring(0, 100) + '</span>',
                  edit_btn = $('<div/>', {class: 'v_prev_edit_btn fas fa-edit', title: 'Редактировать'}).data('tag', i),
                  del_btn = $('<div/>', {class: 'v_prev_del_btn fas fa-trash-alt', title: 'Удалить'}).data('tag', i),
                  prev_str_box = $('<div/>', {class: 'v_prev_str_box', html: str}),
                  control_box = $('<div/>', {class: 'v_prev_control', html: [edit_btn, del_btn]}),
                  prev_item = $('<div/>', {class: 'v_prev_item', html: [prev_str_box, control_box]});
              edit_btn.on('click.Versions', function() {
                __edit_form($(this).data('tag'));
                __edit_form($(this).data('tag'));
                TUI.GoTo('#versions');
              });
              del_btn.on('click.Versions', function() {
                __del($(this).data('tag'));
              });
              _prev.append(prev_item);
            }
          };

      // открыть форму добавления
      _add_btn.on('click.Versions', __add_form);

      // скрыть, очистить форму
      _.find('.v_done_btn').on('click.Versions', __clear);

      // заполнить список материалами выбранного языка
      _lang.on('change.Versions', function() {
        let s = $('<select/>', {class: 'SelectSearch', size: 5, html: '<option disabled>Нет материалов</option>'}),
            m = _m[$(this).val()],
            o = '';
        if (m) {
          for (let i in m) {
            o += '<option value="' + i + '">' + m[i].title + '</option>';
          }
          s.html(o);
        }
        _viewer.html(s);
        TUI.SelectSearch({el: s})
          .on('change.Versions', __set);
      });

      // отобразить превью
      __show();

    }(jQuery));
</script>

    <?php
    }
}
