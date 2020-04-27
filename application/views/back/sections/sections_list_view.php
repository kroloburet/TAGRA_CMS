<h1><?= $data['view_title'] ?></h1>

<!--
########### Фильтр
-->

<form id="filter" class="sheath" method="GET" action="<?= current_url() ?>">
    <div class="TUI_fieldset TUI_align-r">
        <a href="/admin/section/add_form" class="TUI_btn-link">Добавить раздел</a>
    </div>
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
                    <option value="creation_date">По дате создания</option>
                    <option value="last_mod_date">По дате изменения</option>
                    <option value="title">По заголовку</option>
                    <option value="section">По разделу</option>
                    <option value="public">По публикации</option>
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
                    <option value="title">Заголовок</option>
                    <option value="description">Описание</option>
                    <option value="content">Контент</option>
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

<?php if (empty($data['sections'])) { ?>
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
            <td>Заголовок</td>
            <td>Раздел</td>
            <td>Язык</td>
            <td>Действия</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $this->load->helper('back/id_to_title');
        $sec = new id_to_title('sections');
        foreach ($data['sections'] as $v) {
            ?>
            <tr>
                <td><?= mb_strimwidth($v['title'], 0, 40, '...') ?></td>
                <td><?= $sec->get_title($v['section']) ?></td>
                <td><?= $v['lang'] ?></td>
                <td>
                    <span>
                        <a href="#" class="<?= $v['public'] ? 'fas fa-eye' : 'fas fa-eye-slash TUI_red' ?>"
                           title="Опубликовать/не опубликовывать"
                           onclick="toggle_public(this, <?= $v['id'] ?>, 'sections');return false"></a>
                    </span>&nbsp;&nbsp;
                    <a href="/admin/section/edit_form/<?= $v['id'] ?>"
                       class="fas fa-edit" title="Редактировать"></a>&nbsp;&nbsp;
                    <a href="/section/<?= $v['id'] ?>" class="fas fa-external-link-alt"
                       target="_blank" title="Смотреть на сайте"></a>&nbsp;&nbsp;
                    <a href="#" class="fas fa-trash-alt TUI_red" title="Удалить"
                       onclick="del_section(this, <?= $v['id'] ?>);return false"></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!--
    ########### Постраничная навигация
    -->

    <?= $this->pagination->create_links() ?>

<?php } ?>

<script>
    <?php if (!empty($data['filter'])) { ?>
    $(function () {
        // значения полей фильтра
        const filter = $('#filter');
        filter.find('select[name="filter[lang]"] option[value="<?= $data['filter']['lang'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[order]"] option[value="<?= $data['filter']['order'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[limit]"] option[value="<?= $data['filter']['limit'] ?>"]').attr('selected', true);
        filter.find('select[name="filter[context_search]"] option[value="<?= $data['filter']['context_search'] ?>"]').attr('selected', true);
        filter.find('input[name="filter[search]"]').val('<?= $data['filter']['search'] ?>');
    });
    <?php } ?>

    /**
     * Удалить раздел
     *
     * Удалит раздел со всеми дочерними материалами.
     *
     * @param {object} el Ссылка "this" на триггер
     * @param {string} id Идентификатор раздела
     * @returns {void}
     */
    function del_section(el, id) {
        if (!confirm('ВНИМАНИЕ! Раздел со всеми дочерними материалами и комментариями будет удален!\nВыполнить действие?')) return;
        $.ajax({
            url: '/admin/section/del',
            type: 'post',
            data: {id: id},
            dataType: 'text',
            success: function (resp) {
                switch (resp) {
                    case 'ok':
                        $(el).parents('tr').remove();
                        break;
                    case 'error':
                        alert('Ошибка! Не удалось удалить раздел..(');
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
