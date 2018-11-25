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
        E-mail с сайта <i class="fa-question-circle red" onmouseover="tt(this);"></i>
        <pre class="tt">
E-mail на который будут приходить
письма от пользователей вашего сайта.
Например: со страницы «Контакты».
<b class="red">Обязательно для заполнения!</b></pre>
        <label class="input">
         <input type="text" name="conf_site_mail" value="<?=$conf_site_mail?>">
        </label>
        Путь к библиотеке JQuery <i class="fa-question-circle red" onmouseover="tt(this,'c');"></i>
        <pre class="tt">
JQuery — подключаемый скрипт для правильной
работы всего сайта. Подробную информацию и
ссылку для подключения актуальной версии
можно получить на странице <a href="http://code.jquery.com/" target="_blank">jQuery CDN</a>
<b class="red">Обязательно для заполнения!</b></pre>
        <label class="input">
         <input type="text" name="conf_jq" value="<?=$conf_jq?>">
        </label>
        Ключ Google map API <i class="fa-question-circle red" onmouseover="tt(this);"></i>
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

<!--####### Настройки администратора #######-->
  <?php if($conf_status=='administrator'){?>
   <dt>Администратор</dt>
   <dd>
    <div class="tab_content">
     <p>Администратор — это статус с полными правами доступа к административной части сайта. Он может управлять главными (конфигурационными) настройками сайта: назначать модератора, устанавливать пароли и так далее. Администратор в системе может быть только один. Если Вы сейчас читаете эти строки, значит у Вас полные права доступа и администратор — это Вы.</p>
     <form id="edit_a_form">
      Новый логин <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
      <pre class="tt">
Оставьте поле пустым если
не хотите менять логин.
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin</pre>
      <label class="input">
       <input type="text" name="login">
      </label>
      Новый пароль <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
    <pre class="tt">
Оставьте поле пустым если
не хотите менять пароль.
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генирируйте его!</b></pre>
      <a href="#" onclick="gen_pass('admin_pass');return false" class="fa-refresh" title="Генерировать новый пароль"></a>
      <label class="input">
       <input type="text" name="password" id="admin_pass">
      </label>
      E-mail <i class="fa-info-circle red" onmouseover="tt(this);"></i>
    <pre class="tt">
На этот е-mail будут высланы логины
и пароли, если Вы их забудите.
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
      <label class="input">
       <input type="text" name="email" value="<?=$conf_admin_mail?>">
      </label>
      <div class="_msg"></div>
      <div class="button">
       <button type="button" onclick="edit_administrator()">Сохранить</button>
      </div>
     </form>
    </div>
   </dd>

<!--####### Настройки модераторов #######-->
   <dt>Модераторы</dt>
   <dd>
    <div class="tab_content">
     <p>Модератор — это статус с ограниченными правами доступа к административной части сайта, например: он не может назначать модераторов и администратора, изменять их логин, пароль. Модератор может: добавлять, изменять, удалять страницы сайта, управлять меню и так далее.</p>
     <!--добавить модератора-->
     <div class="touch">
      <h2>Добавить модератора</h2>
      <hr>
      <form id="add_m_form">
       Логин <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
Строка длиной 5-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: Va$ya_Pupkin
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="login">
       </label>
       Пароль <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
Строка длиной 8-20 символов
которыми могут быть строчные,
прописные латинские буквы,
цифры, специальные символы.
Пример: o4-slOjniY
<b class="red">Используйте сложный пароль,
или генирируйте его!
Обязательно для заполнения!</b></pre>
       <a href="#" onclick="gen_pass('moderator_pass');return false" class="fa-refresh" title="Генерировать пароль"></a>
       <label class="input">
        <input type="text" name="password" id="moderator_pass">
       </label>
       E-mail <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="email">
       </label>
       <div class="_msg"></div>
       <div class="button">
        <button type="button" onclick="add_moderator()">Добавить модератора</button>
       </div>
      </form>
     </div>
     <!--список модераторов-->
     <?php foreach($moderators as $v){//перебор модераторов?>
     <div class="touch" id="moderator_item_<?=$v['id']?>">
      <h3>
       <i class="fa-user"></i>&nbsp;<?=$v['email']?>&nbsp;&nbsp;&nbsp;
       <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
ID: <?=$v['id'].PHP_EOL?>
Дата добавления: <?=$v['register_date'].PHP_EOL?>
Дата изменения: <?=$v['last_mod_date'].PHP_EOL?>
Дата последней авторизации: <?=$v['last_login_date'].PHP_EOL?></pre>
       <a href="#" class="fa-edit green" title="Редактировать" onclick="opn_cls('edit_m_form_<?=$v['id']?>');return false"></a>
       <a href="#" class="fa-trash-o red" title="Удалить"onclick="del_moderator('<?=$v['id']?>');return false"></a>
      </h3>
      <form class="opn_cls" id="edit_m_form_<?=$v['id']?>">
       <hr>
       Новый логин <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Оставьте поле пустым если
не хотите менять логин</pre>
       <label class="input">
        <input type="text" name="login">
       </label>
       Новый пароль <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Оставьте поле пустым если
не хотите менять пароль</pre>
       <a href="#" onclick="gen_pass('moderator_pass_<?=$v['id']?>');return false" class="fa-refresh" title="Генерировать новый пароль"></a>
       <label class="input">
        <input type="text" name="password" id="moderator_pass_<?=$v['id']?>">
       </label>
       E-mail <i class="fa-info-circle red" onmouseover="tt(this);"></i>
       <pre class="tt">
Указывайте надежный почтовый ящик.
<b class="red">Обязательно для заполнения!</b></pre>
       <label class="input">
        <input type="text" name="email" value="<?=$v['email']?>">
       </label>
       <div class="_msg"></div>
       <div class="button">
        <button type="button" onclick="edit_moderator('<?=$v['id']?>')">Сохранить</button>
        <button type="button" onclick="opn_cls('edit_m_form_<?=$v['id']?>')">Отмена</button>
       </div>
      </form>
     </div>
     <?php }?>
    </div>
   </dd>
<script>
///////////////////////////////////////////////////редактировать администратора
function edit_administrator(){
 var form=$('#edit_a_form');
 var btn=form.find('button');
 var msg=form.find('._msg');
 var email=form.find('[name="email"]');
 form.find('input').removeClass('novalid');
 if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(email.val())){alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');email.addClass('novalid');return false;}
 btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
 $.ajax({
   url: '<?=base_url('admin/setting/edit_administrator')?>',//путь к скрипту, который обработает запрос
   type: 'post',
   data: form.serialize(),
   dataType: 'text',
   success: function(data){//обработка ответа
    switch(data){
     //админ не записан в базу
     case 'error':msg.html('<div class="notific_r">Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.</div>');
      btn.attr('disabled',false).html('Сохранить');
      break;
     //пароль и логин не уникальны
     case 'nounq':msg.html('<div class="notific_r">В системе уже есть пользователь с такими данными!</div>');
      btn.attr('disabled',false).html('Сохранить');
      break;
     //все пучком
     case 'ok':btn.remove();
      msg.html('<div class="notific_g">Администратор успешно изменен!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обновление страницы...</div>');
      setTimeout(function(){location.reload();},3000);
      break;
     //ошибки сценария сервера
     default :msg.html('<div class="notific_b">'+data+'</div>');
      btn.attr('disabled',false).html('Сохранить');
      break;
    }
   }
 });
}
///////////////////////////////////////////////////добавить модератора
function add_moderator(){
 var form=$('#add_m_form');
 var btn=form.find('button');
 var msg=form.find('._msg');
 var login=form.find('[name="login"]');
 var pass=form.find('[name="password"]');
 var email=form.find('[name="email"]');
 form.find('input').removeClass('novalid');
 if(!/\S/.test(login.val())){alert('Поле "Логин" не заполнено!');login.addClass('novalid');return false;}
 if(!/\S/.test(pass.val())){alert('Поле "Пароль" не заполнено!');pass.addClass('novalid');return false;}
 if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(email.val())){alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');email.addClass('novalid');return false;}
 btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
 $.ajax({
   url: '<?=base_url('admin/setting/add_moderator')?>',//путь к скрипту, который обработает запрос
   type: 'post',
   data: form.serialize(),
   dataType: 'text',
   success: function(data){//обработка ответа
    switch(data){
     //модератор не записан в базу
     case 'error':msg.html('<div class="notific_r">Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.</div>');
      btn.attr('disabled',false).html('Добавить модератора');
      break;
     //пароль и логин не уникальны
     case 'nounq':msg.html('<div class="notific_r">В системе уже есть пользователь с такими данными!</div>');
      btn.attr('disabled',false).html('Добавить модератора');
      break;
     //все пучком
     case 'ok':btn.remove();
      msg.html('<div class="notific_g">Модератор успешно добавлен!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обновление страницы...</div>');
      setTimeout(function(){location.reload();},3000);
      break;
     //ошибки сценария сервера
     default :msg.html('<div class="notific_b">'+data+'</div>');
      btn.attr('disabled',false).html('Добавить модератора');
      break;
    }
   }
 });
}
///////////////////////////////////////////////////редактировать модератора
function edit_moderator(id){
 var form=$('#edit_m_form_'+id);
 var btn=form.find('.button');
 var btn_html=btn.html();
 var msg=form.find('._msg');
 var email=form.find('[name="email"]');
 form.find('input').removeClass('novalid');
 if(!/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(email.val())){alert('В поле "E-mail" недопустимый символ либо оно не заполнено!');email.addClass('novalid');return false;}
 btn.html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
 $.ajax({
   url: '<?=base_url('admin/setting/edit_moderator')?>/'+id,//путь к скрипту, который обработает запрос
   type: 'post',
   data: form.serialize(),
   dataType: 'text',
   success: function(data){//обработка ответа
    switch(data){
     //модератор не записан в базу
     case 'error':msg.html('<div class="notific_r">Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.</div>');
      btn.html(btn_html);
      break;
     //пароль и логин не уникальны
     case 'nounq':msg.html('<div class="notific_r">В системе уже есть пользователь с такими данными!</div>');
      btn.html(btn_html);
      break;
     //все пучком
     case 'ok':btn.remove();
      msg.html('<div class="notific_g">Модератор успешно изменен!<br><i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обновление страницы...</div>');
      setTimeout(function(){location.reload();},3000);
      break;
     //ошибки сценария сервера
     default :msg.html('<div class="notific_b">'+data+'</div>');
      btn.html(btn_html);
      break;
    }
   }
 });
}
///////////////////////////////////////////////////удалить модератора
function del_moderator(id){
 if(!confirm('Модератор будет безвозвратно удален!\nВыполнить действие?')){return false;}
 var item=$('#moderator_item_'+id);
 $.ajax({
   url: '<?=base_url('admin/setting/del_moderator')?>',//путь к скрипту, который обработает запрос
   type: 'post',
   data: {id:id},
   dataType: 'text',
   success: function(data){//обработка ответа
    switch(data){
     //модератор не удален
     case 'error':alert('Ой! Ошибка..(\nВозможно это временные неполадки, попробуйте снова.');break;
     //последний модератор
     case 'last':alert('В системе должен быть один или больше модераторов!');break;
     //все пучком
     case 'ok':item.remove();break;
     //ошибки сценария сервера
     default :alert(data);break;
    }
   }
 });
}
</script>
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
