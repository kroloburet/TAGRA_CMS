<h1><?= $data['view_title'] ?></h1>

<dl class="TUI_Tab">

    <!--
    ########### Основные настройки системы
    -->

    <dt>Основные настройки</dt>
    <dd>
        <form method="post" action="/admin/setting/set_config">
            <div class="TUI_row">
                <div class="TUI_col-6">

                    <!--
                    ########### Общее
                    -->

                    <div class="touch">
                        <h2>Общее</h2>
                        <hr>
                        Доступ к сайту <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Управление доступом к пользовательской
                            части вашего сайта. Это полезно, например,
                            когда вы ведете технические работы на сайте,
                            административную часть изменения не затрагивают.
                            Если установлено <q>Доступ к сайту закрыт</q> &mdash;
                            только администратору и модераторам будет
                            открыт доступ к пользовательской части сайта.
                        </pre>
                        <label class="TUI_select">
                            <select name="site_access">
                                <option value="1" <?= $conf['site_access'] ? 'selected' : '' ?>>Доступ к сайту открыт
                                </option>
                                <option value="0" <?= !$conf['site_access'] ? 'selected' : '' ?>>Доступ к сайту закрыт
                                </option>
                            </select>
                        </label>
                        Имя сайта
                        <label class="TUI_input">
                            <input type="text" name="site_name" value="<?= htmlspecialchars($conf['site_name']) ?>">
                        </label>
                        E-mail с сайта <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            E-mail на который будут приходить
                            письма от пользователей вашего сайта.
                            Например: со страницы <q>Контакты</q>.
                            <b class="TUI_red">Обязательно для заполнения!</b>
                        </pre>
                        <label class="TUI_input">
                            <input type="text" name="site_mail" value="<?= htmlspecialchars($conf['site_mail']) ?>">
                        </label>
                        Путь к библиотеке jQuery <i class="fas fa-info-circle TUI_red"
                                                    onmouseover="TUI.Hint(this, 'click')"></i>
                        <pre class="TUI_Hint">
                            jQuery &mdash; подключаемый скрипт для правильной
                            работы всего сайта. Подробную информацию и
                            ссылку для подключения актуальной версии
                            можно получить на странице <a href="http://code.jquery.com/" target="_blank">jQuery CDN</a>
                            <b class="TUI_red">Обязательно для заполнения!</b>
                        </pre>
                        <label class="TUI_input">
                            <input type="text" name="jq" value="<?= htmlspecialchars($conf['jq']) ?>">
                        </label>
                        Ключ Google Map API <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Система использует сервис Google Map чтобы вы могли
                            указать расположение и отобразить карту на странице
                            <q>Контакты</q> или других страницах. Чтобы это работало,
                            необходимо иметь аккаунт в Google, вкрючить API
                            и получить ключ. Если у вас возникли трудности
                            связанные с получением ключа или отображением карт,
                            обратитесь к разработчику или веб-мастеру.
                        </pre>
                        <label class="TUI_input">
                            <input type="text" name="gapi_key" value="<?= htmlspecialchars($conf['gapi_key']) ?>">
                        </label>
                    </div>
                </div>

                <div class="TUI_col-6">

                    <!--
                    ########### Макет и редактор
                    -->

                    <div class="touch">
                        <h2>Макет и редактор</h2>
                        <hr>
                        Ширина шаблона (в пикселах)
                        <label class="TUI_input">
                            <input type="text" name="body_width" value="<?= ($conf['body_width']) ?>">
                        </label>
                        Ширина левой колонки макета <q>Контент</q> (в процентах)
                        <label class="TUI_input">
                            <input type="text" name="content_l_width"
                                   value="<?= htmlspecialchars($conf['content_l_width']) ?>">
                        </label>
                        Emmet <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this, 'click')"></i>
                        <pre class="TUI_Hint">
                            Emmet &mdash; плагин который в некоторой
                            степени ускоряет написание кода HTML, CSS.
                            В системе используется <a href="https://github.com/emmetio/textarea" target="_blank">emmet for &lt;textarea&gt;</a>
                            <a href="http://docs.emmet.io" target="_blank">Документация и синтаксис emmet</a>
                        </pre>
                        <label class="TUI_select">
                            <select name="emmet">
                                <option value="1" <?= $conf['emmet'] ? 'selected' : '' ?>>Emmet включен</option>
                                <option value="0" <?= !$conf['emmet'] ? 'selected' : '' ?>>Emmet выключен</option>
                            </select>
                        </label>
                    </div>

                    <!--
                    ########### Вывод
                    -->

                    <div class="touch">
                        <h2>Вывод</h2>
                        <hr>
                        Микроразметка <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Микроразметка предназначена для улучшения
                            отображения и выделения результатов поиска.
                        </pre>
                        <label class="TUI_select">
                            <select name="markup_data">
                                <option value="1" <?= $conf['markup_data'] ? 'selected' : '' ?>>Микроразметка включена
                                </option>
                                <option value="0" <?= !$conf['markup_data'] ? 'selected' : '' ?>>Микроразметка выключена
                                </option>
                            </select>
                        </label>
                    </div>

                    <!--
                    ########### Кнопки соцсетей
                    -->

                    <div class="touch">
                        <h2 class="float_l">Социальные сети</h2>
                        <a href="#" onclick="TUI.Toggle('addthis_opt');return false"><i class="fas fa-chevron-down"></i></a>
                        <hr>
                        <div id="addthis_opt" hidden>
                            Путь к JavaScript-коду подключения <i class="fas fa-info-circle TUI_blue"
                                                                  onmouseover="TUI.Hint(this)"></i>
                            <pre class="TUI_Hint">
                                После регистрации в сервисе AddThis и создания кнопок
                                вы получаете небольшой код который нужно разместить
                                на вашем сайте. Скопируйте в это поле путь из тега
                                &lt;script&gt;, атрибут <q>src</q>.
                            </pre>
                            <label class="TUI_textarea">
                                <textarea name="addthis[js]"
                                          placeholder="//s7.addthis.com/js/300/addthis_widget.js#pubid=XXXXXXXXXXXXXXX"
                                          rows="2"><?= $conf['addthis']['js'] ?></textarea>
                            </label>
                            HTML-код кнопок <q>Share</q> <i class="fas fa-info-circle TUI_blue"
                                                            onmouseover="TUI.Hint(this)"></i>
                            <pre class="TUI_Hint">
                                Создайте в разделе <q>Share</q> сервиса <q>AddThis</q> набор
                                кнопок для вашего сайта и скопируйте полученный HTML-код
                                в это поле. Созданные вами кнопки будут показаны на страницах.
                                С их помощью посетители вашего сайта смогут поделиться ссылкой
                                и информацией о вашей странице в своих социальных сетях.
                            </pre>
                            <label class="TUI_textarea">
                                <textarea name="addthis[share]"
                                          placeholder="<div class='addthis_sharing_toolbox'></div>"
                                          rows="2"><?= $conf['addthis']['share'] ?></textarea>
                            </label>
                            HTML-код кнопок <q>Follow</q> <i class="fas fa-info-circle TUI_blue"
                                                             onmouseover="TUI.Hint(this)"></i>
                            <pre class="TUI_Hint">
                                Создайте в разделе <q>Follow</q> сервиса <q>AddThis</q> набор
                                кнопок для вашего сайта и скопируйте полученный HTML-код
                                в это поле. Созданные вами кнопки будут показаны на страницах.
                                С их помощью посетители вашего сайта смогут посетить
                                ваши страницы в социальных сетях.
                            </pre>
                            <label class="TUI_textarea">
                                <textarea name="addthis[follow]"
                                          placeholder="<div class='addthis_horizontal_follow_toolbox'></div>"
                                          rows="2"><?= $conf['addthis']['follow'] ?></textarea>
                            </label>
                            Кнопки <q>Share</q> в системе по умолчанию
                            <label class="TUI_select">
                                <select name="addthis[share_def]">
                                    <option value="0" <?= !$conf['addthis']['share_def'] ? 'selected' : '' ?>>Скрыть
                                    </option>
                                    <option value="1" <?= $conf['addthis']['share_def'] ? 'selected' : '' ?>>Показать
                                    </option>
                                </select>
                            </label>
                            Кнопки <q>Follow</q> в системе по умолчанию
                            <label class="TUI_select">
                                <select name="addthis[follow_def]">
                                    <option value="0" <?= !$conf['addthis']['follow_def'] ? 'selected' : '' ?>>Скрыть
                                    </option>
                                    <option value="1" <?= $conf['addthis']['follow_def'] ? 'selected' : '' ?>>Показать
                                    </option>
                                </select>
                            </label>
                            Превью-изображение <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                            <pre class="TUI_Hint">
                                Ссылка на изображение доступное из Интернета
                                или выбранное в менеджере файлов. Изображение
                                будет использовано по умолчанию во всех
                                вновь создаваемых материалах как превью на
                                материал в соцсетях и в списке материалов раздела.
                                Желательно выбирать изображение 1200х630 px.
                            </pre>
                            <div class="TUI_fieldset">
                                <label class="TUI_input">
                                    <input type="text" name="img_prev_def" id="img_prev_def"
                                           value="<?= htmlspecialchars($conf['img_prev_def']) ?>">
                                </label>
                                <a href="#" class="fas fa-folder-open fa-2x TUI_blue"
                                   onclick="files('img_prev_def');return false"></a>
                                <i class="fas fa-eye fa-2x TUI_blue" onmouseover="img_prev(this, '#img_prev_def')"></i>
                                <pre class="TUI_Hint"></pre>
                            </div>
                        </div>
                    </div>

                    <!--
                    ########### Навигация "хлебные крошки"
                    -->

                    <div class="touch">
                        <h2 class="float_l">Навигация <q>хлебные крошки</q></h2>
                        <a href="#" onclick="TUI.Toggle('bc_opt');return false"><i class="fas fa-chevron-down"></i></a>
                        <hr>
                        <div id="bc_opt" hidden>
                            <label class="TUI_select">
                                <select name="breadcrumbs[public]">
                                    <option value="1" <?= $conf['breadcrumbs']['public'] ? 'selected' : '' ?>>
                                        Показать "хлебные крошки"
                                    </option>
                                    <option value="0" <?= !$conf['breadcrumbs']['public'] ? 'selected' : '' ?>>
                                        Скрыть "хлебные крошки"
                                    </option>
                                </select>
                            </label>
                            <label class="TUI_checkbox inline" style="margin:0">
                                <input type="checkbox"
                                       name="breadcrumbs[home]" <?= isset($conf['breadcrumbs']['home']) ? 'value="1" checked' : '' ?>>
                                <span class="custom-checkbox"></span>
                                Ссылка на главную
                            </label>&nbsp;
                            <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                            <pre class="TUI_Hint">
                                Будет ли показана ссылка на страницу <q>Главная</q>
                                в начале цепочки <q>хлебных крошек</q>.
                            </pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="TUI_fieldset this_form_control">
                <button type="button" onclick="subm(form, req)">Сохранить все настройки</button>
            </div>
        </form>
    </dd>

    <?php
    if ($conf['status'] === 'administrator') {
        $this->load->helper('back/back_user_control');
        ?>
        <dt>Администратор</dt>
        <dd>
            <p>Администратор &mdash; это статус с полными правами доступа к административной части системы и правом
                действий от имени администратора в пользовательской части. Администратор в системе может быть только
                один.</p>
            <?php administrator_control() ?>
        </dd>
        <dt>Модераторы</dt>
        <dd>
            <p>Модератор &mdash; это статус с ограниченными правами доступа к административной части системы и правом
                действий от имени модератора в пользовательской части. Модератор может управлять всем кроме настроек
                администратора и модераторов. Администратор может запретить <q>доступ</q> модератору использовать
                вышеперечисленные права.</p>
            <?php moderators_control() ?>
        </dd>
    <?php } ?>

</dl>

<script>
    // рег.выражения для проверки полей
    const req = {
        site_mail: /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/,
        jq: /[^\s]/
    };
</script>

