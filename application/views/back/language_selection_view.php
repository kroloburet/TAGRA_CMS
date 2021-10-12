<h1><?= $data['view_title'] ?></h1>

<!--
########### Выбор языка
-->

<form method="get" action="<?= current_url() ?>">
    <label class="TUI_select">
        <select name="lang" onchange="submit()">
            <option>Выберите язык</option>
            <?php foreach ($conf['langs'] as $v) { ?>
                <option value="<?= $v['tag'] ?>"><?= "{$v['title']} [{$v['tag']}]" ?></option>
            <?php } ?>
        </select>
    </label>
</form>

