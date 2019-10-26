<?php if(ENVIRONMENT==='development'||ENVIRONMENT==='testing'||$conf['site_access']==='off'){?>
 <!--Справка по режиму "разработка\тестирование"-->
 <div class="notific_y" style="margin-top:1.5em">
  Внимание! Ресурс находится в режиме <q>разработка</q> или <q>тестирование</q>. <a href="#" onclick="opn_cls('env_info');return false">Подробнее</a>
  <div id="env_info" class="opn_cls">
   <h3>О режимах</h3>
   <hr>
   <p>Данное сообщение сигнализирует о том, что пользовательская часть сайта закрыта (настройка <q>Доступ к сайту</q> на странице <q>Конфигурация</q>) или в файле /index.php значение константы ENVIRONMENT задано как <q>development</q> или <q>testing</q>. Если разработчик закончил работу над ресурсом и протестировал его, рекомендуется задать значение константы ENVIRONMENT как <q>production</q>. Это запретит системе выводить ошибки PHP и базы данных, которыми могут воспользоваться злоумышленники.</p>
   <a href="#" onclick="opn_cls('env_info');return false">Скрыть подробности</a>
  </div>
 </div>
<?php }?>

<div id="copy">
 Веб-разработка и дизайн
 <a href="mailto:kroloburet@gmail.com"><img src="/img/i.jpg" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com</a><br>
 <img src="/img/tagra_min.svg" alt="Tagra CMS"> Tagra CMS <sup><?=$this->config->item('tagra_version')?></sup>
</div>
</div>

<script src="/UI_fraimwork/js.js"></script>
<script src="/scripts/back/overall_js.js"></script>
<script src="/scripts/tinymce_4.7.11/plugins/moxiemanager/js/moxman.loader.min.js"></script>
<?php if($conf['emmet']=='on'){?>
 <script src="/scripts/libs/emmet.min.js"></script>
 <script>
  emmet.require('textarea').setup({//Emmet plugin for <textarea> https://github.com/emmetio/textarea
   pretty_break:true,// enable formatted line breaks (when inserting between opening and closing tag)
   use_tab:true// expand abbreviations by Tab key
  });
 </script>
<?php }?>

<!--
########### Вот ты сейчас в этой уйне копошишься,
########### а жизнь мимо летит..)
-->

</body>
</html>