<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять, имя нового поля должно быть добавлено в
//таблицу бд (поле имя=бд поле имя, поле валуе=бд поле валуе).
?>
<h1><?=$conf_title?></h1>
 <dl class="tabs">
  
<!--####### Основные настройки #######-->
  <dt class="tab_active">Основные настройки</dt>
  <dd>
   <form method="POST" action="<?=base_url('admin/setting/set_my_config')?>" onsubmit="subm(this);return false">
    <div class="tab_content">
     <p>— Это панель настроек конфигурации. Здесь Вы можете управлять главными настройками сайта.<br>Редактируя настройки Вы можете использовать кириллицу, но рекомендуется использовать латинские символы.<br>Не забывайте сохранять настройки после изменения.</p>
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
когда вы ведете технические работы на сайте.
Установите «Доступ к сайту закрыт» —
посетителям вашего сайта будет показана страница
(plug.html) с соответствующим сообщением.</pre>
        <label class="select">
         <select name="conf_site_access">
          <option value="on" <?php if($conf_site_access=='on') echo'selected'?>>Доступ к сайту открыт</option>
          <option value="off" <?php if($conf_site_access=='off') echo'selected'?>>Доступ к сайту закрыт</option>
         </select> 
        </label>
        Имя сайта
        <label class="input">
         <input type="text" name="conf_site_name" value="<?=$conf_site_name?>">
        </label>
        E-mail с сайта <i class="fa-question-circle red" onmouseover="tt(this);"></i>
        <pre class="tt">
E-mail на который будут приходить
письма от пользователей вашего сайта.
Например: со страницы «Контакты».
<b class="red">Обязательно для заполнения!</b></pre>
        <label class="input">
         <input type="email" name="conf_site_mail" value="<?=$conf_site_mail?>">
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
       </div>
       
       <!--####### Уведомления #######-->
       <div class="row touch">
        <h3>Уведомления</h3>
        <hr>
        Уведомления о новых комментариях <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
        <pre class="tt">
Выберите e-mail на который система будет
отправлять уведомления о новых комментариях
к материалам, или если комментарии нуждаются в
премодерации перед публикацией.</pre>
        <label class="select">
         <select name="conf_comment_notific">
          <option value="off">Не уведомлять и публиковать по умолчанию</option>
          <optgroup label="Без премодерации">
           <option value="site_mail">На e-mail сайта</option>
           <option value="admin_mail">На e-mail администратора</option>
           <option value="moderator_mail">На e-mail модератора</option>
          </optgroup>
          <optgroup label="С премодерацией">
           <option value="premod_site_mail">На e-mail сайта</option>
           <option value="premod_admin_mail">На e-mail администратора</option>
           <option value="premod_moderator_mail">На e-mail модератора</option>
          </optgroup>
         </select>
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
        Ширина левой колонки макета (в процентах)
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
          <option value="on" <?php if($conf_emmet=='on') echo'selected'?>>Emmet включен</option>
          <option value="off" <?php if($conf_emmet=='off') echo'selected'?>>Emmet выключен</option>
         </select>
        </label>
       </div>
      </div>
     </div>

 <!--####### Кнопки соцсетей #######-->
     <div class="touch">
      <h3 class="nowrap float_l">Кнопки «поделиться»</h3> <span><i class="fa-question-circle blue" onmouseover="tt(this,'c');"></i>
       <pre class="tt">
Сервис <a href="https://www.addthis.com" target="_blank">AddThis</a> предоставляет возможность после регистрации
размещать на страницах вашего сайта кнопки социальных сетей,
с помощью которых посетители вашего сайта могут поделиться
информацией о вашем сайте или странице в своих соц-сетях.
А так же, они могут посетить ваши странички соц-сетей.
— Это хороший способ рассказать о себе и своем сайте.</pre>
       <a href="#" onclick="opn_cls('addthis_opt');
       return false">Настройки <i class="fa-angle-down"></i></a>
      </span>
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
       <div class="row">
        <div class="col6">
         Кнопки «Share» в системе по умолчанию
         <label class="select">
          <select name="conf_addthis_share_def">
           <option value="off" <?php if($conf_addthis_share_def=='off') echo'selected'?>>скрыть</option>
           <option value="on" <?php if($conf_addthis_share_def=='on') echo'selected'?>>показать</option>
          </select>
         </label>
        </div>
        <div class="col6">
         Кнопки «Follow» в системе по умолчанию
         <label class="select">
          <select name="conf_addthis_follow_def">
           <option value="off" <?php if($conf_addthis_follow_def=='off') echo'selected'?>>скрыть</option>
           <option value="on" <?php if($conf_addthis_follow_def=='on') echo'selected'?>>показать</option>
          </select>
         </label>        
        </div>
       </div>      
      </div>
     </div>

 <!--####### Навигация «хлебные крошки» #######-->
     <div class="touch">
      <h3 class="nowrap float_l">Навигация «хлебные крошки»</h3><span>
       <a href="#" onclick="opn_cls('bc_opt');return false">Настройки <i class="fa-angle-down"></i></a>
      </span>
      <hr>
      <div id="bc_opt" class="opn_cls">
       <label class="select">
        <select name="conf_breadcrumbs_public">
         <option value="on" <?php if($conf_breadcrumbs_public=='on') echo'selected'?>>Показать «хлебные крошки»</option>
         <option value="off" <?php if($conf_breadcrumbs_public=='off') echo'selected'?>>Скрыть «хлебные крошки»</option>
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
     <div class="button">
      <input type="submit" value="Сохранить все настройки">
     </div>
    </div>
   </form>
  </dd>
  
<!--####### Настройки администратора #######-->
  <?php if($conf_status==='administrator'){?>
   <dt>Администратор</dt>
   <dd>
    <form method="POST" action="<?=base_url('admin/setting/set_user')?>" onsubmit="subm(this);return false">
     <div class="tab_content">
      <p>Администратор — это статус с полными правами доступа к административной части сайта. Он может управлять главными (конфигурационными) настройками сайта: назначать модератора, устанавливать пароли и так далее. Если Вы сейчас читаете эти строки, значит у Вас полные права доступа и администратор — это Вы.</p>
      Новый логин администратора <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
      <pre class="tt">
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin</pre>
      <label class="input">
       <input type="text" name="admin_login" value="">
      </label>
      Новый пароль администратора <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генирируйте его!</b></pre>
      <a href="#" onclick="gen_pass('admin_pass');return false" class="fa-refresh" title="Генерировать новый пароль"></a>
      <label class="input">
       <input type="text" name="admin_pass" id="admin_pass" value="">
      </label>
      E-mail администратора <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
На этот е-mail будут высланы логины
и пароли, если Вы их забудите.
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
      <label class="input">
       <input type="email" name="admin_mail" value="<?=$conf_admin_mail?>">
      </label>
      <div class="button">
       <input type="submit" value="Сохранить настройки администратора">
      </div>
     </div>
    </form>
   </dd>
   
<!--####### Настройки модератора #######-->
   <dt>Модератор</dt>
   <dd>
    <form method="POST" action="<?=base_url('admin/setting/set_user')?>" onsubmit="subm(this);return false">
     <div class="tab_content">
      <p>Модератор — это статус с неполными правами доступа к административной части сайта. Ему не доступны главные (конфигурационные) настройки сайта но он имеет доступ к остальной административной части сайта. Модератор может добавлять, изменять, удалять страницы сайта, управлять меню и так далее.</p>
      Новый логин модератора <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin</pre>
      <label class="input">
       <input type="text" name="moder_login" value="">
      </label>
      Новый пароль модератора <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
      <pre class="tt">
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генирируйте его!</b></pre>
      <a href="#" onclick="gen_pass('moderator_pass');return false" class="fa-refresh" title="Генерировать новый пароль"></a>
      <label class="input">
       <input type="text" name="moder_pass" id="moderator_pass" value="">
      </label>
      E-mail модератора <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
Даже если модератор не назначен,
все равно заполите это поле.
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
      <label class="input">
       <input type="email" name="moder_mail" value="<?=$conf_moderator_mail?>">
      </label>
      <div class="button">
       <input type="submit" value="Сохранить настройки модератора">
      </div>
     </div>
    </form>
   </dd>
  <?php }?>
 </dl>
 
<script>
////////////////////////////////////////////////рег.выражения для проверки полей
 var s_opts={
  conf_site_mail:/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/,
  conf_jq:/[^\s]/
 };
$(function(){
 ///////////////////////////////////////////////установка значений полей
 $('select[name="conf_comment_notific"] option[value="<?=$conf_comment_notific?>"]').attr('selected',true);
});
</script>
