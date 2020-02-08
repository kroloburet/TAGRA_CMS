<?php if (ENVIRONMENT === 'development' || ENVIRONMENT === 'testing' || !$conf['site_access']) { ?>

<!--
########### Справка по режиму "Разработка/тестирование"
-->

<div class="notific_y" style="margin-top:1.5em">
  Внимание! Ресурс находится в режиме <q>разработка</q> или <q>тестирование</q>.
  <a href="#" onclick="TUI.Toggle('#env_info');return false">Подробнее</a>
  <div id="env_info" hidden>
    <h3>О режимах</h3>
    <hr>
    <p>Данное сообщение сигнализирует о том, что пользовательская часть сайта закрыта (настройка <q>Доступ к сайту</q> на странице <q>Конфигурация</q>) или в файле /index.php значение константы ENVIRONMENT задано как <q>development</q> или <q>testing</q>. Если разработчик закончил работу над ресурсом и протестировал его, рекомендуется задать значение константы ENVIRONMENT как <q>production</q>. Это запретит системе выводить ошибки PHP и базы данных, которыми могут воспользоваться злоумышленники.</p>
    <a href="#" onclick="TUI.Toggle('#env_info');return false">Скрыть подробности</a>
  </div>
</div>
<?php } ?>

<!--
########### Footer
-->

<div id="copy">
  Веб-разработка и дизайн
  <a href="mailto:kroloburet@gmail.com">
    <img src="/img/i.jpg" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com
  </a><br>
  <img src="/img/tagra.svg" alt="Tagra CMS"> Tagra CMS <sup><?= $this->config->item('tagra_version') ?></sup>
</div>
</div>

<script src="/Tagra_UI/script.js"></script>
<script src="/scripts/back/overall_js.js"></script>
<script src="/scripts/libs/tinymce_4.7.11/plugins/moxiemanager/js/moxman.loader.min.js"></script>
<?php if ($conf['emmet']) { ?>
<script src="/scripts/libs/emmet.min.js"></script>
<script>
    emmet.require('textarea').setup({//Emmet plugin for <textarea> https://github.com/emmetio/textarea
      pretty_break: true, // enable formatted line breaks (when inserting between opening and closing tag)
      use_tab: true// expand abbreviations by Tab key
    });
</script>
<?php } ?>

<!--
########### Вот ты сейчас в этой уйне копошишься,
########### а жизнь мимо летит..)
-->

</body>
</html>
