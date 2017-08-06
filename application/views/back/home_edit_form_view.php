<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять.
?>
<h1><?=$conf_title?></h1>
<div class="container">
 <form method="POST" action="<?=base_url('admin/home/edit')?>" onsubmit="subm(this,s_opts);return false">
  
<!--####### Title, description... #######-->
  <div class="touch">
   Заголовок страницы <i class="fa-info-circle red" onmouseover="tt(this);"></i>
   <pre class="tt">
Должен быть информативным и емким,
содержать ключевые слова.
<b class="red">Обязательно для заполнения!</b></pre>
   <label class="input">
    <input type="text" class="width90" name="title" placeholder="Заголовок страницы. Пример: Мой персональный веб-сайт" value="<?=$title?>" onkeyup="lim(this,100)">
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
    <textarea name="description" class="no-emmet width90" placeholder="Описание страницы (description)" onkeyup="lim(this,250)" rows="3"><?=$description?></textarea>
   </label>
  </div>

<!--####### JS, CSS, кнопки соцсетей... #######-->
  <div class="algn_r"><a href="#" onclick="opn_cls('more_opt');return false">Дополнительно (JS, CSS, кнопки соцсетей...) <i class="fa-angle-down"></i></a></div>
  <div id="more_opt" class="opn_cls touch">
   Для индексации поисковыми роботами
   <label class="select">
    <select name="robots">
     <option value="all">страница полностью доступна</option>
     <option value="index">ТОЛЬКО индексировать страницу</option>
     <option value="follow">ТОЛЬКО проходить по ссылкам на странице</option>
     <option value="noindex">НЕ индексировать страницу</option>
     <option value="nofollow">НЕ проходить по ссылкам на странице</option>
     <option value="noimageindex">НЕ индексировать изображения на странице</option>
     <option value="none">страница полностью НЕ доступна</option>
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
      <textarea name="css" class="emmet-syntax-css" placeholder="CSS-код с тегами <style> и </style>" rows="6"><?=$css?></textarea>
     </label>
    </div>
    <div class="col6">
     JAVASCRIPT-код <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
     <pre class="tt">
JAVASCRIPT-код без тегов script
который будет применен к этой странице.
Можно подгружать внешние скрипты</pre>
     <label class="textarea">
      <textarea name="js" class="no-emmet" placeholder="JAVASCRIPT-код с тегами <script> и </script>" rows="6"><?=$js?></textarea>
     </label>
    </div>
   </div>
   <div class="row">
    <div class="col6">
     Кнопки «Share»
     <label class="select">
      <select name="addthis_share">
       <option value="off" <?php if($addthis_share=='off') echo'selected'?>>скрыть</option>
       <option value="on" <?php if($addthis_share=='on') echo'selected'?>>показать</option>
      </select>
     </label>
    </div>
    <div class="col6">
     Кнопки «Follow»
     <label class="select">
      <select name="addthis_follow">
       <option value="off" <?php if($addthis_follow=='off') echo'selected'?>>скрыть</option>
       <option value="on" <?php if($addthis_follow=='on') echo'selected'?>>показать</option>
      </select>
     </label>
    </div>
   </div>
  </div>

<!--####### Контент #######-->
  <div class="touch">
   <h3>Контент</h3>
   <hr>
   Ширина левого сегмента макета&nbsp;<input type="text" name="layout_l_width" class="layout_l_width_input" value="<?=$layout_l_width?>" size="3" onkeyup="$('#layout_l').css('width',($(this).val()-2)+'%')">&nbsp;%&nbsp;&nbsp;<a href="#" onclick="$('#layout_t,#layout_l,#layout_r,#layout_b').removeClass('nav_layout_active');return false">Kомпактно <i class="fa-compress"></i></a>&nbsp;&nbsp;<a href="#" onclick="opn_cls('o_makete');return false">О макете <i class="fa-angle-down"></i></a>
   <div id="o_makete" class="opn_cls">
С целью улучшения визуального восприятия и компактности основной части страницы, контент представлен в виде макета. Вы можете заполнить один и более сегментов.<br>Макет разделен на 4 сегмента, в каждом из которых Вы можете размещать и редактировать свой контент(содержание). Чтобы разместить или редактировать контент в одном из сегментов, выберите его, кликнув по нему мышкой.<br>Сегмент, который не содержит контент, не будет отображен на странице. Вы можете задать ширину левого сегмента в процентном отношении, к ширине макета на странице. Значение по умолчанию, для всех страниц, устанавливается в настройках макета. Еще, вы можете "компактно" собрать макет редактирования, чтобы вернуть его сегменты к прежнему виду.
   </div>
   <div style="margin-top:.5em">
    <div id="layout_t" class="nav_layout_t"><?=$layout_t?></div>
    <div id="layout_l" class="nav_layout_l" style="width:<?=$layout_l_width?>%"><?=$layout_l?></div>
    <div id="layout_r" class="nav_layout_r"><?=$layout_r?></div>
    <div id="layout_b" class="nav_layout_b"><?=$layout_b?></div>
   </div>
  </div>

<!--####### Связанные ссылки #######-->
  <div class="touch">
   <?php
   $this->load->helper('back/links');
   links($links);
   ?>
  </div>

<!--####### Превью для соцсетей #######-->
  <div class="touch">
   <h3 class="float_l">Превью для соцсетей</h3> <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
   <pre class="tt">
Введите в поле ниже ссылку на изображение
доступное из Интернета или выберите его
в менеджере файлов. Изображение будет
использовано как привью на главную
страницу в соцсетях.</pre>
   <hr>
   Путь к файлу<br>
   <label class="input inline width90">
    <input type="text" name="img_prev" id="img_prev" value="<?=$img_prev?>">
   </label>
   <a href="#" class="fa-folder-open fa-lg blue" onclick="files('img_prev');return false"></a>&nbsp;<i class="fa-eye fa-lg blue" onmouseover="img_prev(this,'img_prev')"></i>
   <pre class="tt"></pre>
  </div>

  <div class="button">
   <input type="submit" value="Сохранить изменения"><a href="#" class="btn_lnk" onclick="window.history.back();return false">Отменить</a>
  </div>
 </form>
</div>
<script>
 var s_opts={//рег.выражения для проверки полей
  title:/^[^><]+$/i,
  description:/^[^><]+$/i
 };
 $(function(){
  ////////////////установка значений полей
  $('select[name="robots"] option[value="<?=$robots?>"]').attr('selected',true);
 });
</script>
