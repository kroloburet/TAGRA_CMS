<?php
function print_pids_tree($input,$level=0){
 if(empty($input)){return FALSE;}
 foreach($input as $v){
  echo'<option value="'.$v['id'].'">'.str_repeat('&#183; ',$level).$v['title'].'</option>'.PHP_EOL;
  if(isset($v['nodes'])){print_pids_tree($v['nodes'],$level+1);}
 }
}

function print_menu_tree($input){
 if(empty($input)){return FALSE;}
 foreach($input as $v){
  $ext_link=$v['url']?'<a href="'.$v['url'].'" target="_blank" class="fa-external-link" title="Посмотреть на сайте"></a>&nbsp;&nbsp;':'<i class="fa-external-link gray"></i>&nbsp;&nbsp;';
  echo '<li>'.PHP_EOL;
  echo '<div class="m_item">'.$v['title'].'&nbsp;&nbsp;'.PHP_EOL
       .$ext_link.PHP_EOL
       .'<a href="#" onclick="Menu.public_del(this,\'public\');return false" class="'.($v['public']=='on'?'fa-eye green':'fa-eye-slash red').'" title="Опубликовать/не опубликовывать"></a>&nbsp;&nbsp;'.PHP_EOL
       .'<a href="#" class="fa-edit green" title="Редактировать" onclick="Menu.show_edit_form(this);return false"></a>&nbsp;&nbsp;'.PHP_EOL
       .'<a href="#" class="fa-trash-o red" title="Удалить" onclick="Menu.public_del(this,\'del\');return false"></a>'.PHP_EOL
       .'<textarea class="m_item_opt" hidden>'.json_encode($v,JSON_FORCE_OBJECT).'</textarea>'.PHP_EOL
       .'</div>'.PHP_EOL;
  if(isset($v['nodes'])){
   echo '<ul>'.PHP_EOL;
   print_menu_tree($v['nodes']);//рекурсия
   echo '</ul>'.PHP_EOL;
  }
  echo '</li>'.PHP_EOL;
 }
}
?>

<h1><?="{$data['view_title']} [{$data['lang']}]"?></h1>
<div class="sheath" id="m_area">
 <div class="touch">
  <h3>Добавить пункт меню</h3>
  <hr>
  <form class="m_form">
   <div class="row">
    <div class="col6">
     Родительский пункт
     <label class="select">
      <select class="m_pid" onchange="Menu.load_order(this)">
       <option value="0">Нет родителя</option>
       <?php print_pids_tree($data['menu'])?>
      </select>
     </label>
    </div>
    <div class="col6">
     Порядок
     <label class="select">
      <select class="m_order"></select>
     </label>
    </div>
    Название пункта
    <label class="input">
     <input type="text" class="m_title">
    </label>
   </div>
   <div class="row">
    <div class="col6">
     Ссылка
     <label class="input">
      <input type="text" class="m_url" id="m_url" placeholder="Оставьте пустым, если пункт — не ссылка">
     </label>
    </div>
    <div class="col6">
     Материалы ресурса
     <label class="select">
      <select class="m_link" onchange="Menu.select_link(this)">
       <option value="">Выбрать из предложенных:</option>
       <option value="pages">Страница сайта</option>
       <option value="sections">Раздел сайта</option>
       <option value="gallerys">Галерея сайта</option>
       <option value="home">Страница "Главная"</option>
       <option value="contact">Страница "Контакты"</option>
       <option value="file">Файл или папка</option>
      </select>
     </label>
    </div>
   </div>
   <div class="m_link_viewer"></div>
   <div class="row">
    <div class="col6">
     <label class="select">
      <select class="m_target">
       <option value="_self">Открывать в текущем окне</option>
       <option value="_blank">Открывать в новом окне</option>
      </select>
     </label>
    </div>
    <div class="col6">
     <label class="select">
      <select class="m_public">
       <option value="on">Опубликовать пункт</option>
       <option value="off">Не опубликовывать пункт</option>
      </select>
     </label>
    </div>
   </div>
   <div class="button m_control">
    <button type="button" onclick="Menu.add_edit(this,'add')">Добавить пункт меню</button>
   </div>
  </form>
 </div>

 <ul class="m_tree">
  <?php print_menu_tree($data['menu'])?>
 </ul>
</div>

<script>
var Menu={
 _mm:<?=json_encode($data['materials'],JSON_FORCE_OBJECT)?>,//материалы ресурса
 _m:<?=json_encode($data['menu'],JSON_FORCE_OBJECT)?>,//меню

 ///////////////////////заполнение списка "Порядок"
 load_order:function(el,id){
  //el-список "Порядок" (this)
  //id-id пункта исключаемого из списка в форме редактирования
  var f=$(el.form),
      pid=$(el),
      order=f.find('.m_order'),
      n=1,
      filling=function(m){//рекурсивное наполнение пунктами родителя
       for(var i in m){
        if(pid.val()===m[i].pid){
         id&&id===m[i].id?false:order.append('<option value="'+(n++)+'">После "'+m[i].title+'"</option>');
        }else if(m[i].nodes){filling(m[i].nodes);}
       }
      };
  order.html('<option value="'+(n++)+'">Первый пункт</option>');
  if($.isEmptyObject(this._m)){return false;}
  filling(this._m);
  order.find('option').last().attr('selected',true);
 },

 ///////////////////////работа списка "Материалы ресурса"
 select_link:function(el){
  //el-список "Материалы ресурса" (this)
  var f=$(el.form),
      link=$(el),
      viewer=f.find('.m_link_viewer'),
      url=f.find('.m_url');
  switch(link.val()){
   case 'home':url.val('/');viewer.empty();break;
   case 'contact':url.val('/contact');viewer.empty();break;
   case 'file':files(url.attr('id'),'<?=$data['lang']?>',{no_host:true});viewer.empty();break;
   case 'pages':viewer.html(this.load_link_viewer('pages',url));break;
   case 'sections':viewer.html(this.load_link_viewer('sections',url));break;
   case 'gallerys':viewer.html(this.load_link_viewer('gallerys',url));break;
  }
 },

 ///////////////////////отображение списка и выбор материала ресурса
 load_link_viewer:function(material,url){
  //material-строка, значение списка "Материалы ресурса"
  //url-объект, поле "Ссылка"
  var mm=this._mm[material],
      label=$('<label/>',{class:'select'}),
      section=$('<select/>',{class:'full',size:'5'}),
      preurl='/';
  label.html(section);
  if(!mm||$.isEmptyObject(mm)){section.html($('<option/>',{text:'Нет материалов..('}));return label;}
  switch(material){
   case 'pages':preurl='/page/';break;
   case 'sections':preurl='/section/';break;
   case 'gallerys':preurl='/gallery/';break;
  }
  for(var k in mm){section.append($('<option/>',{value:preurl+mm[k].id,text:mm[k].title}));}
  section.on('change.M',function(){url.val(this.value);}).SelectSearch();
  return label;
 },

 ///////////////////////отображение формы редактирования пункта
 show_edit_form:function(el){
  //el-ссылка "Редактировать" (this)
  this.hide_edit_form($('.m_item'));
  var m_item=$(el).parent('.m_item'),
      clone=$('.m_form').clone(true),
      m_pid=clone.find('.m_pid'),
      opt=$.parseJSON(m_item.find('.m_item_opt').val()),//данные редактируемого пункта с вложенными
      set_pids=function(m){//удаление из списка "Родительский пункт" редактируемого пункта с вложенными
       m_pid.find('option[value="'+m.id+'"]').remove();
       if(m.nodes){for(var i in m.nodes){set_pids(m.nodes[i]);}}
      },
      btns=[
       $('<button/>',{type:'button',text:'Сохранить изменения'}).on('click.M',function(){Menu.add_edit(this,'edit');}),
       $('<button/>',{type:'button',text:'Отмена'}).on('click.M',function(){Menu.hide_edit_form(m_item);})
      ];
  set_pids(opt);
  m_pid.val(opt.pid);
  this.load_order(m_pid[0],opt.id);
  clone.find('.m_order').val(opt.order);
  clone.find('.m_link_viewer').empty();
  clone.find('.m_title').val(opt.title);
  clone.find('.m_url').attr('id',opt.id).val(opt.url);
  clone.find('.m_target').val(opt.target);
  clone.find('.m_public').val(opt.public);
  clone.find('.m_control').html(btns);
  clone.prepend($('<input/>',{class:'m_id',type:'hidden',value:opt.id}));
  m_item.addClass('edit').append(clone);
 },

 ///////////////////////сокрытие формы редактирования пункта
 hide_edit_form:function(item){
  //item-объект, контейнер редактируемого пункта
  item.removeClass('edit');
  item.find('.m_form').remove();
 },

 ///////////////////////отображение сообщений
 msg:function(style,msg,targ,call){
  //style-строка, css-класс сообщения
  //msg-строка, сообщение
  //targ-объект, элемент для отображения сообщения
  //call-функция, что делать после отображения сообщения
  style=style||'notific_r';
  msg=msg||'Поле "Название пункта" должно быть заплнено!';
  var box=$('<p/>',{class:'full '+style,html:msg});
  targ.html(box);
  setTimeout(function(){box.remove();call();},3000);
  return true;
 },

 ///////////////////////обновление страницы и данных
 update:function(data){
  //data-json (объект), ответ сервера
  $('#m_area').replaceWith($(data.html).filter('#m_area'));
  this._mm=data.materials;
  this._m=data.menu;
  this.load_order($('.m_form').find('.m_pid')[0]);
 },

 ///////////////////////добавление\редактирование пункта
 add_edit:function(el,action){
  //el-кнопка "Добавить пункт меню\Сохранить изменения" (this)
  //action-строка (add||edit), действие
  if(!el||(action!=='add'&&action!=='edit')){return false;}
  var f=$(el.form),
      title=f.find('.m_title').val(),
      process='<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...',
      control=f.find('.m_control'),
      btn=control.html(),
      err=action==='add'?'Ошибка! Не удалось добавить пункт меню..(':'Ошибка! Не удалось изменить пункт меню..(';
  if(!/[^\s]/.test(title)){return this.msg(null,null,control,function(){control.html(btn);});}
  control.html(process);
  $.ajax({
   url:'/admin/menu/'+(action==='add'?'add_item':'edit_item'),
   type:'post',
   data:{
    lang:'<?=$data['lang']?>',
    id:f.find('.m_id').val()||null,
    pid:f.find('.m_pid').val(),
    order:f.find('.m_order').val(),
    title:title,
    url:f.find('.m_url').val(),
    target:f.find('.m_target').val(),
    public:f.find('.m_public').val()
   },
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case 'ok':Menu.update(resp);break;
     case 'error':Menu.msg(null,err,control,function(){control.html(btn);});break;
     default :console.log(resp);
    }
   },
   error:function(){
    Menu.msg(null,'Ой! Возникла ошибка соединения..( Повторите попытку.',control,function(){control.html(btn);});
   }
  });
 },

 ///////////////////////переключение публикации\удаление пункта
 public_del:function(el,action){
  //el-ссылка "Удалить" или "Опубликовать/не опубликовывать"(this)
  //action-строка (public||del), действие
  if(!el||(action!=='public'&&action!=='del')){return false;}
  if(action==='del'&&!confirm('Пункт меню будет удален вместе с вложенными пунктами!\nВыполнить действие?')){return false;}
  var self=$(el),
      process=$('<i/>',{class:'fa fa-spin fa-spinner'});
  self.replaceWith(process);
  $.ajax({
   url:'/admin/menu/'+(action==='public'?'public_item':'del_item'),
   type:'post',
   data:$.parseJSON(process.siblings('.m_item_opt').val()),
   dataType:'json',
   success:function(resp){
    switch(resp.status){
     case 'ok':Menu.update(resp);break;
     case 'error':
      process.replaceWith(self);
      alert(action==='public'?'Ошибка! Не удалось применить изменения..(':'Ошибка! Не удалось удалить пункт меню..(');
     break;
     default :process.replaceWith(self);console.log(resp);
    }
   },
   error:function(){
    process.replaceWith(self);
    alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
   }
  });
 }

};
///////////////////////после загрузки страницы
Menu.load_order($('.m_form').find('.m_pid')[0]);
</script>