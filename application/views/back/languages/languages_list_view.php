<h1><?=$data['view_title']?></h1>
<!--####### Настройки вывода, поиск, иные опции #######-->
<form id="filter" class="sheath" method="GET" action="<?=current_url()?>">
 <div class="button algn_r">
  <a href="/admin/language/add_form" class="btn_lnk">Добавить язык</a>
 </div>
 <div class="row">
  <div class="col6">
   Сортировать
   <label class="select">
    <select name="order" onchange="submit()">
     <option value="id">По идентификатору</option>
     <option value="tag">По тегу</option>
     <option value="title">По названию</option>
     <option value="def">По умолчанию</option>
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
     <option value="title">Название</option>
     <option value="tag">Тег</option>
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

<?php if(empty($data['langs'])){?>
 <div class="sheath">
  <p>Ничего не найдено. Запрос не дал результатов..(</p>
 </div>
<?php }else{?>

 <!--####### Таблица записей #######-->
 <table class="tabl order-table">
  <thead>
   <tr>
    <td>Название</td>
    <td>Тег</td>
    <td>Действия</td>
   </tr>
  </thead>
  <tbody>
   <?php foreach($data['langs'] as $v){?>
    <tr>
     <td><?=mb_strimwidth($v['title'],0,40,'...')?></td>
     <td><?=$v['tag']?></td>
     <td>
      <?=$v['def']=='on'?'<i class="fa-star red" title="Язык по умолчанию"></i>&nbsp;&nbsp;':FALSE?>
      <a href="/admin/language/edit_form/<?=$v['id']?>" class="fa-edit green" title="Редактировать"></a>&nbsp;&nbsp;
      <?php if(!$v['def']){?>
       <span><a href="#" class="<?=$v['public']=='on'?'fa-eye green':'fa-eye-slash red'?>" title="Опубликовать/не опубликовывать" onclick="toggle_public(this,<?=$v['id']?>,'languages');return false"></a></span>&nbsp;&nbsp;
       <a href="#" class="fa-trash-o red" title="Удалить" onclick="del_lang(this,'<?=$v['tag']?>');return false"></a>
      <?php }?>
     </td>
    </tr>
   <?php }?>
  </tbody>
 </table>

 <!--####### Постраничная навигация #######-->
 <?=$this->pagination->create_links()?>
<?php }?>

<script>
//установить значеня полей фильтра
<?php
$def['order']=!$this->input->get('order')?'id':$this->input->get('order');
$def['pag_per_page']=!$this->input->get('pag_per_page')?$this->session->userdata('pag_per_page'):$this->input->get('pag_per_page');
$def['context_search']=!$this->input->get('context_search')?'title':$this->input->get('context_search');
$def['search']=($this->input->get('search')==='')?'':addslashes($this->input->get('search'));
?>
 var form=$('#filter');
 $(function(){
  form.find('select[name="order"] option[value="<?=$def['order']?>"]').attr('selected',true);
  form.find('select[name="pag_per_page"] option[value="<?=$def['pag_per_page']?>"]').attr('selected',true);
  form.find('select[name="context_search"] option[value="<?=$def['context_search']?>"]').attr('selected',true);
  form.find('input[name="search"]').val('<?=$def['search']?>');
 });
//удалить язык
 function del_lang(el,tag){
  if(!confirm('Вместе с языком будут удалены: все материалы, меню, файлы локализации, каталог языка в файловом менеджере со всем содержимым!\nВыполнить действие?')){
   return false;
  }
  $.ajax({
   url:'/admin/language/del',
   type:'post',
   data:{tag:tag},
   dataType:'text',
   success:function(resp){
    switch(resp){
     case 'ok':
      $(el).parents('tr').remove();
      break;
     case 'error':
      alert('Ошибка! Не удалось удалить язык..(');
      break;
     default :
      console.log(resp);
    }
   },
   error:function(){
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 }
</script>