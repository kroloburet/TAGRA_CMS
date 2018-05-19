<h1><?=$conf_title?></h1>

<?php if(!empty($new_comments)){?>
<!--####### Новые комментарии #######-->
<div class="sheath">
 <h3>Новые комментарии</h3>
 <hr>
 <?php foreach($new_comments as $item){?>
  <div class="touch">
   <div class="float_l"><i class="fa-user"></i>&nbsp;<b><?=$item['name']?></b></div>
   <div class="algn_r">
    <a href="<?=base_url('admin/comment/public_new/'.$item['id'])?>" class="fa-check-circle green" title="Опубликовать комментарий"></a>&nbsp;&nbsp;
    <a href="<?=base_url('admin/comment/del_new/'.$item['id'])?>" class="fa-trash-o red" title="Удалить комментарий" onclick="if(!confirm('Комментарий будет удален!\nВыполнить действие?')){return false;}"></a>&nbsp;&nbsp;
      <a href="<?=base_url($item['url'])?>" target="_blank" class="fa-external-link" title="Перейти на страницу"></a>&nbsp;&nbsp;
    <span class="blue fa-info-circle" onmouseover="tt(this);"></span>
    <pre class="tt">
 <b>Дата: </b><?=$item['date'].PHP_EOL?>
 <b>URL: </b>/<?=$item['url']?>
    </pre>
   </div>
   <div><sup class="fa-quote-left"></sup>&nbsp;&nbsp;<?=$item['comment']?>&nbsp;&nbsp;<sub class="fa-quote-right"></sub></div>
  </div>
 <?php }?>
</div>
<?php }?>

<!--####### Настройки вывода, поиск, иные опции #######-->
<form id="filter" class="sheath" method="GET" action="<?=current_url()?>">
 <div class="row">
  <div class="col6">
   Сортировать
   <label class="select">
    <select name="order" onchange="submit()">
     <option value="id">по идентификатору</option>
     <option value="name">по имени комментатора</option>
     <option value="date">по дате</option>
     <option value="url">по URL</option>
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
     <option value="comment">текст комментария</option>
     <option value="name">имя комментатора</option>
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

<?php if(empty($comments)){?>
<div class="sheath">
 <p>Ничего не найдено. Запрос не дал результатов..(</p>
</div>
<?php }else{?>

<!--####### Таблица записей #######-->
<table class="tabl order-table">
 <thead>
  <tr>
   <td>Дата</td>
   <td>Комментатор</td>
   <td>URL</td>
   <td>Действия</td>
  </tr>
 </thead>
 <tbody>
  <?php foreach($comments as $item){?>
  <tr>
   <td><?=$item['date']?></td>
   <td><?=$item['name']?></td>
   <td>/<?=$item['url']?></td>
   <td>
    <a href="<?=base_url($item['url'])?>" target="_blank" class="fa fa-external-link" title="Перейти на страницу"></a>&nbsp;&nbsp;
    <span class="blue fa fa-info-circle" onmouseover="tt(this);"></span><pre class="tt" style="max-width:400px;white-space:normal;"><?=$item['comment']?></pre>&nbsp;&nbsp;
    <a href="#" class="fa fa-trash-o red" title="Удалить" onclick="del_tab(this,<?=$item['id']?>,'<?=$prefix?>comments');return false"></a>
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
$def['context_search']=!$this->input->get('context_search')?'comment':$this->input->get('context_search');
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
