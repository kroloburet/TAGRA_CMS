<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять, имя нового поля должно быть добавлено в
//таблицу бд (поле имя=бд поле имя, поле валуе=бд поле валуе).
?>
<h1><?=$conf_title?></h1>
<dl class="tabs">

<!--####### Основные настройки #######-->
 <dt class="tab_active">Основные настройки</dt>
 <dd>
  <form method="POST" action="<?=base_url('admin/setting/set_config')?>">
   <div class="tab_content">
    <div class="row">
     <div class="col6">

      <!--####### Общее #######-->
      <div class="row  touch">
       <h3>Общее</h3>
       <hr>
       Доступ к сайту <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Управление доступом к пользовательской
части вашего сайта. Это полезно, например,
когда вы ведете технические работы на сайте,
административную часть изменения не затрагивают.
Если установлено «Доступ к сайту закрыт» —
только администратору и модераторам будет
открыт доступ к пользовательской части сайта.</pre>
       <label class="select">
        <select name="conf_site_access">
         <option value="on" <?=$conf_site_access=='on'?'selected':''?>>Доступ к сайту открыт</option>
         <option value="off" <?=$conf_site_access=='off'?'selected':''?>>Доступ к сайту закрыт</option>
        </select>
       </label>
       Имя сайта
       <label class="input">
        <input type="text" name="conf_site_name" value="<?=$conf_site_name?>">
       </label>
      E-mail с сайта <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
E-mail на который будут приходить
письма от пользователей вашего сайта.
Например: со страницы «Контакты».
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="conf_site_mail" value="<?=$conf_site_mail?>">
       </label>
      Путь к библиотеке JQuery <i class="fa-info-circle red" onmouseover="tt(this,'c');"></i>
       <pre class="tt">
JQuery — подключаемый скрипт для правильной
работы всего сайта. Подробную информацию и
ссылку для подключения актуальной версии
можно получить на странице <a href="http://code.jquery.com/" target="_blank">jQuery CDN</a>
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="conf_jq" value="<?=$conf_jq?>">
       </label>
      Ключ Google map API <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
Система использует сервис Google map чтобы вы могли
указать расположение и отобразить карту на странице
«Контакты» или других страницах. Чтобы это работало,
необходимо иметь аккаунт в Google, вкрючить API
и получить ключ. Если у вас возникли трудности
связанные с получением ключа или отображением карт,
обратитесь к разработчику или веб-мастеру.
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="conf_gapi_key" value="<?=$conf_gapi_key?>">
       </label>
      </div>
     </div>

     <div class="col6">

      <!--####### Макет и редактор #######-->
      <div class="row touch">
       <h3>Макет и редактор</h3>
       <hr>
       Ширина шаблона (в пикселах)
       <label class="input">
        <input type="text" name="conf_body_width" value="<?=$conf_body_width?>">
       </label>
       Ширина левой колонки макета «Контент» (в процентах)
       <label class="input">
        <input type="text" name="conf_layout_l_width" value="<?=$conf_layout_l_width?>">
       </label>
       Emmet <i class="fa-question-circle blue" onmouseover="tt(this,'c');"></i>
       <pre class="tt">
Emmet — плагин который в некоторой
степени ускоряет написание кода HTML, CSS.
В системе используется <a href="https://github.com/emmetio/textarea" target="_blank">emmet for &lt;textarea&gt;</a>
<a href="http://docs.emmet.io" target="_blank">Документация и синтаксис emmet</a></pre>
       <label class="select">
        <select name="conf_emmet">
         <option value="on" <?=$conf_emmet=='on'?'selected':''?>>Emmet включен</option>
         <option value="off" <?=$conf_emmet=='off'?'selected':''?>>Emmet выключен</option>
        </select>
       </label>
      </div>

      <!--####### Вывод #######-->
      <div class="row touch">
       <h3>Вывод</h3>
       <hr>
       Микроразметка <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Микроразметка предназначена для улучшения
отображения результатов поиска, делая процесс
поиска правильных веб-страниц проще для людей.</pre>
       <label class="select">
        <select name="conf_markup_data">
         <option value="on" <?=$conf_markup_data=='on'?'selected':''?>>Микроразметка включена</option>
         <option value="off" <?=$conf_markup_data=='off'?'selected':''?>>Микроразметка выключена</option>
        </select>
       </label>
      </div>

      <!--####### Кнопки соцсетей #######-->
      <div class="row touch">
       <h3 class="float_l">Социальные сети</h3>
       <a href="#" onclick="opn_cls('addthis_opt');return false">Настройки <i class="fa-angle-down"></i></a>
       <hr>
       <div id="addthis_opt" class="opn_cls">
        JAVASCRIPT-код подключения к сервису <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
После регистрации в сервисе AddThis вы получаете
небольшой код который нужно разместить на вашем сайте.
После этого вы можете управлять кнопками «поделиться»
с вашего аккаунта в сервисе.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="conf_addthis_js" placeholder="<script src='//s7.addthis.com/js/300/addthis_widget.js#pubid=XXXXXXXXXXXXXXX'></script>" rows="2"><?=$conf_addthis_js?></textarea>
        </label>
        HTML-код кнопок «Share» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Создайте в разделе «Share» сервиса «AddThis» набор
кнопок для вашего сайта и скопируйте полученный HTML-код
в это поле. Созданные вами кнопки будут показанны на страницах.
С их помощью посетители вашего сайта смогут поделиться ссылкой
и информацией о вашей странице в своих соц-сетях.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="conf_addthis_share" placeholder="<div class='addthis_sharing_toolbox'></div>" rows="2"><?=$conf_addthis_share?></textarea>
        </label>
        HTML-код кнопок «Follow» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Создайте в разделе «Follow» сервиса «AddThis» набор
кнопок для вашего сайта и скопируйте полученный HTML-код
в это поле. Созданные вами кнопки будут показанны на страницах.
С их помощью посетители вашего сайта смогут посетить
ваши страницы соц-сетей.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="conf_addthis_follow" placeholder="<div class='addthis_horizontal_follow_toolbox'></div>" rows="2"><?=$conf_addthis_follow?></textarea>
        </label>
        Кнопки «Share» в системе по умолчанию
        <label class="select">
         <select name="conf_addthis_share_def">
          <option value="off" <?=$conf_addthis_share_def=='off'?'selected':''?>>скрыть</option>
          <option value="on" <?=$conf_addthis_share_def=='on'?'selected':''?>>показать</option>
         </select>
        </label>
        Кнопки «Follow» в системе по умолчанию
        <label class="select">
         <select name="conf_addthis_follow_def">
          <option value="off" <?=$conf_addthis_follow_def=='off'?'selected':''?>>скрыть</option>
          <option value="on" <?=$conf_addthis_follow_def=='on'?'selected':''?>>показать</option>
         </select>
        </label>
        Превью для соцсетей <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Ссылка на изображение доступное из Интернета
или выбранное в менеджере файлов. Изображение
будет использовано по умолчанию во всех
вновь создаваемых материалах как привью на
материал в соцсетях и в списке материалов раздела.
Желательно выбирать изображение 1200х630.</pre><br>
        <label class="input inline width80">
         <input type="text" name="conf_img_prev_def" id="conf_img_prev_def" value="<?=$conf_img_prev_def?>">
        </label>
        <a href="#" class="fa-folder-open fa-lg blue" onclick="files('conf_img_prev_def');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'conf_img_prev_def')"></i>
        <pre class="tt"></pre>
       </div>
      </div>

      <!--####### Навигация «хлебные крошки» #######-->
      <div class="row touch">
       <h3 class="float_l">Навигация «хлебные крошки»</h3><span>
        <a href="#" onclick="opn_cls('bc_opt');return false">Настройки <i class="fa-angle-down"></i></a>
       </span>
       <hr>
       <div id="bc_opt" class="opn_cls">
        <label class="select">
         <select name="conf_breadcrumbs_public">
          <option value="on" <?=$conf_breadcrumbs_public=='on'?'selected':''?>>Показать «хлебные крошки»</option>
          <option value="off" <?=$conf_breadcrumbs_public=='off'?'selected':''?>>Скрыть «хлебные крошки»</option>
         </select>
        </label>
        Ссылка на главную  <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Текст или HTML который будет отображаться как
ссылка на главную в начале цепочки «хлебных крошек».
Оставьте поле пустым чтобы не отображать ссылку.
ВНИМАНИЕ! В HTML-коде используйте двойные кавычки
Пример: &lt;span class=&quot;home&quot;&gt;Home&lt;/span&gt;
        </pre>
        <label class="input">
         <input type="text" name="conf_breadcrumbs_home" value='<?=$conf_breadcrumbs_home?>'>
        </label>
       </div>
      </div>
     </div>
    </div>

    <div class="button this_form_control">
     <button type="button" onclick="subm(form,s_opts)">Сохранить все настройки</button>
    </div>
   </div>
  </form>
 </dd>

 <?php if($conf_status=='administrator'){$this->load->helper('back/back_user_control')?>
 <dt>Администратор</dt>
 <dd>
  <div class="tab_content">
   <p>Администратор — это статус с полными правами доступа к административной части системы и правом действий от имени администратора в пользовательской части. Администратор в системе может быть только один.</p>
   <?php administrator_control()?>
  </div>
 </dd>
 <dt>Модераторы</dt>
 <dd>
  <div class="tab_content">
   <p>Модератор — это статус с ограниченными правами доступа к административной части системы и правом действий от имени модератора в пользовательской части. Модератор может управлять всем кроме настроек администратора и модераторов. Администратор может запретить «доступ» модератору использовать вышеперчисленные права.</p>
   <?php moderators_control()?>
  </div>
 </dd>
 <?php }?>

</dl>
<script>
////////////////////////////////////////////////рег.выражения для проверки полей
var s_opts={
 conf_site_mail:/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/,
 conf_jq:/[^\s]/,
 conf_gapi_key:/[^\s]/
};
</script>
