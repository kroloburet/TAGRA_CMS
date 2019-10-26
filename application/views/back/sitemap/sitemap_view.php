<h1><?=$data['view_title']?></h1>
<?php if(is_writable(getcwd().'/sitemap.xml')){//sitemap.xml доступен для записи?>
 <dl class="tabs">
  <dt class="tab_active">Обзор</dt>
  <dd>
   <div class="tab_content">

    <!--####### обзор карты сайта #######-->
    <a href="/sitemap.xml" target="_blank">Обзор в новой вкладке <i class="fa-external-link"></i></a>
    <label class="full textarea">
     <textarea rows="20" readonly><?=file_get_contents(getcwd().'/sitemap.xml')?></textarea>
    </label>
   </div>
  </dd>
  <dt>Настройки</dt>
  <dd>
   <div class="tab_content">

    <!--####### настройки карты сайта #######-->
    <form method="POST" action="/admin/set_sitemap_config">
     <div class="row touch">
      <div class="col6">
       Генерировать карту сайта <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
  При выборе <q>Автоматически</q> карта сайта будет
  перезаписана всякий раз когда:
  - изменена публикация материала
  - добавлен материал
  - удален материал
  При выборе <q>Вручную</q> карта сайта будет перезаписана
  только если нажмете <q>Сохранить настройки и обновить карту</q></pre>
       <label class="select">
        <select name="generate">
         <option value="auto">Автоматически</option>
         <option value="manually">Вручную</option>
        </select>
       </label>
      </div>
      <div class="col6">
       Включать в карту сайта <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
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
     <div class="button this_form_control">
      <button type="button" onclick="subm(form)">Сохранить и обновить карту</button>
     </div>
    </form>
   </div>
  </dd>
 </dl>

<?php }else{//sitemap.xml не доступен для записи?>
 <div class="sheath">
  <h2 class="red">Ошибка! Файл sitemap.xml не доступен для записи.</h2>
  <p>Установите права на запись (644) для файла sitemap.xml в корневой директории вашего сайта</p>
 </div>
<?php }?>

<script>
 $(function(){
  //установить значеня полей
  $('select[name="generate"] option[value="<?=$conf['sitemap']['generate']?>"]').attr('selected',true);
  $('select[name="allowed"] option[value="<?=$conf['sitemap']['allowed']?>"]').attr('selected',true);
 });
</script>