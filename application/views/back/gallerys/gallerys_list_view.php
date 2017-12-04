<h1><?=$conf_title?></h1>

<!--####### Настройки вывода, поиск, иные опции #######-->
<form id="filter" class="container" method="GET" action="<?=current_url()?>">
 <div class="button algn_r">
  <a href="<?=base_url('admin/gallery/add_form')?>" class="btn_lnk">Добавить галерею</a>
 </div>
 <div class="row">
  <div class="col6">
   Сортировать
   <label class="select">
    <select name="order" onchange="submit()">
     <option value="id">по идентификатору</option>
     <option value="alias">по алиасу</option>
     <option value="title">по заголовку</option>
     <option value="section">по разделу</option>
     <option value="public">по публикации</option>
    </select>
   </label>
  </div>
  <div class="col6">
   Выводить записей
   <label class="select">
    <select name="pag_per_page" onchange="submit()">
     <option value="20">20</option>
     <option value="50">50</option>
     <option value="100">100</option>
     <option value="300">300</option>
     <option value="500">500</option>
     <option value="1000">1000</option>
     <option value="all">все</option>
    </select>
   </label>
  </div>
 </div>
 <div class="row">
  <div class="col4">
   Контекст поиска
   <label class="select">
    <select name="context_search">
     <option value="title">заголовок</option>
     <option value="alias">алиас</option>
     <option value="description">описание</option>
     <option value="content">контент</option>
    </select>
   </label>
  </div>
  <div class="col8">
   Искать в контексте
   <label class="search">
    <input type="text" name="search" placeholder="строка запроса">
    <a href="#" class="btn_lnk" onclick="form.submit();return false">Поиск</a>
   </label>
  </div>
 </div>
</form>

<?php if(empty($gallerys)){?>
<div class="container">
 <p>Ничего не найдено. Запрос не дал результатов..(</p>
</div>
<?php }else{?>

<!--####### Таблица записей #######-->
<table class="tabl order-table">
 <thead>
  <tr>
   <td>Заголовок</td>
   <td>Алиас</td>
   <td>Раздел</td>
   <td>Действия</td>
  </tr>
 </thead>
 <tbody>
  <?php 
  $this->load->helper('back/section_alias_to_title');
  foreach($gallerys as $item){?>
  <tr>
   <td><?=$item['title']?></td>
   <td><?=$item['alias']?></td>
   <td><?php section_alias_to_title($item['section'])?></td>
   <td>
    <span><a href="#" class="<?php if($item['public']=='on'){echo'fa-eye green';}else{echo'fa-eye-slash red';}?>" title="Опубликовать/не опубликовывать" onclick="toggle_public(this,<?=$item['id']?>,'<?=$prefix?>gallerys','<?=$item['public']?>');return false"></a></span>&nbsp;&nbsp;
    <a href="<?=base_url('admin/gallery/edit_form/'.$item['id'])?>" class="fa-edit green" title="Редактировать"></a>&nbsp;&nbsp;
    <a href="<?=base_url('gallery/'.$item['alias'])?>" class="fa-external-link" target="_blank" title="Смотреть на сайте"></a>&nbsp;&nbsp;
    <a href="#" class="fa-trash-o red" title="Удалить" onclick="del_gallery(this,'<?=$item['alias']?>');return false"></a>
   </td>
  </tr>
  <?php }?>
 </tbody>
</table>

<!--####### Постраничная навигация #######-->
<?=$this->pagination->create_links()?>
<?php }?>

<?php
//устанавливаю значеня полей фильтра
$def['order']=!$this->input->get('order')?'id':$this->input->get('order');
$def['pag_per_page']=!$this->input->get('pag_per_page')?$this->session->userdata('pag_per_page'):$this->input->get('pag_per_page');
$def['context_search']=!$this->input->get('context_search')?'title':$this->input->get('context_search');
$def['search']=($this->input->get('search')==='')?'':$this->input->get('search');
?>
<script>
//устанавливаю значеня полей фильтра
var form=$('#filter');
$(function(){
 form.find('select[name="order"] option[value="<?=$def['order']?>"]').attr('selected',true);
 form.find('select[name="pag_per_page"] option[value="<?=$def['pag_per_page']?>"]').attr('selected',true);
 form.find('select[name="context_search"] option[value="<?=$def['context_search']?>"]').attr('selected',true);
 form.find('input[name="search"]').val('<?=$def['search']?>');
});
</script>
