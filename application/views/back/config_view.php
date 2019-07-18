<h1><?=$data['view_title']?></h1>
<dl class="tabs">

<!--####### Основные настройки #######-->
 <dt class="tab_active">Основные настройки</dt>
 <dd>
  <form method="POST" action="/admin/setting/set_config">
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
        <select name="site_access">
         <option value="on" <?=$conf['site_access']=='on'?'selected':''?>>Доступ к сайту открыт</option>
         <option value="off" <?=$conf['site_access']=='off'?'selected':''?>>Доступ к сайту закрыт</option>
        </select>
       </label>
       Имя сайта
       <label class="input">
        <input type="text" name="site_name" value="<?=htmlspecialchars($conf['site_name'])?>">
       </label>
      E-mail с сайта <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
E-mail на который будут приходить
письма от пользователей вашего сайта.
Например: со страницы «Контакты».
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="site_mail" value="<?=htmlspecialchars($conf['site_mail'])?>">
       </label>
      Путь к библиотеке JQuery <i class="fa-info-circle red" onmouseover="tt(this,'c');"></i>
       <pre class="tt">
JQuery — подключаемый скрипт для правильной
работы всего сайта. Подробную информацию и
ссылку для подключения актуальной версии
можно получить на странице <a href="http://code.jquery.com/" target="_blank">jQuery CDN</a>
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="jq" value="<?=htmlspecialchars($conf['jq'])?>">
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
        <input type="text" name="gapi_key" value="<?=htmlspecialchars($conf['gapi_key'])?>">
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
        <input type="text" name="body_width" value="<?=($conf['body_width'])?>">
       </label>
       Ширина левой колонки макета «Контент» (в процентах)
       <label class="input">
        <input type="text" name="layout_l_width" value="<?=htmlspecialchars($conf['layout_l_width'])?>">
       </label>
       Emmet <i class="fa-question-circle blue" onmouseover="tt(this,'c');"></i>
       <pre class="tt">
Emmet — плагин который в некоторой
степени ускоряет написание кода HTML, CSS.
В системе используется <a href="https://github.com/emmetio/textarea" target="_blank">emmet for &lt;textarea&gt;</a>
<a href="http://docs.emmet.io" target="_blank">Документация и синтаксис emmet</a></pre>
       <label class="select">
        <select name="emmet">
         <option value="on" <?=$conf['emmet']=='on'?'selected':''?>>Emmet включен</option>
         <option value="off" <?=$conf['emmet']=='off'?'selected':''?>>Emmet выключен</option>
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
        <select name="markup_data">
         <option value="on" <?=$conf['markup_data']=='on'?'selected':''?>>Микроразметка включена</option>
         <option value="off" <?=$conf['markup_data']=='off'?'selected':''?>>Микроразметка выключена</option>
        </select>
       </label>
      </div>

      <!--####### Кнопки соцсетей #######-->
      <div class="row touch">
       <h3 class="float_l">Социальные сети</h3>
       <a href="#" onclick="opn_cls('addthis_opt');return false">Настройки <i class="fa-angle-down"></i></a>
       <hr>
       <div id="addthis_opt" class="opn_cls">
        JavaScript-код подключения к сервису <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
После регистрации в сервисе AddThis вы получаете
небольшой код который нужно разместить на вашем сайте.
После этого вы можете управлять кнопками «поделиться»
с вашего аккаунта в сервисе.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="addthis_js" placeholder="<script src='//s7.addthis.com/js/300/addthis_widget.js#pubid=XXXXXXXXXXXXXXX'></script>" rows="2"><?=$conf['addthis_js']?></textarea>
        </label>
        HTML-код кнопок «Share» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Создайте в разделе «Share» сервиса «AddThis» набор
кнопок для вашего сайта и скопируйте полученный HTML-код
в это поле. Созданные вами кнопки будут показанны на страницах.
С их помощью посетители вашего сайта смогут поделиться ссылкой
и информацией о вашей странице в своих соц-сетях.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="addthis_share" placeholder="<div class='addthis_sharing_toolbox'></div>" rows="2"><?=$conf['addthis_share']?></textarea>
        </label>
        HTML-код кнопок «Follow» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Создайте в разделе «Follow» сервиса «AddThis» набор
кнопок для вашего сайта и скопируйте полученный HTML-код
в это поле. Созданные вами кнопки будут показанны на страницах.
С их помощью посетители вашего сайта смогут посетить
ваши страницы соц-сетей.</pre>
        <label class="textarea">
         <textarea class="no-emmet" name="addthis_follow" placeholder="<div class='addthis_horizontal_follow_toolbox'></div>" rows="2"><?=$conf['addthis_follow']?></textarea>
        </label>
        Кнопки «Share» в системе по умолчанию
        <label class="select">
         <select name="addthis_share_def">
          <option value="off" <?=$conf['addthis_share_def']=='off'?'selected':''?>>Скрыть</option>
          <option value="on" <?=$conf['addthis_share_def']=='on'?'selected':''?>>Показать</option>
         </select>
        </label>
        Кнопки «Follow» в системе по умолчанию
        <label class="select">
         <select name="addthis_follow_def">
          <option value="off" <?=$conf['addthis_follow_def']=='off'?'selected':''?>>Скрыть</option>
          <option value="on" <?=$conf['addthis_follow_def']=='on'?'selected':''?>>Показать</option>
         </select>
        </label>
        Превью-изображение <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Ссылка на изображение доступное из Интернета
или выбранное в менеджере файлов. Изображение
будет использовано по умолчанию во всех
вновь создаваемых материалах как привью на
материал в соцсетях и в списке материалов раздела.
Желательно выбирать изображение 1200х630.</pre><br>
        <label class="input inline width80">
         <input type="text" name="img_prev_def" id="img_prev_def" value="<?=htmlspecialchars($conf['img_prev_def'])?>">
        </label>
        <a href="#" class="fa-folder-open fa-lg blue" onclick="files('img_prev_def');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'img_prev_def')"></i>
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
         <select name="breadcrumbs[public]">
          <option value="on" <?=$conf['breadcrumbs']['public']=='on'?'selected':''?>>Показать «хлебные крошки»</option>
          <option value="off" <?=$conf['breadcrumbs']['public']=='off'?'selected':''?>>Скрыть «хлебные крошки»</option>
         </select>
        </label>
        <label class="checkbox inline" style="margin:0">
         <input type="checkbox" name="breadcrumbs[home]" <?=isset($conf['breadcrumbs']['home'])?'value="on" checked':''?>>
         <span class="custom-checkbox"><i class="icon-check"></i></span>
         Ссылка на главную
        </label>  <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Будет ли показана ссылка на страницу «Главная»
в начале цепочки «хлебных крошек».</pre>
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

 <?php if($conf['status']=='administrator'){$this->load->helper('back/back_user_control')?>
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
 site_mail:/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/,
 jq:/[^\s]/,
 gapi_key:/[^\s]/
};
</script>
