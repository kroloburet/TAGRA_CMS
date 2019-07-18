<h1><?=$data['view_title']?></h1>
<!--####### Настройки вывода, поиск, иные опции #######-->
<form id="filter" class="sheath" method="GET" action="<?=current_url()?>">
 <div class="row">
  <div class="col6">
   Сортировать
   <label class="select">
    <select name="order" onchange="submit()">
     <option value="id">По идентификатору</option>
     <option value="creation_date">По дате создания</option>
     <option value="last_mod_date">По дате изменения</option>
     <option value="title">По заголовку</option>
     <option value="lang">По языку</option>
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
     <option value="all">Все</option>
    </select>
   </label>
  </div>
 </div>
 <div class="row">
  <div class="col4">
   Контекст поиска
   <label class="select">
    <select name="context_search">
     <option value="title">Заголовок</option>
     <option value="description">Описание</option>
     <option value="content">Контент</option>
     <option value="lang">Язык</option>
    </select>
   </label>
  </div>
  <div class="col8">
   Искать в контексте
   <label class="search">
    <input type="text" name="search" placeholder="Строка запроса">
    <a href="#" class="btn_lnk" onclick="form.submit();return false">Поиск</a>
   </label>
  </div>
 </div>
</form>

<?php if(empty($data['home_pages'])){?>
<div class="sheath">
 <p>Ничего не найдено. Запрос не дал результатов..(</p>
</div>
<?php }else{?>

<!--####### Таблица записей #######-->
<table class="tabl order-table">
 <thead>
  <tr>
   <td>Заголовок</td>
   <td>Язык</td>
   <td>Действия</td>
  </tr>
 </thead>
 <tbody>
  <?php foreach($data['home_pages'] as $v){?>
  <tr>
   <td><?=mb_strimwidth($v['title'],0,40,'...')?></td>
   <td><?=$v['lang']?></td>
   <td>
    <a href="/admin/home/edit_form/<?=$v['id']?>" class="fa-edit green" title="Редактировать"></a>&nbsp;&nbsp;
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
$def['search']=($this->input->get('search')==='')?'':addslashes($this->input->get('search'));
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
