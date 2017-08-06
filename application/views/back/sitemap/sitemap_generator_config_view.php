<?php
//Кнопка отправки формы не должна отправлятся (иметь имя), имена полей не менять, имя нового поля должно быть добавлено в
//таблицу бд (поле имя=бд поле имя, поле валуе=бд поле валуе).
?>
<h1>
<?=$conf_title?>
&nbsp;&nbsp;<i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
Здесь вы можете управлять настройками генератора
карты сайта, которая поможет поисковым машинам
сканировать страницы вашего сайта быстрее.
Генератор записывает URL-ы на материалы вашего
сайта в файл sitemap.xml.
Не должно быть более 50000 URL в одном файле
и его объем не должен привышать 10Мв.
Установите настройки и нажмите
«Сохранить настройки и обновить карту»
Карта сайта будет перезаписана учитывая
новые настройки конфигурации</pre>
</h1>
<div class="container">
<?php if(is_writable(getcwd().'/sitemap.xml')){//если sitemap.xml доступен для записи?>
 
<!--####### настройки карты сайта #######-->
<form method="POST" action="<?=base_url('admin/set_sitemap_config')?>" onsubmit="subm(this);return false">
 <div class="touch">
  <div class="row">
   <div class="col6">
    Генерировать карту сайта <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
При выборе «Автоматически» карта сайта будет
перезаписана всякий раз когда:
- меняется алиас (заголовок) материала
- меняется статус «опубликовать» в материале
- добавляется новый материал
- удаляется материал
Имейте в виду, что если на сайте очень много материалов, системе может
понадобится больше времени на обработку выше перечисленных действий.
При выборе «Вручную» карта сайта будет перезаписана
только если нажмете «Сохранить настройки и обновить карту»</pre>
    <label class="select">
     <select name="generate">
      <option value="auto">Автоматически</option>
      <option value="manually">Вручную</option>
     </select>  
    </label>    
   </div>
   <div class="col6">
    Включать в карту сайта <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
Вы можете выбрать, какие материалы
будут включены в карту сайта.</pre>
    <label class="select">
     <select name="allowed">
      <option value="all">Все материалы</option>
      <option value="public">Только опубликованные материалы</option>
     </select>
    </label>    
   </div>
  </div>
  <div class="button">
   <input type="submit" value="Сохранить настройки и обновить карту">
  </div>
 </div>
</form>
 
<!--####### обзор карты сайта #######--> 
<div class="touch">
 <div>
  <a href="#" onclick="opn_cls('sitemap_view');return false">Обзор карты сайта <i class="fa-angle-down"></i></a>&nbsp;&nbsp;&nbsp;
  <a href="<?= base_url('sitemap.xml')?>" target="_blank">Обзор в новой вкладке <i class="fa-external-link"></i></a>
 </div>
 <label id="sitemap_view" class="opn_cls full textarea">
  <textarea rows="20" readonly><?=file_get_contents(getcwd().'/sitemap.xml')?></textarea>
 </label>
</div>
 
<?php }else{//если sitemap.xml не доступен для записи?>
 <h3 class="red">Ошибка! Файл sitemap.xml не доступен для записи.</h3>
 <p>Установите права на запись (777) для файла sitemap.xml в корневой директории вашего сайта</p>
<?php }?>
</div>
<script>
$(function(){
 $('select[name="generate"] option[value="<?=$sitemap['generate']?>"]').attr('selected',true);
 $('select[name="allowed"] option[value="<?=$sitemap['allowed']?>"]').attr('selected',true);
})
</script>
