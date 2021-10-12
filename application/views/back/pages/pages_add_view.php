<h1><?= "{$data['view_title']} [{$data['lang']}]" ?></h1>

<div class="sheath">
    <form method="post" action="/admin/page/add">
        <input type="hidden" name="creation_date" value="<?= date('Y-m-d H:i:s') ?>">
        <input type="hidden" name="last_mod_date" value="<?= date('Y-m-d H:i:s') ?>">
        <input type="hidden" name="lang" value="<?= $data['lang'] ?>">

        <!--
        ########### Основное
        -->

        <div class="touch">
            <h2>Основное</h2>
            <hr>
            <?php
            $this->load->helper('back/id_field');
            id_field('pages');
            ?>
            Заголовок материала <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Должен быть информативным и емким,
                содержать ключевые слова.
                <b class="TUI_red">Обязательно для заполнения!</b>
            </pre>
            <label class="TUI_input">
                <input type="text" name="title" oninput="TUI.Lim(this, 150)">
            </label>
            Описание <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Краткое (до 250 символов) описание этой страницы
                которое будет показано под заголовком (ссылкой)
                в результатах поиска в Интернете (description)
                и на странице родительского раздела. Должно быть
                информативным и емким, содержать ключевые слова.
                <b class="TUI_red">Обязательно для заполнения!</b>
            </pre>
            <label class="TUI_textarea">
                <textarea name="description" class="no-emmet" oninput="TUI.Lim(this, 250)" rows="3"></textarea>
            </label>
            Родительский раздел <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Раздел сайта, в котором будет
                эта страница.
            </pre>
            <?php
            $this->load->helper('back/select_sections_tree');
            new select_section();
            ?>
            Превью-изображение <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Введите в поле ниже ссылку на изображение
                доступное из Интернета или выберите его
                в менеджере файлов. Изображение будет
                использовано как превью на эту страницу
                в соцсетях и в списке материалов раздела.
            </pre>
            <div class="TUI_fieldset">
                <label class="TUI_input">
                    <input type="text" name="img_prev" id="img_prev" value="<?= htmlspecialchars($conf['img_prev_def']) ?>">
                </label>
                <a href="#" class="fas fa-folder-open fa-2x TUI_blue"
                   onclick="files('img_prev');return false"></a>
                <i class="fas fa-eye fa-2x TUI_blue" onmouseover="img_prev(this, '#img_prev')"></i>
                <pre class="TUI_Hint"></pre>
            </div>

            <!--
            ########### Дополнительные настройки
            -->

            <a href="#" onclick="TUI.Toggle('more_basic_opt');return false">Дополнительные настройки&nbsp;
                <i class="fas fa-angle-down"></i>
            </a>
            <div id="more_basic_opt" hidden>
                <div class="TUI_row">
                    <div class="TUI_col-6">
                        Кнопки <q>Share</q>
                        <label class="TUI_select">
                            <select name="addthis_share">
                                <option value="0" <?= !$conf['addthis']['share_def'] ? 'selected' : '' ?>>Скрыть
                                </option>
                                <option value="1" <?= $conf['addthis']['share_def'] ? 'selected' : '' ?>>Показать
                                </option>
                            </select>
                        </label>
                    </div>
                    <div class="TUI_col-6">
                        Кнопки <q>Follow</q>
                        <label class="TUI_select">
                            <select name="addthis_follow">
                                <option value="0" <?= !$conf['addthis']['follow_def'] ? 'selected' : '' ?>>Скрыть
                                </option>
                                <option value="1" <?= $conf['addthis']['follow_def'] ? 'selected' : '' ?>>Показать
                                </option>
                            </select>
                        </label>
                    </div>
                </div>
                Индексация поисковыми роботами
                <label class="TUI_select">
                    <select name="robots">
                        <option value="all">Индексировать без ограничений</option>
                        <option value="noindex">Не показывать материал в результатах поиска</option>
                        <option value="nofollow">Не проходить по ссылкам в материале</option>
                        <option value="noimageindex">Не индексировать изображения в материале</option>
                        <option value="none">Не индексировать полностью</option>
                    </select>
                </label>
                <div class="TUI_row">
                    <div class="TUI_col-6">
                        CSS-код <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            CSS-код с тегами style
                            который будет применен к этой странице.
                            Можно подгружать внешние таблицы стилей.
                        </pre>
                        <label class="TUI_textarea">
                            <textarea name="css" class="emmet-syntax-css"
                                      placeholder="CSS-код с тегами <style> и </style>"
                                      rows="6"></textarea>
                        </label>
                    </div>
                    <div class="TUI_col-6">
                        JavaScript-код <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            JavaScript-код с тегами script
                            который будет применен к этой странице.
                            Можно подгружать внешние скрипты.
                        </pre>
                        <label class="TUI_textarea">
                            <textarea name="js" class="no-emmet"
                                      placeholder="JavaScript-код с тегами <script> и </script>"
                                      rows="6"></textarea>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $this->load->helper('back/content');
        content();
        ?>

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
            <div class="TUI_row">
                <div class="TUI_col-6">
                    <label class="TUI_select">
                        <select name="comments">
                            <option value="on">Разрешить комментировать и отвечать</option>
                            <option value="on_comment">Разрешить только комментировать</option>
                            <option value="off">Запретить комментировать и отвечать</option>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-6">
                    <label class="TUI_select">
                        <select name="public">
                            <option value="1">Опубликовать</option>
                            <option value="0">Не опубликовывать</option>
                        </select>
                    </label>
                </div>
            </div>
        </div>

        <div class="TUI_fieldset this_form_control">
            <button type="button" onclick="subm(form, req)">Добавить страницу</button>
            <a href="/admin/page/get_list" class="TUI_btn-link">Отменить</a>
        </div>
    </form>
</div>

<script>
    // рег.выражения для проверки полей
    const req = {
        title: /[^\s]/,
        description: /[^\s]/
    };

    $(function () {
        // значения полей
        $('select[name="comments"] option[value="<?= $conf['comments']['form'] ?>"]').attr('selected', true);
    });
</script>

<?php $this->load->helper('back/redactor') ?>

