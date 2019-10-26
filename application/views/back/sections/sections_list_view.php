<h1><?=$data['view_title']?></h1>
<!--####### Настройки вывода, поиск, иные опции #######-->
<form id="filter" class="sheath" method="GET" action="<?=current_url()?>">
 <div class="button algn_r">
  <a href="/admin/section/add_form" class="btn_lnk">Добавить раздел</a>
 </div>
 <div class="row">
  <div class="col6">
   Сортировать
   <label class="select">
    <select name="order" onchange="submit()">
     <option value="id">По идентификатору</option>
     <option value="creation_date">По дате создания</option>
     <option value="last_mod_date">По дате изменения</option>
     <option value="title">По заголовку</option>
     <option value="section">По разделу</option>
     <option value="public">По публикации</option>
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

<?php if(empty($data['sections'])){?>
 <div class="sheath">
  <p>Ничего не найдено. Запрос не дал результатов..(</p>
 </div>
<?php }else{?>

 <!--####### Таблица записей #######-->
 <table class="tabl order-table">
  <thead>
   <tr>
    <td>Заголовок</td>
    <td>Раздел</td>
    <td>Язык</td>
    <td>Действия</td>
   </tr>
  </thead>
  <tbody>
   <?php
   $this->load->helper('back/section_id_to_title');
   $satt=new section_id_to_title();
   foreach($data['sections'] as $v){
    ?>
    <tr>
     <td><?=mb_strimwidth($v['title'],0,40,'...')?></td>
     <td><?=$satt->get_title($v['section'])?></td>
     <td><?=$v['lang']?></td>
     <td>
      <span><a href="#" class="<?=$v['public']=='on'?'fa-eye green':'fa-eye-slash red'?>" title="Опубликовать/не опубликовывать" onclick="toggle_public(this,<?=$v['id']?>,'sections');return false"></a></span>&nbsp;&nbsp;
      <a href="/admin/section/edit_form/<?=$v['id']?>" class="fa-edit green" title="Редактировать"></a>&nbsp;&nbsp;
      <a href="/section/<?=$v['id']?>" class="fa-external-link" target="_blank" title="Смотреть на сайте"></a>&nbsp;&nbsp;
      <a href="#" class="fa-trash-o red" title="Удалить" onclick="del_section(this,'<?=$v['id']?>');return false"></a>
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
//удалить раздел
 function del_section(el,id){
  if(!confirm('Раздел и все комментарии к нему будут удалены, а дочерние разделы, галереи и страницы не будут пренадлежать ниодному разделу!\nВыполнить действие?')){
   return false;
  }
  $.ajax({
   url:'/admin/section/del',
   type:'post',
   data:{id:id},
   dataType:'text',
   success:function(resp){
    switch(resp){
     case 'ok':
      $(el).parents('tr').remove();
      break;
     case 'error':
      alert('Ошибка! Не удалось удалить раздел..(');
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