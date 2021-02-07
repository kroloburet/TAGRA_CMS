<h1><?= $data['view_title'] ?></h1>

<?php if (!empty($data['new_comments'])) { ?>

    <!--
    ########### Новые комментарии
    -->

    <div class="sheath">
        <h2>Новые комментарии</h2>
        <hr>
        <?php foreach ($data['new_comments'] as $v) { ?>
            <div class="touch">
                <div class="new_comment_header">
                    <!-- комментатор -->
                    <div>
                        <b><?= $v['name'] ?></b>
                        <?= $v['pid'] > 0
                            ? ' в ответ на <a href="' . base_url($v['url'] . '#comment_' . $v['pid'])
                            . '" target="_blank">комментарий</a>'
                            : '' ?>
                    </div>
                    <!-- действия -->
                    <div>
                        <a href="/admin/comment/public_new/<?= $v['premod_code'] ?>"
                           class="fas fa-check-circle TUI_green"
                           title="Опубликовать комментарий"></a>&nbsp;&nbsp;
                        <a href="/admin/comment/del_new/<?= $v['premod_code'] ?>"
                           class="fas fa-trash-alt TUI_red"
                           title="Удалить комментарий"
                           onclick="if (! confirm('Комментарий будет удален!\nВыполнить действие?')) return false"></a>&nbsp;&nbsp;
                        <a href="/<?= $v['url'] ?>"
                           target="_blank"
                           class="fas fa-external-link-alt"
                           title="Перейти на страницу"></a>&nbsp;&nbsp;
                        <span class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></span>
                        <pre class="TUI_Hint">
                            <b>ID: </b><?= $v['id'] . PHP_EOL ?>
                            <b>URL: </b>/<?= $v['url'] . PHP_EOL ?>
                            <b>Дата: </b><?= $v['creation_date'] ?>
                        </pre>
                    </div>
                </div>
                <div>
                    <?= $v['comment'] ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<dl class="TUI_Tab">
    <dt>Фильтры</dt>
    <dd>

        <!--
        ########### Фильтр
        -->

        <form id="filter" method="GET" action="<?= current_url() ?>">
            <div class="TUI_row">
                <div class="TUI_col-4">
                    Язык
                    <label class="TUI_select">
                        <select name="filter[lang]" onchange="submit()">
                            <option value="all">Все</option>
                            <?php foreach ($conf['langs'] as $v) { ?>
                                <option value="<?= $v['tag'] ?>"><?= "{$v['title']} [{$v['tag']}]" ?></option>
                            <?php } ?>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-4">
                    Сортировать
                    <label class="TUI_select">
                        <select name="filter[order]" onchange="submit()">
                            <option value="creation_date">По дате добавления</option>
                            <option value="name">По имени комментатора</option>
                            <option value="url">По URL</option>
                            <option value="lang">По языку</option>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-4">
                    Выводить записей
                    <label class="TUI_select">
                        <select name="filter[limit]" onchange="submit()">
                            <option value="all">Все</option>
                            <option value="500">500</option>
                            <option value="300">300</option>
                            <option value="100">100</option>
                            <option value="50">50</option>
                            <option value="20">20</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="TUI_row">
                <div class="TUI_col-4">
                    Контекст поиска
                    <label class="TUI_select">
                        <select name="filter[context_search]">
                            <option value="comment">Текст комментария</option>
                            <option value="name">Имя комментатора</option>
                        </select>
                    </label>
                </div>
                <div class="TUI_col-8">
                    Искать в контексте
                    <label class="TUI_search">
                        <input type="search" name="filter[search]" placeholder="Строка запроса">
                        <button type="submit">Поиск</button>
                    </label>
                </div>
            </div>
        </form>
    </dd>
    <dt>Настройки</dt>
    <dd>

        <!--
        ########### Настройки комментариев
        -->

        <form method="POST" action="/admin/comment/set_comments_config">
            <div class="TUI_row">
                <div class="TUI_col-6">
                    <!-- вывод -->
                    <div class="touch">
                        <h2>Вывод</h2>
                        <hr>
                        Форма комментирования по умолчанию <i class="fas fa-info-circle TUI_blue"
                                                              onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Настройка будет применена по умолчанию
                            во всех вновь создаваемых материалах.
                            Вы сможете изменить ее индивидуально
                            на страницах добавления и редактирования
                            материалов.
                        </pre>
                        <label class="TUI_select">
                            <select name="form">
                                <option value="on">Разрешить комментировать и отвечать</option>
                                <option value="on_comment">Разрешить только комментировать</option>
                                <option value="off">Запретить комментировать и отвечать</option>
                            </select>
                        </label>
                        Зарезервированные имена <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Здесь вы можете перечислить через точку с запятой (;)
                            имена и e-mail которые смогут использовать только
                            администратор и модераторы системы. Эта настройка не
                            позволит никому кроме вас комментировать и отвечать от
                            имени, к примеру, администратора. Валидация имен
                            не чувствительна к регистру (Админ = админ) но
                            чувствительна к символам разной локали ([eng A]дмин &ne; админ)
                        </pre>
                        <label class="TUI_input">
                            <input type="text" name="reserved_names"
                                   value="<?= htmlspecialchars($data['conf']['reserved_names']) ?>">
                        </label>
                        Рейтинг комментариев <i class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Комментарии и ответы могут быть оценены
                            пользователями <q>лайками</q> и <q>дизлайками</q>.
                        </pre>
                        <label class="TUI_select">
                            <select name="rating">
                                <option value="1">Разрешить рейтинг</option>
                                <option value="0">Запретить рейтинг</option>
                            </select>
                        </label>
                        Лимит в поле <q>Ваше имя</q> <i class="fas fa-info-circle TUI_blue"
                                                        onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Лимит символов в поле <q>Ваше имя</q>
                            форм комментирования и ответа.
                            Если <q>0</q> &mdash; лимит безграничный.
                            <b class="TUI_red">Целое, положительное число!</b>
                        </pre>
                        <label class="TUI_number">
                            <input name="name_limit" type="number" min="0" class="TUI_InputNumber"
                                   value="<?= htmlspecialchars($data['conf']['name_limit']) ?>">
                        </label>
                        Лимит в поле <q>Ваш комментарий</q> <i class="fas fa-info-circle TUI_blue"
                                                               onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Лимит символов в поле <q>Ваш комментарий</q>
                            и <q>Ваш ответ</q> формы комментирования и ответа.
                            Если <q>0</q> &mdash; лимит безграничный.
                            <b class="TUI_red">Целое, положительное число!</b>
                        </pre>
                        <label class="TUI_number">
                            <input name="text_limit" type="number" min="0" class="TUI_InputNumber"
                                   value="<?= htmlspecialchars($data['conf']['text_limit']) ?>">
                        </label>
                        Кнопка <q>Еще комментарии</q> <i class="fas fa-info-circle TUI_blue"
                                                         onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Установите число комментариев после которых
                            в списке будет выведена кнопка <q>Еще комментарии</q>,
                            а остальной список будет скрыт.
                            Если <q>0</q> &mdash; не скрывать комментарии.
                            <b class="TUI_red">Целое, положительное число!</b>
                        </pre>
                        <label class="TUI_number">
                            <input name="show" type="number" min="0" class="TUI_InputNumber"
                                   value="<?= htmlspecialchars($data['conf']['show']) ?>">
                        </label>
                    </div>
                </div>
                <div class="TUI_col-6">
                    <!-- уведомления -->
                    <div class="touch">
                        <h2>Уведомления</h2>
                        <hr>
                        Уведомления о новых комментариях <i class="fas fa-info-circle TUI_blue"
                                                            onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Выберите e-mail на который система будет
                            отправлять уведомления о новых комментариях
                            к материалам, или если комментарии нуждаются в
                            премодерации перед публикацией.
                        </pre>
                        <label class="TUI_select">
                            <select name="notific">
                                <option value="off">Не уведомлять и публиковать по умолчанию</option>
                                <optgroup label="Без премодерации">
                                    <option value="site_mail">На e-mail сайта</option>
                                    <option value="admin_mail">На e-mail администратора</option>
                                    <option value="moderator_mail">На e-mail всем модераторам</option>
                                </optgroup>
                                <optgroup label="С премодерацией">
                                    <option value="premod_site_mail">На e-mail сайта [премодерация]</option>
                                    <option value="premod_admin_mail">На e-mail администратора [премодерация]</option>
                                    <option value="premod_moderator_mail">На e-mail всем модераторам [премодерация]
                                    </option>
                                </optgroup>
                            </select>
                        </label>
                        Обратная связь с комментатором <i class="fas fa-info-circle TUI_blue"
                                                          onmouseover="TUI.Hint(this)"></i>
                        <pre class="TUI_Hint">
                            Опция позволяет уведомлять пользователей
                            об ответах на их комментарии. Система
                            предлагает указать e-mail в поле <q>Ваше имя</q>
                            и отправляет уведомление об ответе. Система
                            скрывает в списке комментариев e-mail,
                            выводит только имя пользователя e-mail.
                            Например: <q>pupkin</q> из <q>pupkin@gmail.com</q>
                        </pre>
                        <label class="TUI_select">
                            <select name="feedback">
                                <option value="1">Разрешить обратную связь</option>
                                <option value="0">Запретить обратную связь</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div class="TUI_fieldset this_form_control">
                <button type="button" onclick="subm(form, req)">Сохранить все настройки</button>
            </div>
        </form>
    </dd>
</dl>

<?php if (empty($data['comments'])) { ?>
    <div class="sheath">
        <p>Ничего не найдено. Запрос не дал результатов..(</p>
    </div>
<?php } else { ?>

    <!--
    ########### Таблица записей
    -->

    <table class="TUI_table">
        <thead>
        <tr>
            <td>Дата</td>
            <td>Комментатор</td>
            <td>URL</td>
            <td>Язык</td>
            <td>Действия</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $tree_arr = [];
        foreach ($data['comments'] as $v) {// получить многомерный массив
            $tree_arr[$v['pid']][] = $v;
        }
        // построение дерева
        function build_tree($tree_arr, $pid = 0)
        {
            if (!is_array($tree_arr) || !isset($tree_arr[$pid])) {
                return;// нет данных
            }
            foreach ($tree_arr[$pid] as $v) {
                ?>
                <tr id="<?= $v['id'] ?>">
                    <td><?= $v['creation_date'] ?></td>
                    <td><?= $v['pid'] > 0
                            ? '<a href="' . base_url($v['url'] . '#comment_' . $v['pid'])
                            . '" class="fas fa-level-up-alt" target="_blank" '
                            . 'title="Перейти к родительскому комментарию"></a> '
                            . mb_strimwidth($v['name'], 0, 40, '...')
                            : mb_strimwidth($v['name'], 0, 40, '...') ?></td>
                    <td>/<?= mb_strimwidth($v['url'], 0, 40, '...') ?></td>
                    <td><?= $v['lang'] ?></td>
                    <td>
                        <span class="fas fa-info-circle TUI_blue" onmouseover="TUI.Hint(this)"></span>
                        <pre class="TUI_Hint" style="max-width:400px;white-space:normal;"><?= $v['comment'] ?></pre>&nbsp;&nbsp;
                        <a href="<?= base_url($v['url'] . '#comment_' . $v['id']) ?>"
                           target="_blank" class="fas fa-external-link-alt"
                           title="Перейти к комментарию на странице"></a>&nbsp;&nbsp;
                        <a href="#" class="fas fa-trash-alt TUI_red"
                           title="Удалить с дочерней ветвью"
                           onclick="del_branch(<?= $v['id'] ?>, '<?= $v['url'] ?>');return false"></a>
                    </td>
                </tr>
                <?php
                build_tree($tree_arr, $v['id']);
            }
        }

        // вывод дерева
        build_tree($tree_arr);
        ?>
        </tbody>
    </table>

    <!--
    ########### Постраничная навигация
    -->

    <?= $this->pagination->create_links() ?>

<?php } ?>

<script>
    // рег.выражения для проверки полей
    const req = {
        name_limit: /^(0|[1-9]\d*)$/,
        text_limit: /^(0|[1-9]\d*)$/,
        show: /^(0|[1-9]\d*)$/
    };

    $(function () {
        <?php if (!empty($data['filter'])) { ?>
        // значения полей фильтра
        const filter = $('#filter');
        filter.find('select[name="filter[lang]"] option[value="<?= $data['filter']['lang'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[order]"] option[value="<?= $data['filter']['order'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[limit]"] option[value="<?= $data['filter']['limit'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[context_search]"] option[value="<?= $data['filter']['context_search'] ?>"]').attr('selected', true);
        filter.find('input[name="filter[search]"]').val('<?= $data['filter']['search'] ?>');
        <?php } ?>
        // значения полей настроек
        $('select[name="form"] option[value="<?= $data['conf']['form'] ?>"]').attr('selected', true);
        $('select[name="rating"] option[value="<?= $data['conf']['rating'] ?>"]').attr('selected', true);
        $('select[name="notific"] option[value="<?= $data['conf']['notific'] ?>"]').attr('selected', true);
        $('select[name="feedback"] option[value="<?= $data['conf']['feedback'] ?>"]').attr('selected', true);
    });

    /**
     * Удалить комментарий вместе с ветвью дочерних
     *
     * @param {string} id Идентификатор комментария
     * @param {string} url URL материала с комментарием
     * @returns {void}
     */
    function del_branch(id, url) {
        if (!confirm('Комментарий вместе с ветвью дочерних комментариев будут удалены!\nВыполнить действие?')) return;
        $.ajax({
            url: '/admin/comment/del_branch',
            type: 'post',
            data: {id: id, url: url},
            dataType: 'json',
            success: function (resp) {
                switch (resp.status) {
                    case 'ok':
                        for (let i in resp.ids) {
                            $('#' + resp.ids[i]).remove();
                        }
                        break;
                    case 'error':
                        alert('Ошибка! Не удалось удалить комментарий..(');
                        break;
                    default :
                        console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                        alert('Ой! Что-то пошло не так..(\nСведения о неполадке выведены в консоль.');
                }
            },
            error: function (xhr, status, thrown) {
                console.error(`#### TAGRA ERROR INFO ####\n\nПричина: ${thrown}\nОтвет сервера:\n${xhr.responseText}`);
                alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет. Повторите попытку.');
            }
        });
    }
</script>
