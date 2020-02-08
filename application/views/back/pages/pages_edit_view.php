<h1><?= "{$data['view_title']} [{$data['lang']}]" ?></h1>

<div class="sheath">
  <form method="POST" action="/admin/page/edit/<?= $data['id'] ?>">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
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
        Должен быть информативным и емким,
        содержать ключевые слова.
        <b class="red">Обязательно для заполнения!</b>
      </pre>
      <label class="input">
        <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" onkeyup="TUI.Lim(this, 150)">
      </label>
      Описание <i class="fas fa-info-circle red" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Краткое (до 250 символов) описание этой страницы
        которое будет показано под заголовком (ссылкой)
        в результатах поиска в Интернете (description)
        и на странице родительского раздела. Должно быть
        информативным и емким, содержать ключевые слова.
        <b class="red">Обязательно для заполнения!</b>
      </pre>
      <label class="textarea">
        <textarea name="description" class="no-emmet" onkeyup="TUI.Lim(this, 250)"
                  rows="3"><?= $data['description'] ?></textarea>
      </label>
      Родительский раздел <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Раздел сайта, в котором будет
        эта страница.
      </pre>
      <?php
      $this->load->helper('back/select_sections_tree');
      new select_section();
      ?>
      Превью-изображение <i class="fas fa-info-circle blue" onmouseover="TUI.Hint(this)"></i>
      <pre class="hint">
        Введите в поле ниже ссылку на изображение
        доступное из Интернета или выберите его
        в менеджере файлов. Изображение будет
        использовано как привью на эту страницу
        в соцсетях и в списке материалов раздела.
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

    <?php
    $this->load->helper('back/versions');
    versions('pages');
    ?>

    <!--
    ########### Доступность
    -->

    <div class="touch">
      <h2>Доступность</h2>
      <hr>
      <div class="row">
        <div class="col6">
          <label class="select">
            <select name="comments">
              <option value="on">Разрешить комментировать и отвечать</option>
              <option value="on_comment">Разрешить только комментировать</option>
              <option value="off">Запретить комментировать и отвечать</option>
            </select>
          </label>
        </div>
        <div class="col6">
          <label class="select">
            <select name="public">
              <option value="1" <?= $data['public'] ? 'selected' : '' ?>>Опубликовать</option>
              <option value="0" <?= !$data['public'] ? 'selected' : '' ?>>Не опубликовывать</option>
            </select>
          </label>
        </div>
      </div>
    </div>

    <div class="button this_form_control">
      <button type="button" onclick="subm(form, req)">Сохранить изменения</button>
      <a href="/admin/page/get_list" class="btn_lnk">Отменить</a>
    </div>
  </form>
</div>

<script>
    // рег.выражения для проверки полей
    const req = {
      title: /[^\s]/,
      description: /[^\s]/
    };

    $(function() {
      // значения полей
      $('select[name="robots"] option[value="<?= $data['robots'] ?>"]').attr('selected', true);
      $('select[name="comments"] option[value="<?= $data['comments'] ?>"]').attr('selected', true);
    });
</script>

<?php $this->load->helper('back/redactor') ?>
