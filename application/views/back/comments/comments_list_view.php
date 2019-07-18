<h1><?=$data['view_title']?></h1>
<?php if(!empty($data['new_comments'])){?>

<!--####### Новые комментарии #######-->
<div class="sheath">
 <h3>Новые комментарии</h3>
 <hr>
 <?php foreach($data['new_comments'] as $v){?>
  <div class="touch">
   <div class="float_l">
    <b><?=$v['name']?></b>
    <?=$v['pid']>0?' в ответ на <a href="'.base_url($v['url'].'#comment_'.$v['pid']).'" target="_blank">комментарий</a>':FALSE?>
   </div>
   <div class="algn_r">
    <a href="/admin/comment/public_new/<?=$v['premod_code']?>" class="fa-check-circle green" title="Опубликовать комментарий"></a>&nbsp;&nbsp;
    <a href="/admin/comment/del_new/<?=$v['premod_code']?>" class="fa-trash-o red" title="Удалить комментарий" onclick="if(!confirm('Комментарий будет удален!\nВыполнить действие?')){return false;}"></a>&nbsp;&nbsp;
      <a href="/<?=$v['url']?>" target="_blank" class="fa-external-link" title="Перейти на страницу"></a>&nbsp;&nbsp;
    <span class="blue fa-info-circle" onmouseover="tt(this);"></span>
    <pre class="tt">
<b>ID: </b><?=$v['id'].PHP_EOL?>
<b>URL: </b>/<?=$v['url'].PHP_EOL?>
<b>Дата: </b><?=$v['date']?>
    </pre>
   </div>
   <div><?=$v['comment']?></div>
  </div>
 <?php }?>
</div>
<?php }?>

<dl class="tabs">
 <dt class="tab_active">Фильтры</dt>
 <dd>
  <div class="tab_content">

   <!--####### Настройки вывода, поиск, иные опции #######-->
   <form id="filter" method="GET" action="<?=current_url()?>">
    <div class="row">
     <div class="col6">
      Сортировать
      <label class="select">
       <select name="order" onchange="submit()">
        <option value="id">По идентификатору</option>
        <option value="name">По имени комментатора</option>
        <option value="date">По дате</option>
        <option value="url">По URL</option>
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
        <option value="comment">Текст комментария</option>
        <option value="name">Имя комментатора</option>
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
  </div>
 </dd>
 <dt>Настройки</dt>
 <dd>
  <div class="tab_content">

   <!--####### Настройки комментариев #######-->
   <form method="POST" action="/admin/comment/set_comments_config">
    <div class="row">
     <div class="col6">
      <!--####### Вывод #######-->
      <div class="row touch">
       <h3>Вывод</h3>
       <hr>
       Форма комментирования по умолчанию <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Настройка будет применена по умолчанию
во всех вновь создаваемых материалах.
Вы сможете изменить ее индивидуально
на страницах добавления и редактирования
материалов.</pre>
       <label class="select">
        <select name="form">
         <option value="on">Разрешить комментировать и отвечать</option>
         <option value="on_comment">Разрешить только комментировать</option>
         <option value="off">Запретить комментировать и отвечать</option>
        </select>
       </label>
       Зарезервированные имена <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Здесь вы можете перечислить через точку с запятой (;)
имена и e-mail которые смогут использовать только
администратор и модераторы системы. Эта настройка не
позволит никому кроме вас комментировать и отвечать от
имени, к примеру, администратора. Валидация имен
не чувствительна к регистру (Админ = админ) но
чувствительна к символам разной локали ([eng A]дмин &ne; админ)</pre>
       <label class="input">
        <input type="text" name="reserved_names" value="<?=htmlspecialchars($data['conf']['reserved_names'])?>">
       </label>
       Рейтинг комментариев <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Комментарии и ответы могут быть оценены
пользователями «лайками» и «дизлайками».</pre>
       <label class="select">
        <select name="rating">
         <option value="on">Разрешить рейтинг</option>
         <option value="off">Запретить рейтинг</option>
        </select>
       </label>
       Лимит в поле «Ваше имя» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Лимит символов в поле «Ваше имя»
форм комментирования и ответа.
Если "0" - лимит безграничный.
<b class="red">Целое, положительное число!</b></pre>
       <label class="input">
        <input name="name_limit" type="number" min="0" value="<?=htmlspecialchars($data['conf']['name_limit'])?>">
       </label>
       Лимит в поле «Ваш комментарий» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Лимит символов в поле «Ваш комментарий»
и «Ваш ответ» формы комментирования и ответа.
Если "0" - лимит безграничный.
<b class="red">Целое, положительное число!</b></pre>
       <label class="input">
        <input name="text_limit" type="number" min="0" value="<?=htmlspecialchars($data['conf']['text_limit'])?>">
       </label>
       Кнопка «Еще комментарии» <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Установите число комментариев после которых
в списке будет выведена кнопка «Еще комментарии»,
а остальной список будет скрыт.
Если "0" - не скрывать комментарии.
<b class="red">Целое, положительное число!</b></pre>
       <label class="input">
        <input name="show" type="number" min="0" value="<?=htmlspecialchars($data['conf']['show'])?>">
       </label>
      </div>
     </div>
     <div class="col6">
      <!--####### Уведомления #######-->
      <div class="row touch">
       <h3>Уведомления</h3>
       <hr>
       Уведомления о новых комментариях <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Выберите e-mail на который система будет
отправлять уведомления о новых комментариях
к материалам, или если комментарии нуждаются в
премодерации перед публикацией.</pre>
       <label class="select">
        <select name="notific">
         <option value="off">Не уведомлять и публиковать по умолчанию</option>
         <optgroup label="Без премодерации">
          <option value="site_mail">На e-mail сайта</option>
          <option value="admin_mail">На e-mail администратора</option>
          <option value="moderator_mail">На e-mail всем модераторам</option>
         </optgroup>
         <optgroup label="С премодерацией">
          <option value="premod_site_mail">На e-mail сайта [премодерация]</option>
          <option value="premod_admin_mail">На e-mail администратора [премодерация]</option>
          <option value="premod_moderator_mail">На e-mail всем модераторам [премодерация]</option>
         </optgroup>
        </select>
       </label>
       Обратная связь с комментатором <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
       <pre class="tt">
Опция позволяет уведомлять пользователей
об ответах на их комментарии. Система
предлагает указать e-mail в поле «Ваше имя»
и отправляет уведомление об ответе. Система
скрывает в списке комментариев e-mail,
выводит только имя пользователя e-mail.
Например: «pupkin» из «pupkin@gmail.com»</pre>
       <label class="select">
        <select name="feedback">
         <option value="on">Разрешить обратную связь</option>
         <option value="off">Запретить обратную связь</option>
        </select>
       </label>
      </div>
     </div>
    </div>
    <div class="button this_form_control">
     <button type="button" onclick="subm(form,s_opts)">Сохранить все настройки</button>
    </div>
   </form>
  </div>
 </dd>
</dl>

<?php if(empty($data['comments'])){?>
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
   <td>Язык</td>
   <td>Действия</td>
  </tr>
 </thead>
 <tbody>
 <?php
 $tree_arr=[];
 foreach($data['comments'] as $v){$tree_arr[$v['pid']][]=$v;}//получить многомерный массив
 function build_tree($tree_arr,$pid=0){//построение дерева
  if(!is_array($tree_arr) || !isset($tree_arr[$pid])){return false;}//нет данных
  foreach($tree_arr[$pid] as $v){?>
  <tr id="<?=$v['id']?>">
   <td><?=$v['date']?></td>
   <td><?=$v['pid']>0?'<a href="'.base_url($v['url'].'#comment_'.$v['pid']).'" class="fa-level-up" target="_blank" title="Перейти к родительскому комментарию"></a> '.mb_strimwidth($v['name'],0,40,'...'):mb_strimwidth($v['name'],0,40,'...')?></td>
   <td>/<?=mb_strimwidth($v['url'],0,40,'...')?></td>
   <td><?=$v['lang']?></td>
   <td>
    <span class="blue fa fa-info-circle" onmouseover="tt(this);"></span><pre class="tt" style="max-width:400px;white-space:normal;"><?=$v['comment']?></pre>&nbsp;&nbsp;
    <a href="<?=base_url($v['url'].'#comment_'.$v['id'])?>" target="_blank" class="fa fa-external-link" title="Перейти к комментарию на странице"></a>&nbsp;&nbsp;
    <a href="#" class="fa fa-trash-o red" title="Удалить с дочерней ветвью" onclick="del_branch('<?=$v['id']?>','<?=$v['url']?>');return false"></a>
   </td>
  </tr>
  <?php build_tree($tree_arr,$v['id']);}}
  build_tree($tree_arr);//вывод дерева?>
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
$def['search']=($this->input->get('search')==='')?'':addslashes($this->input->get('search'));
?>
<script>
var form=$('#filter'),
    s_opts={//рег.выражения для проверки полей
     name_limit:/^\d+$/,
     text_limit:/^\d+$/,
     show:/^\d+$/
    };
$(function(){
 //////////////////////////устанавливаю значеня полей фильтра
 form.find('select[name="order"] option[value="<?=$def['order']?>"]').attr('selected',true);
 form.find('select[name="pag_per_page"] option[value="<?=$def['pag_per_page']?>"]').attr('selected',true);
 form.find('select[name="context_search"] option[value="<?=$def['context_search']?>"]').attr('selected',true);
 form.find('input[name="search"]').val('<?=$def['search']?>');
 //////////////////////////установка значений полей настроек
 $('select[name="form"] option[value="<?=$data['conf']['form']?>"]').attr('selected',true);
 $('select[name="rating"] option[value="<?=$data['conf']['rating']?>"]').attr('selected',true);
 $('select[name="notific"] option[value="<?=$data['conf']['notific']?>"]').attr('selected',true);
 $('select[name="feedback"] option[value="<?=$data['conf']['feedback']?>"]').attr('selected',true);
});
//////////////////////////удаление опубликованого комментария с дочерней ветвью
function del_branch(id,url){
 if(!confirm('Комментарий и его дочерняя ветвь будут удалены!\nВыполнить действие?')){return false;}
 $.ajax({
   url:'/admin/comment/del_branch',
   type:'post',
   data:{id:id,url:url},
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case 'ok':for(var i in resp.ids){$('#'+resp.ids[i]).remove();}break;
     case 'error':alert('Ошибка! Не удалось удалить комментарий..(');break;
     default :console.log(resp);
    }
   },
  error:function(){
   alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
  }
 });
}
</script>
