<h1><?="{$data['view_title']} [{$data['lang']}]"?></h1>
<div class="sheath">
 <form method="POST" action="/admin/home/edit/<?=$data['id']?>">
  <input type="hidden" name="last_mod_date" value="<?=date('Y-m-d')?>">
  <input type="hidden" name="lang" value="<?=$data['lang']?>">

<!--####### Title, description... #######-->
  <div class="touch">
   Заголовок страницы <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Должен быть информативным и емким,
содержать ключевые слова.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="input">
    <input type="text" name="title" placeholder="Заголовок страницы. Пример: Мой персональный веб-сайт" value="<?=htmlspecialchars($data['title'])?>" onkeyup="lim(this,100)">
   </label>
   Описание <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Краткое (до 250 символов) описание этой страницы
которое будет показано под заголовком (ссылкой)
в результатах поиска в Интернете
и разделах, подразделах сайта.
Должно быть информативным и емким,
содержать ключевые слова.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="textarea">
    <textarea name="description" class="no-emmet" placeholder="Описание страницы (description)" onkeyup="lim(this,250)" rows="3"><?=$data['description']?></textarea>
   </label>
  </div>

<!--####### JS, CSS, кнопки соцсетей... #######-->
  <div class="algn_r"><a href="#" onclick="opn_cls('more_opt');return false">Дополнительно (JS, CSS, кнопки соцсетей...) <i class="fa-angle-down"></i></a></div>
  <div id="more_opt" class="opn_cls touch">
   Индексация поисковыми роботами
   <label class="select">
    <select name="robots">
     <option value="all">Страница полностью доступна</option>
     <option value="index">Только индексировать страницу</option>
     <option value="follow">Только проходить по ссылкам на странице</option>
     <option value="noindex">Не индексировать страницу</option>
     <option value="nofollow">Не проходить по ссылкам на странице</option>
     <option value="noimageindex">Не индексировать изображения на странице</option>
     <option value="none">Страница полностью недоступна</option>
    </select>
   </label>
   <div class="row">
    <div class="col6">
     CSS-код <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
     <pre class="tt">
CSS-код с тегами style
который будет применен к этой странице.
Можно подгружать внешние таблицы стилей.</pre>
     <label class="textarea">
      <textarea name="css" class="emmet-syntax-css" placeholder="CSS-код с тегами <style> и </style>" rows="6"><?=$data['css']?></textarea>
     </label>
    </div>
    <div class="col6">
     JavaScript-код <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
     <pre class="tt">
JavaScript-код с тегами script
который будет применен к этой странице.
Можно подгружать внешние скрипты.</pre>
     <label class="textarea">
      <textarea name="js" class="no-emmet" placeholder="JavaScript-код с тегами <script> и </script>" rows="6"><?=$data['js']?></textarea>
     </label>
    </div>
   </div>
   <div class="row">
    <div class="col6">
     Кнопки «Share»
     <label class="select">
      <select name="addthis_share">
       <option value="off" <?php if($data['addthis_share']=='off') echo'selected'?>>Скрыть</option>
       <option value="on" <?php if($data['addthis_share']=='on') echo'selected'?>>Показать</option>
      </select>
     </label>
    </div>
    <div class="col6">
     Кнопки «Follow»
     <label class="select">
      <select name="addthis_follow">
       <option value="off" <?php if($data['addthis_follow']=='off') echo'selected'?>>Скрыть</option>
       <option value="on" <?php if($data['addthis_follow']=='on') echo'selected'?>>Показать</option>
      </select>
     </label>
    </div>
   </div>
  </div>

<!--####### Контент #######-->
  <div class="touch">
   <h3>Контент</h3>
   <hr>
   Ширина левого сегмента макета&nbsp;<input type="text" name="layout_l_width" class="layout_l_width_input" value="<?=htmlspecialchars($data['layout_l_width'])?>" size="3" onkeyup="$('#layout_l').css('width',($(this).val()-2)+'%')">&nbsp;%&nbsp;&nbsp;<a href="#" onclick="$('#layout_t,#layout_l,#layout_r,#layout_b').removeClass('nav_layout_active');return false">Kомпактно <i class="fa-compress"></i></a>&nbsp;&nbsp;<a href="#" onclick="opn_cls('o_makete');return false">О макете <i class="fa-angle-down"></i></a>
   <div id="o_makete" class="opn_cls">
С целью улучшения визуального восприятия и компактности основной части страницы, контент представлен в виде макета. Вы можете заполнить один и более сегментов.<br>Макет разделен на 4 сегмента, в каждом из которых Вы можете размещать и редактировать свой контент(содержание). Чтобы разместить или редактировать контент в одном из сегментов, выберите его, кликнув по нему мышкой.<br>Сегмент, который не содержит контент, не будет отображен на странице. Вы можете задать ширину левого сегмента в процентном отношении, к ширине макета на странице. Значение по умолчанию, для всех страниц, устанавливается в настройках макета. Еще, вы можете "компактно" собрать макет редактирования, чтобы вернуть его сегменты к прежнему виду.
   </div>
   <div style="margin-top:.5em">
    <div id="layout_t" class="nav_layout_t"><?=$data['layout_t']?></div>
    <div id="layout_l" class="nav_layout_l" style="width:<?=$data['layout_l_width']?>%"><?=$data['layout_l']?></div>
    <div id="layout_r" class="nav_layout_r"><?=$data['layout_r']?></div>
    <div id="layout_b" class="nav_layout_b"><?=$data['layout_b']?></div>
   </div>
  </div>

<!--####### Связанные ссылки #######-->
  <div class="touch">
   <?php
   $this->load->helper('back/links');
   links();
   ?>
  </div>

<!--####### Превью-изображение #######-->
  <div class="touch">
   <h3 class="float_l">Превью-изображение</h3> <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Введите в поле ниже ссылку на изображение
доступное из Интернета или выберите его
в менеджере файлов. Изображение будет
использовано как привью на страницу
«Главная» в соцсетях.</pre>
   <hr>
   Путь к файлу<br>
   <label class="input inline width90">
    <input type="text" name="img_prev" id="img_prev" value="<?=htmlspecialchars($data['img_prev'])?>">
   </label>
   <a href="#" class="fa-folder-open fa-lg blue" onclick="files('img_prev','<?=$data['lang']?>');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'img_prev')"></i>
   <pre class="tt"></pre>
  </div>

  <div class="button this_form_control">
   <button type="button" onclick="subm(form,s_opts)">Сохранить изменения</button><a href="/admin/home/get_list" class="btn_lnk">Отменить</a>
  </div>
 </form>
</div>

<script>
 var s_opts={//рег.выражения для проверки полей
  title:/[^\s]/,
  description:/[^\s]/
 };
 $(function(){
  ////////////////установка значений полей
  $('select[name="robots"] option[value="<?=$data['robots']?>"]').attr('selected',true);
 });
</script>
<?php $this->load->helper('back/redactor')?>
