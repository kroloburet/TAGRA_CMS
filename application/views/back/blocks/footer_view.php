<?php if(ENVIRONMENT==='development'||ENVIRONMENT==='testing'||$conf_site_access==='off'){?>
<!--Справка по режиму "разработка\тестирование"-->
<div class="notific_y" style="margin-top:1.5em">
 Внимание! Ресурс находится в режиме "разработка" или "тестирование". <a href="#" onclick="opn_cls('env_info');return false">Подробнее</a>
 <div id="env_info" class="opn_cls">
  <h3>О режимах</h3>
  <hr>
  <p>Данное сообщение сигнализирует о том, что пользовательская часть сайта закрыта (настройка «Доступ к сайту» на странице «Конфигурация») или в файле /index.php значение константы ENVIRONMENT задано как "development" или "testing". Если разработчик, программист закончил работу над ресурсом и протестировал его, рекомендуется задать значение константы ENVIRONMENT как "production". Это запретит системе выводить ошибки PHP и базы данных, которыми могут воспользоваться злоумышленники.</p>
  <a href="#" onclick="opn_cls('env_info');return false">Скрыть подробности</a>
 </div>
</div>
<?php }?>
<div id="copy">
 Веб-разработка и дизайн<a href="mailto:kroloburet@gmail.com"> <img src="<?=base_url('UI_fraimwork/img/kroloburet_18_18.jpeg')?>" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com</a><br>
 <img src="<?=base_url('UI_fraimwork/img/logo_tagra_18_18.svg')?>" alt="Tagra CMS"> Tagra CMS <sup><?=$this->config->item('tagra_version')?></sup><br>
</div>
</div>
<!--########### END CONTAINER ###########-->
<script src="<?= base_url('UI_fraimwork/js.js'); ?>"></script>
<script src="<?= base_url('scripts/back/overall_js.js'); ?>"></script>
<?php $this->load->helper('back/redactor');?>
<script src="<?=base_url('scripts/tinymce_4.7.11/plugins/moxiemanager/js/moxman.loader.min.js')?>"></script>
<?php if($conf_emmet=='on'){?>
<script src="<?=base_url('scripts/libs/emmet.min.js')?>"></script>
<script>
emmet.require('textarea').setup({//Emmet plugin for <textarea> https://github.com/emmetio/textarea
 pretty_break: true,// enable formatted line breaks (when inserting between opening and closing tag)
 use_tab: true// expand abbreviations by Tab key
});
</script>
<?php }?>
<!--#################################
Вот ты сейчас в этой уйне копошишься,
а жизнь мимо летит..)
##################################-->
</body>
</html>