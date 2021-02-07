<h1><?= $data['view_title'] ?></h1>

<!--
########### Фильтр
-->

<form id="filter" class="sheath" method="GET" action="<?= current_url() ?>">
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

<?php if (empty($data['home_pages'])) { ?>
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
            <td>Язык</td>
            <td>Действия</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['home_pages'] as $v) { ?>
            <tr>
                <td title="<?= $v['title'] ?>"><?= mb_strimwidth($v['title'], 0, 40, '...') ?></td>
                <td title="<?= $v['lang'] ?>"><?= $v['lang'] ?></td>
                <td>
                    <a href="/admin/home/edit_form/<?= $v['id'] ?>"
                       class="fas fa-edit" title="Редактировать"></a>&nbsp;&nbsp;
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

<?php if (!empty($data['filter'])) { ?>
    <script>
        $(function () {
            // значения полей фильтра
            const filter = $('#filter');
            filter.find('select[name="filter[lang]"] option[value="<?= $data['filter']['lang'] ?>"]').attr('selected', true);
            filter.find('select[name="filter[order]"] option[value="<?= $data['filter']['order'] ?>"]').attr('selected', true);
            filter.find('select[name="filter[limit]"] option[value="<?= $data['filter']['limit'] ?>"]').attr('selected', true);
            filter.find('select[name="filter[context_search]"] option[value="<?= $data['filter']['context_search'] ?>"]').attr('selected', true);
            filter.find('input[name="filter[search]"]').val('<?= $data['filter']['search'] ?>');
        });
    </script>
<?php } ?>
