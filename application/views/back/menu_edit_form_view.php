<?php
$GLOBALS['menu']=&$menu;
///////////////////////////////////////////вывод выпадающего списка пунктов меню
 function get_select_menu_tree(/*массив меню*/$tree,/*id пункта*/$id='',/*id родителя*/$pid='') {
  if (empty($tree)) {//если передан пустой массив
   return '<select name="pid"><option value="0">Нет родителя</option></select>';
  }
  $select_pid='';
  $nbspCnt=0;
  $curPos=0;
  $count=count($tree);
  $stateArray=array();
  $repeat='&#183; ';
  if($pid==''){$selected='selected';}else{$selected='';}
  $select_pid.='<select name="pid">';
  $select_pid.='<option value="0" '.$selected.'>Нет родителя</option>';
  do{
   while($curPos<$count){
    $selected='';
    $disabled='';
    if($pid==$tree[$curPos]['id']){$selected='selected';}
    if($id!==''){if($tree[$curPos]['id']==$id){$disabled='disabled';}}
    $select_pid.= '<option value="'.$tree[$curPos]['id'].'" '.$selected.' '.$disabled.'>'.str_repeat($repeat,$nbspCnt).$tree[$curPos]['title'].'</option>';
    if(isset($tree[$curPos]['nodes'])){
     array_push($stateArray,array('tree'=>&$tree,'count'=>$count,'curPos'=>$curPos+1));
     $tree=&$tree[$curPos]['nodes'];
     $count=count($tree);
     $curPos=0;
     $nbspCnt++;
    }else{
     $curPos++;
    }
   }
   if(($a=array_pop($stateArray))!==null){
    $curPos=$a['curPos'];
    $count=$a['count'];
    $tree=$a['tree'];
    $nbspCnt--;
   }
  }while($a!=null);
  $select_pid.='</select>';
  return $select_pid;
 }
 
////////////////////////////////////////////////Вывод дерева меню
 function get_menu_tree(/*массив меню*/$list) {
  if(empty($list)){//если передан пустой массив
   return '';
  }
  foreach($list as $v){
   echo '<li>';
   //содержимое елемента списка
   $_self=$_blank='';
   $ext_link=$v['url']?'<a href="'.$v['url'].'" target="_blank" class="fa-external-link" title="Перейти на страницу"></a>&nbsp;&nbsp;':'<i class="fa-external-link gray"></i>&nbsp;&nbsp;';
   $eye=$v['public']=='on'?'fa-eye green':'fa-eye-slash red';
   if($v['target']=='_blank'){$_blank='selected';}else{$_self='selected';}
   echo '<span class="menu_item">'.$v['title'].'&nbsp;&nbsp;
         '.$ext_link.'
         <span><a href="#" onclick="public_item(this,\''.$v['id'].'\',\''.$v['public'].'\');return false" class="'.$eye.'" title="Опубликовать/не опубликовывать"></a></span>&nbsp;&nbsp;
         <a href="'.base_url('admin/menu/del_item/'.$v['id'].'/'.$v['pid'].'/'.$v['order']).'" class="fa-trash-o red" title="Удалить" onclick="if(!confirm(\'Пункт меню будет удален вместе с вложенными пунктами!\nВыполнить действие?\')){return false;}"></a>&nbsp;&nbsp;
         <a href="#" class="fa-edit green" title="Редактировать" onclick="opn_cls(\'edit_item_'.$v['id'].'\');return false"></a>
        </span>
        <div class="edit_menu_item" id="edit_item_'.$v['id'].'">
         <form action="'.base_url('admin/menu/edit_item/'.$v['id']).'" method="POST" onsubmit="subm(this);return false">
          <div class="row">
           <div class="col6">
            Родительский пункт
             <label class="select">
              '.get_select_menu_tree($GLOBALS['menu'],$v['id'],$v['pid']).'
            </label>
           </div>
           <div class="col6">
            Порядок
            <label class="select">
             <select name="order" id="order"></select>
            </label>
           </div>
          Название пункта
          <label class="input">
           <input type="text" name="title" value="'.$v['title'].'" required>
          </label>
          </div>
          <div class="row">
           <div class="col6">
            Ссылка
            <label class="input">
             <input type="text" name="url" id="url_'.$v['id'].'" value="'.$v['url'].'" placeholder="Оставьте пустым, если пункт — не ссылка">
            </label>
           </div>
           <div class="col6">
            Материалы ресурса
            <label class="select">
             <select name="link" onchange="select_link(this,\'url_'.$v['id'].'\',\'link_viewer_'.$v['id'].'\')">
              <option selected>Выбрать из предложенных:</option>
              <option value="page">Страница сайта</option>
              <option value="section">Раздел сайта</option>
              <option value="gallery">Галерея сайта</option>
              <option value="index">Страница «Главная»</option>
              <option value="contact">Страница «Контакты»</option>
              <option value="file">Файл или папка</option>
             </select>
            </label>
           </div>
          </div>
          <div class="link_viewer" id="link_viewer_'.$v['id'].'"></div>
          <div>
           <label class="select">
            <select name="target">
             <option value="_self" '.$_self.'>В текущем окне</option>
             <option value="_blank" '.$_blank.'>В новом окне</option>
            </select>
           </label>
          </div>
          <div class="button inline">
           <input type="submit" value="Редактировать">
           <a href="#" class="btn_lnk" onclick="opn_cls(\'edit_item_'.$v['id'].'\');return false">Отменить</a>
          </div>
         </form>
        </div>
        ';
   if(isset($v['nodes'])){//если есть вложенные елементы
    echo '<ul>';
    get_menu_tree($v['nodes']);//рекурсия
    echo '</ul>';
   }
   echo '</li>';
  }
 }
 ?>

<h1><?=$conf_title?></h1>
<div class="container">
 <div class="touch">
 <h3>Добавить пункт меню</h3>
 <hr>
 <form method="POST" action="<?=base_url('admin/menu/add_item')?>" onsubmit="subm(this);return false">
  <div class="row">
   <div class="col6">
    Родительский пункт
     <label class="select">
      <?=get_select_menu_tree($menu)?>
    </label>
   </div>
   <div class="col6">
    Порядок
    <label class="select">
     <select name="order" id="order"></select>
    </label>
   </div>
  Название пункта
  <label class="input">
   <input type="text" name="title" required>
  </label>
  </div>
  <div class="row">
   <div class="col6">
    Ссылка
    <label class="input">
     <input type="text" name="url" id="add_url" placeholder="Оставьте пустым, если пункт — не ссылка">
    </label>
   </div>
   <div class="col6">
    Материалы ресурса
    <label class="select">
     <select name="link" onchange="select_link(this,'add_url','link_viewer')">
      <option selected>Выбрать из предложенных:</option>
      <option value="page">Страница сайта</option>
      <option value="section">Раздел сайта</option>
      <option value="gallery">Галерея сайта</option>
      <option value="index">Страница «Главная»</option>
      <option value="contact">Страница «Контакты»</option>
      <option value="file">Файл или папка</option>
     </select>
    </label>
   </div>
  </div>
  <div class="link_viewer" id="link_viewer"></div>
  <div class="row">
   <div class="col6">
    <label class="select">
     <select name="target">
      <option value="_self" selected>Открывать в текущем окне</option>
      <option value="_blank">Открывать в новом окне</option>
     </select>
    </label>
   </div>
   <div class="col6">
    <label class="select">
     <select name="public">
      <option value="on" selected>Опубликовать пункт</option>
      <option value="off">Не опубликовывать пункт</option>
     </select> 
    </label>
   </div>
  </div>
  <div class="button">
   <input type="submit" value="Добавить пункт меню">
  </div>
 </form>
 </div>
 <ul class="menu_tree">
 <?php get_menu_tree($menu)?>
 </ul>
</div>

<script>
///////////////////////////списки материалов
 var pages='<?php if(empty($pages)){echo'<option value="">На сайте нет страниц</option>';}else{foreach($pages as $i){?><option value="/<?=$i['alias']?>"><?=$i['title']?></option><?php }}?>',
     sections='<?php if(empty($sections)){echo'<option value="">На сайте нет разделов</option>';}else{foreach($sections as $i){?><option value="/<?='section/'.$i['alias']?>"><?=$i['title']?></option><?php }}?>',
     gallerys='<?php if(empty($gallerys)){echo'<option value="">На сайте нет галерей</option>';}else{foreach($gallerys as $i){?><option value="/<?='gallery/'.$i['alias']?>"><?=$i['title']?></option><?php }}?>';
 
//////////////////////////выпадающий список материалов для выбора
 function select_link(select/*this*/,input_url/*поле для вставки ссылки*/,viewer/*список ссылок для добавления в input_url*/){
  var url=$('#'+input_url),
      viewer=$('#'+viewer);
  switch($(select).val()){
   case 'index':url.val('/');viewer.empty();break;
   case 'contact':url.val('/contact');viewer.empty();break;
   case 'file':files(input_url,{no_host:true});viewer.empty();break;
   case 'page':
viewer.html('<label class="select"><select class="full SelectSearch" size="5" onchange="$(\'#'+input_url+'\').val($(this).val())">'+pages+'</select></label>');
viewer.find('select').SelectSearch();
   break;
   case 'section':
viewer.html('<label class="select"><select class="full SelectSearch" size="5" onchange="$(\'#'+input_url+'\').val($(this).val())">'+sections+'</select></label>');
viewer.find('select').SelectSearch();
   break;
   case 'gallery':
viewer.html('<label class="select"><select class="full SelectSearch" size="5" onchange="$(\'#'+input_url+'\').val($(this).val())">'+gallerys+'</select></label>');
viewer.find('select').SelectSearch();
   break;
  }
 }
 
////////////////////////загрузка списка порядкового номера
 var menu=<?=json_encode($menu)?>;//массив меню в объект (json)
 function get_order_menu_select(
         /*объект меню*/menu,
         /*id родительского пункта для вывода его детей*/id,
         /*id тега select для заполнения*/elem,
         /*значение ближайшего поля "название пункта"*/item_name
         ){//
  $.each(menu,function(){//перебрать объект меню
   if(this.pid===id){//если есть дочерние пункты пункта с id
    var dsbl=item_name===this.title?'disabled':'';
    elem.append('<option value="'+(parseInt(this.order,10)+1)+'" '+dsbl+'>после«'+this.title+'»</option>');//наполняю ими elem
   }else{//если нет
    if(this.nodes)get_order_menu_select(this.nodes,id,elem,item_name);//если есть дочерняя ветка - ищем там, запускаем рекурссию
   }
  });
  if(item_name!==''){
   elem.children('option:disabled').prev('option').attr('selected','selected');
  }else{
   elem.children('option').last().attr('selected','selected');
  }
 }
  
////////////////////////побликовать\не публиковать пункт меню
 function public_item(el,id,pub){
  $(el).parent().load(
   '<?=base_url('admin/menu/public_item')?>',
   {id:id,pub:pub}
  );
 }

///////////////////////после загрузки страницы
 $(function(){
  $('select[name=pid]').on('change',function(){//после выбора корневого пункта
   var f=$(this.form);
  f.find('select[name=order]').html('<option value="1">Первый пункт</option>');//добавить первый пункт
  get_order_menu_select(menu,$(this).val(),f.find('select[name=order]'),f.find('input[name=title]').val());//добавить дочерние пункты, если есть
  });
  $('select[name=pid]').change();//загрузить списоки порядкового номера по умолчанию
 });
</script>
