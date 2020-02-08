<h1><?= "{$data['view_title']} [{$data['lang']}]" ?></h1>

<div class="sheath">
  <form method="POST" action="/admin/contact/edit/<?= $data['id'] ?>">
    <input type="hidden" name="last_mod_date" value="<?= date('Y-m-d') ?>">
    <input type="hidden" name="lang" value="<?= $data['lang'] ?>">

    <!--
    ########### Основное
    -->

    <div class="touch">
      <h2>Основное</h2>
      <hr>
      Заголовок страницы <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Должен быть информативным и емким.
        <b class="red">Обязательно для заполнения!</b>
      </pre>
      <label class="input">
        <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" onkeyup="TUI.Lim(this, 150)">
      </label>
      Описание <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Краткое (до 250 символов) описание этой страницы
        которое будет показано под заголовком (ссылкой)
        в результатах поиска в Интернете (description).
        Должно быть информативным и емким, содержать
        ключевые слова.
        <b class="red">Обязательно для заполнения!</b>
      </pre>
      <label class="textarea">
        <textarea name="description" class="no-emmet" onkeyup="TUI.Lim(this, 250)"
                  rows="3"><?= $data['description'] ?></textarea>
      </label>
      Превью-изображение <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Введите в поле ниже ссылку на изображение
        доступное из Интернета или выберите его
        в менеджере файлов. Изображение будет
        использовано как привью на эту страницу
        в соцсетях.
      </pre>
      <label class="input inline width90">
        <input type="text" name="img_prev" id="img_prev" value="<?= htmlspecialchars($data['img_prev']) ?>">
      </label>
      <a href="#" class="fas fa-folder-open fa-lg blue"
         onclick="files('img_prev', '<?= $data['lang'] ?>');return false"></a>&nbsp;
         <i class="fas fa-eye fa-lg blue" onmouseover="img_prev(this, '#img_prev')"></i>
      <pre class="hint"></pre>

      <!--
      ########### Дополнительные настройки
      -->

      <a href="#" onclick="TUI.Toggle('#more_basic_opt');return false">Дополнительные настройки&nbsp;
        <i class="fas fa-angle-down"></i>
      </a>
      <div id="more_basic_opt" hidden>
        <div class="row">
          <div class="col6">
            Кнопки <q>Share</q>
            <label class="select">
              <select name="addthis_share">
                <option value="0" <?= !$data['addthis_share'] ? 'selected' : '' ?>>Скрыть</option>
                <option value="1" <?= $data['addthis_share'] ? 'selected' : '' ?>>Показать</option>
              </select>
            </label>
          </div>
          <div class="col6">
            Кнопки <q>Follow</q>
            <label class="select">
              <select name="addthis_follow">
                <option value="0" <?= !$data['addthis_follow'] ? 'selected' : '' ?>>Скрыть</option>
                <option value="1" <?= $data['addthis_follow'] ? 'selected' : '' ?>>Показать</option>
              </select>
            </label>
          </div>
        </div>
        Индексация поисковыми роботами
        <label class="select">
          <select name="robots">
            <option value="all">Индексировать без ограничений</option>
            <option value="noindex">Не показывать материал в результатах поиска</option>
            <option value="nofollow">Не проходить по ссылкам в материале</option>
            <option value="noimageindex">Не индексировать изображения в материале</option>
            <option value="none">Не индексировать полностью</option>
          </select>
        </label>
        <div class="row">
          <div class="col6">
            CSS-код <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              CSS-код с тегами style
              который будет применен к этой странице.
              Можно подгружать внешние таблицы стилей.
            </pre>
            <label class="textarea">
              <textarea name="css" class="emmet-syntax-css"
                        placeholder="CSS-код с тегами <style> и </style>"
                        rows="6"><?= $data['css'] ?></textarea>
            </label>
          </div>
          <div class="col6">
            JavaScript-код <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              JavaScript-код с тегами script
              который будет применен к этой странице.
              Можно подгружать внешние скрипты.
            </pre>
            <label class="textarea">
              <textarea name="js" class="no-emmet"
                        placeholder="JavaScript-код с тегами <script> и </script>"
                        rows="6"><?= $data['js'] ?></textarea>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!--
    ########### Контент
    -->

    <div class="touch">
      <h2>Контент</h2>
      <hr>
      Ширина левой колонки макета&nbsp;
      <input type="text" name="layout_l_width" class="layout_l_width_input"
             value="<?= htmlspecialchars($data['layout_l_width']) ?>" size="3"
             onkeyup="$('#layout_l').css('width', ($(this).val() - 2) + '%')">&nbsp;%&nbsp;&nbsp;
      <a href="#"
         onclick="$('#layout_t,#layout_l,#layout_r,#layout_b').removeClass('nav_layout_active');return false">
        Kомпактно <i class="fas fa-compress"></i>
      </a>&nbsp;&nbsp;
      <a href="#" onclick="TUI.Toggle('#o_makete');return false">
        О макете&nbsp;<i class="fas fa-angle-down"></i>
      </a>
      <div id="o_makete" hidden>
        Чтобы основная часть страницы проще воспринималась визуально и была адаптивной, она представлена в виде макета. Сам макет разделен на 4 сегмента (колонки). Вы можете заполнять один и более этих сегментов своим контентом (содержимым). Чтобы разместить или редактировать контент в одном из сегментов, выберите его, кликнув по нему мышкой. Пустой сегмент, без контента, не будет отображаться на странице. Вы можете задать ширину левой колонки в процентном отношении к общей ширине шаблона. Значение ширины шаблона и ширина левой колонки по умолчанию для всех вновь создаваемых материалов устанавливается в настройках <q>Макет и редактор</q> (в главном меню: Конфигурация). Чтобы вернуть макет к <q>компактному</q> виду, нажмите на <q>Компактно</q> в верхней части этого блока.
      </div>
      <div style="margin-top:.5em">
        <div id="layout_t" class="nav_layout_t"><?= $data['layout_t'] ?></div>
        <div id="layout_l" class="nav_layout_l" style="width:<?= $data['layout_l_width'] ?>%"><?= $data['layout_l'] ?></div>
        <div id="layout_r" class="nav_layout_r"><?= $data['layout_r'] ?></div>
        <div id="layout_b" class="nav_layout_b"><?= $data['layout_b'] ?></div>
      </div>
    </div>

    <!--
    ########### Контакты
    -->

    <div class="touch" id="contact">
      <h2 class="float_l">Контакты</h2> <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Здесь вы можете указать один или
        несколько контактов, а также
        редактировать их.
      </pre>
      <hr>
      <button type="button" class="add_contact_btn">Добавить контакт</button>
      <!-- форма добавления/редактирования -->
      <div class="contact_form" hidden>
        <?php if (!$conf['gapi_key']) { ?>
        <p class="notific_y mini full">Внимание! Если вы хотите использовать GPS-координаты и карты Google, необходимо иметь аккаунт в Google, вкрючить Google Map API, получить ключ и добавить его в конфигурацию (в главном меню: Конфигурация, в поле <q>Ключ Google Map API</q>). Если у вас возникли трудности связанные с получением ключа или отображением карт, обратитесь к разработчику или веб-мастеру.</p>
        <?php } ?>
        <div class="row">
          <div class="col6">
            Порядок <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              Порядковый номер этого контакта
              в списке контактов.
              <b class="red">Обязательно для заполнения!</b>
            </pre>
            <label class="input">
              <input type="number" class="order" min="1">
            </label>
          </div>
          <div class="col6">
            Заголовок <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              Заголовок для этого контакта.
              Пример: Отдел поддержки котиков
            </pre>
            <label class="input">
              <input type="text" class="title" placeholder="Отдел поддержки котиков">
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col6">
            E-mail <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              Если у контакта несколько почтовых ящиков,
              укажите их по одному через запятую.
              <b class="red">Обязательно для заполнения!</b>
            </pre>
            <label class="input">
              <input type="text" class="mail" placeholder="info@domen.com, sale@domen.com">
            </label>
          </div>
          <div class="col6">
            Телефон <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="hint">
              Если у контакта несколько номеров,
              укажите их по одному через запятую.
              Внимание! Указывайте номера с кодом станы.
              Пример: +38(093)1234567, +38 044 123-45-67
            </pre>
            <label class="input">
              <input type="text" class="tel" placeholder="+38(095)1112233, +38 063 123 4567">
            </label>
          </div>
        </div>
        Skype
        <label class="input">
          <input type="text" class="skype">
        </label>
        Viber
        <label class="input">
          <input type="text" class="viber">
        </label>
        Telegram
        <label class="input">
          <input type="text" class="telegram">
        </label>
        Адрес <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
        <pre class="hint">
          Физический адрес контакта
        </pre>
        <label class="input">
          <input class="address" type="text" placeholder="Крещатик, 20/22, Киев">
        </label>
        GPS-координаты <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
        <pre class="hint">
          GPS-координаты (широта,долгота).
          Координаты можно получить перетащив
          маркер на карте в нужное место, или
          воспользоваться полем <q>Поиск координат</q>.
          Начните вводить в поле поиска адрес чтобы
          вызвать варианты, выберите нужный вариант,
          координаты и маркер будут установлены.
        </pre>
        <label class="input">
          <input class="gps" type="text" placeholder="50.450031,30.524205">
        </label>
        <input class="gps_search" type="text" placeholder="Поиск координат">
        <div class="contact_gmap"></div>
        <input class="marker_desc" type="text" placeholder="Текст для маркера (можно использовать HTML)">
        <div class="button algn_r">
          <button type="button" class="contact_done_btn">Готово</button>
          <button type="button" class="contact_cancel_btn">Отмена</button>
        </div>
      </div>
      <textarea name="contacts" class="no-emmet" hidden><?= $data['contacts'] ?></textarea>
      <!-- превью для контактов -->
      <div class="contact_prev"></div>
    </div>

    <!--
    ########### Форма обратной связи
    -->

    <div class="touch">
      <h2>Форма обратной связи</h2>
      <hr>
      <label class="select">
        <select name="contact_form">
          <option value="1" <?= $data['contact_form'] ? 'selected' : '' ?>>Показать форму обратной связи</option>
          <option value="0" <?= !$data['contact_form'] ? 'selected' : '' ?>>Скрыть форму обратной связи</option>
        </select>
      </label>
    </div>

    <div class="button this_form_control">
      <button type="button" onclick="subm(form, req)">Сохранить изменения</button>
      <a href="/admin/contact/get_list" class="btn_lnk">Отменить</a>
    </div>
  </form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?language=<?= $data['lang'] ?>&key=<?= $conf['gapi_key'] ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
<script>
    // загрузить стили jquery-ui
    $('head').eq(0).append('<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" rel="stylesheet">');

    // рег.выражения для проверки полей
    const req = {
      title: /[^\s]/,
      description: /[^\s]/
    };

    // значения полей
    $(function() {
      $('select[name="robots"] option[value="<?= $data['robots'] ?>"]').attr('selected', true);
    });

    /**
     * Модуль управления контактами
     */
    ;(function($, google) {
      let _ = $('#contact'), // блок контакта
          _order = _.find('.order'), // поле "порядок"
          _title = _.find('.title'), // поле "заголовок"
          _mail = _.find('.mail'), // поле "email"
          _tel = _.find('.tel'), // поле "телефон"
          _skype = _.find('.skype'), // поле "skype"
          _viber = _.find('.viber'), // поле "viber"
          _telegram = _.find('.telegram'), // поле "telegram"
          _address = _.find('.address'), // поле "адрес"
          _gps = _.find('.gps'), // поле "gps"
          _marker_desc = _.find('.marker_desc'), // поле "текст для маркера"
          _contacts = _.find('textarea[name=contacts]'), // поле объекта контактов
          _form = _.find('.contact_form'), // форма добавить\редактировать контакт
          _gmap = _.find('.contact_gmap'), // блок карты
          _prev = _.find('.contact_prev'), // блок превью
          _add_btn = _.find('.add_contact_btn'), // кнопка "добавить контакт"
          _del_all_btn = $('<button/>', {type: 'button', text: 'Удалить все'})// кнопка "удалить все"
            .on('click.Contact', function() {
              __del_all();
            }),
          _done_btn = _.find('.contact_done_btn'), // кнопка "готово"
          _cancel_btn = _.find('.contact_cancel_btn'), // кнопка "отмена"
          _gps_reg = /(^-?)[0-9]+(?:\.[0-9]*)?,-?[0-9]+(?:\.[0-9]*)?$/, // регулярка проверки gps
          _opt = ! _contacts.val() ? {} : JSON.parse(_contacts.val());// объект данных контактов

      /**
       * Валидация полей
       *
       * @param {string} id Идентификатор контакта (порядковый номер)
       * @returns {boolean}
       */
      const __validator = function(id) {
        let opt_count = Object.keys(_opt).length;// всего контактов

        // валидация поля "Порядок"
        if (! /^[1-9]\d*$/.test(_order.val())) {
          alert('Поле "Порядок" не заполнено или заполнено некорректно!\nТолько целое число больше нуля!');
          return false;
        }
        if (typeof id !== "undefined" && parseInt(_order.val(), 10) > opt_count) {
          alert('Поле "Порядок" заполнено некорректно!\nТолько целое число в пределах 1-' + opt_count + '!');
          return false;
        }

        // валидация поля "E-mail"
        if (! /\S/.test(_mail.val())) {// если пусто
          alert('Поле "E-mail" не заполнено!');
          return false;
        } else {// если поле заполнено
          let mail_arr = _mail.val().split(',');// разбиваю стоку в массив email-ов
          for (let i = 0; i < mail_arr.length; i ++) {// проход по массиву email-ов
            if (! /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(mail_arr[i].trim())) {// проверка
              alert('Поле "E-mail" заполнено некорректно!');
              return false;
            }
          }
        }

        // валидация поля "Tелефон"
        if (/\S/.test(_tel.val())) {// поле заполнено
          let tel_arr = _tel.val().split(',');// разбиваю стоку в массив телефонов
          for (let i = 0; i < tel_arr.length; i ++) {// проход по массиву телефонов
            if (! /^\+\d{2,3}[\s-]?\(?\d{0,3}\)?[\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}$/.test(tel_arr[i].trim())) {// проверка
              alert('Поле "Телефон" заполнено некорректно!');
              return false;
            }
          }
        }

        // валидация поля "Адрес", "GPS-координаты"
        if (/\S/.test(_address.val())) {// поле "Адрес" заполнено - нужно заполнить координаты
          if (! _gps_reg.test(_gps.val())) {// GPS-координаты некорректны
            alert('Поле "GPS-координаты" не заполнено или заполнено некорректно!\nЕсли заполнено поле "Адрес", то поле "GPS-координаты" обязательно для заполнения.');
            return false;
          } else {// GPS-координаты корректны - проверка на уникальность в контактах
            for (let k in _opt) {// обход контактов
              if (_opt[k].gps === _gps.val() && k !== id) {
                alert('Контакт с такими координатами уже добавлен!');
                return false;
              }
            }
          }
        }
        if (/\S/.test(_gps.val())) {// поле "GPS-координаты" заполнено - нужно заполнить адрес
          if (! /\S/.test(_address.val())) {
            alert('Поле "Адрес" не заполнено!\nЕсли заполнено поле "GPS-координаты", то поле "Адрес" обязательно для заполнения.');
            return false;
          }
        }

        // валидация пройдена успешно
        return true;
      };

      /**
       * Открыть форму добавления
       *
       * @returns {void}
       */
      const __get_add_form = function() {
        __clear();
        let order = Object.keys(_opt).length + 1;
        _done_btn.on('click.Contact', __add);// событие на "готово" - добавить
        _order.val(order).attr({'readonly': true, 'max': order});// задать порядковый номер и заблокировать поле
        _form.slideDown(200);
        __map_init();// активация Google Map
      };

      /**
       * Открыть форму редактирования
       *
       * @param {string} id Идентификатор контакта
       * @returns {void}
       */
      const __get_edit_form = function(id) {
        __clear();
        _done_btn.on('click.Contact', function() {// событие на "готово" - редактировать
          __edit(id);
        });
        _order.val(id).attr({'readonly': false, 'max': Object.keys(_opt).length});
        _title.val(_opt[id].title);
        _mail.val(_opt[id].mail);
        _tel.val(_opt[id].tel);
        _skype.val(_opt[id].skype);
        _viber.val(_opt[id].viber);
        _telegram.val(_opt[id].telegram);
        _address.val(_opt[id].address);
        _gps.val(_opt[id].gps);
        _marker_desc.val(_opt[id].marker_desc);
        _form.slideDown(200);
        __map_init();// активация Google Map
      };

      /**
       * Скрыть, очистить блок полей
       *
       * @returns {void}
       */
      const __clear = function() {
        _done_btn.off();// удалить все события у кнопки "готово"
        _gmap.empty();// очистить карту
        _form.slideUp(200).find('input,textarea').val('');// очистить поля, скрыть форму
      };

      /**
       * Добавить контакт
       *
       * @returns {void}
       */
      const __add = function() {

        // валидация полей
        if (! __validator())return;

        _opt[_order.val()] = {
            title: _title.val(),
            mail: _mail.val(),
            tel: _tel.val(),
            skype: _skype.val(),
            viber: _viber.val(),
            telegram: _telegram.val(),
            gps: _gps.val(),
            marker_desc: _marker_desc.val(),
            address: _address.val()
        };

        if (! $.isEmptyObject(_opt)) {
          _contacts.val(JSON.stringify(_opt));
          __clear();
          __show();
          TUI.GoTo('#contact');
        }
      };

      /**
       * Редактировать контакт
       *
       * @param {string} id Идентификатор контакта
       * @returns {void}
       */
      const __edit = function(id) {

        // валидация полей
        if (! __validator(id)) return;

        let order = _order.val(), // новый порядковый номер
            int_order = parseInt(order, 10), // новый порядковый номер в число
            int_id = parseInt(id, 10), // текущий порядковый номер в число
            temp = {};// если изменится порядковый номер - будет хранить объект с новым порядком

        // проверка числа в диапазоне
        Number.prototype.between = function(a, b) {
          let min = Math.min(a, b), max = Math.max(a, b);
          return this >= min && this <= max;
        };

        // если порядковый номер изменился
        if (order !== id) {

          for (let k in _opt) {
            let int_k = parseInt(k, 10);// текущий порядковый номер (ключ) в число
            if (int_k.between(int_id, int_order)) {// текущий ключ входит в диапазон
              if (int_k === int_id) {// текущий ключ и текущий порядковый номер совпали
                temp[order] = {// записать измененный элемент и назначить новый порядковый номер
                    title: _title.val(),
                    mail: _mail.val(),
                    tel: _tel.val(),
                    skype: _skype.val(),
                    viber: _viber.val(),
                    telegram: _telegram.val(),
                    gps: _gps.val(),
                    marker_desc: _marker_desc.val(),
                    address: _address.val()
                };
              } else {// не совпали - увеличить или уменьшить номер в диапазоне чтобы сдвинуть
                int_id > int_order ? temp[int_k + 1] = _opt[k] : temp[int_k - 1] = _opt[k];
              }
            } else {//текущий ключ не входит в диапазон - оставить как есть
              temp[k] = _opt[k];
            }
          }

          _opt = temp;
          if (! $.isEmptyObject(_opt)) {
            _contacts.val(JSON.stringify(_opt));
            __clear();
            __show();
            TUI.GoTo('#contact');
          }
          return true;
        }

        //порядковый номер не изменился
        _opt[id] = {
            title: _title.val(),
            mail: _mail.val(),
            tel: _tel.val(),
            skype: _skype.val(),
            viber: _viber.val(),
            telegram: _telegram.val(),
            gps: _gps.val(),
            marker_desc: _marker_desc.val(),
            address: _address.val()
        };

        if (! $.isEmptyObject(_opt)) {
          _contacts.val(JSON.stringify(_opt));
          __clear();
          __show();
          TUI.GoTo('#contact');
        }
      };

      /**
       * Удалить контакт
       *
       * @param {string} id Идентификатор контакта
       * @returns {void}
       */
      const __del = function(id) {
        let int_id = parseInt(id, 10);

        for (let k in _opt) {
          let int_k = parseInt(k, 10);
          int_k > int_id ? _opt[int_k - 1] = _opt[k] : null;
          int_k === Object.keys(_opt).length ? delete _opt[k] : null;
        }

        if ($.isEmptyObject(_opt)) {
          _prev.empty();
          _contacts.val('');
          _del_all_btn.remove();
        } else {
          _contacts.val(JSON.stringify(_opt));
          __show();
          TUI.GoTo('#contact');
        }
      };

      /**
       * Удалить все контакты
       *
       * @returns {void}
       */
      const __del_all = function() {
        if (! confirm('Все контакты будут удалены!\nВыполнить действие?')) return;
        _opt = {};
        _prev.empty();
        _contacts.val('');
        __clear();
        _del_all_btn.remove();
      };

      /**
       * Отобразить превью
       *
       * @returns {void}
       */
      const __show = function() {

        // если нет данных
        if ($.isEmptyObject(_opt)) return;

        _add_btn.after(_del_all_btn);// добавить кнопку "удалить все"
        _prev.empty();// очистить превью
        for (let k in _opt) {// заполнять превью адресами
          let str = (`${k}. ${_opt[k].title} ${_opt[k].mail} ${_opt[k].tel} ${_opt[k].address}`).substring(0, 100) + '...',
              edit_btn = $('<div/>', {class: 'contact_prev_edit_btn fas fa-edit', title: 'Редактировать'}).data('id', k),
              del_btn = $('<div/>', {class: 'contact_prev_del_btn fas fa-trash-alt', title: 'Удалить'}).data('id', k),
              prev_str_box = $('<div/>', {class: 'contact_prev_str_box', text: str}),
              control_box = $('<div/>', {class: 'contact_prev_control', html: [edit_btn, del_btn]}),
              prev_item = $('<div/>', {class: 'contact_prev_item', html: [prev_str_box, control_box]});
          edit_btn.on('click.Contact', function() {
            __get_edit_form($(this).data('id'));
            TUI.GoTo('#contact');
          });
          del_btn.on('click.Contact', function() {
            if (confirm('Этот контакт будет удален!\nВыполнить действие?'))
              __del($(this).data('id'));
          });
          _prev.append(prev_item);
        }
      };

      /**
       * Активация Google Map
       *
       * @returns {void}
       */
      const __map_init = function() {
        let latlng = _gps_reg.test(_gps.val())
              ? new google.maps.LatLng(_gps.val().split(',')[0], _gps.val().split(',')[1])
              : new google.maps.LatLng(50.450209, 30.522536899999977),
            mapOptions = {
              zoom: 6,
              scrollwheel: false,
              center: latlng,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
            },
            map = new google.maps.Map(_gmap[0], mapOptions),
            geocoder = new google.maps.Geocoder(),
            marker = new google.maps.Marker({
              map: map,
              draggable: true,
              position: latlng,
              title: "Для получения координат передвиньте маркер в нужное место"
            });

        // поиск координат на карте
        _.find('.gps_search').autocomplete({
          source: function(request, response) {
            geocoder.geocode({'address': request.term}, function(results, status) {
              response($.map(results, function(item) {
                return {
                  label: item.formatted_address,
                  value: item.formatted_address,
                  latitude: item.geometry.location.lat(),
                  longitude: item.geometry.location.lng()
                };
              }));
            });
          },
          select: function(event, ui) {
            _gps.val(ui.item.latitude + ',' + ui.item.longitude);
            let location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
            marker.setPosition(location);
            map.setCenter(location);
          },
          delay: 500
        });

        // установить маркер по введенным координатам
        _gps.on('keyup.Contact', function() {
          if (_gps_reg.test($(this).val())) {// если соответствует шаблону gps-координат
            let location = new google.maps.LatLng($(this).val().split(',')[0], $(this).val().split(',')[1]);
            marker.setPosition(location);
            map.setCenter(location);
          }
        });

        // перетаскивание маркера
        google.maps.event.addListener(marker, 'drag', function() {
          geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                _gps.val(marker.getPosition().lat() + ',' + marker.getPosition().lng());
              }
            }
          });
        });
      };

      // открыть форму добавления контакта
      _add_btn.on('click.Contact', __get_add_form);

      // скрыть, очистить форму
      _cancel_btn.on('click.Contact', function() {
        __clear();
        TUI.GoTo('#contact');
      });

      // отобразить превью
      __show();

    }(jQuery, google));
</script>

<?php $this->load->helper('back/redactor') ?>
