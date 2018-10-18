$(function(){//готовность DOM

//////////////////////////////////////////////////////////////загрузка альтернативного изображения
 $('img').on('error',function(){$(this).attr('src','/img/noimg.svg');});

//////////////////////////////////////////////////////////////показать в пункте меню (комментарии) колличество новых комментов
 $('#count_new_comments').load(
  "/admin/comment/get_count_new",
  function(data){
   var count=$('#count_new_comments');
   data>0?count.addClass('fa-bell-o red'):count.remove();
  }
 );

//////////////////////////////////////////////////////////////замена недопустимых символов в полях
 var f=$('textarea[name="description"]');
 f.on('blur',function(){$(this).val($(this).val().replace(/"([^"]+)"/g,"«$1»").replace(/"/g,"'").replace(/\n/g," "));});

});//готовность DOM

////////////////////////////////////////////////превью изображения по url из поля
function img_prev(targ,input){//превью изображения по url из поля input, по событию на элементе targ
 var self=targ,
     e=$('#'+input),
     prev=$(targ).next('.tt');
 if(e.val()!==''){//если поле не пустое
  prev.html('<img src="'+e.val()+'" style="max-width:350px">');
  tt(self);
 }else{//если поле пустое
  prev.empty();
  tt(self);
 }
}

///////////////////////////////////////////////////////////////////////////////////справка пользователю системы
//localStorage.removeItem('notific_work_info');
function off_notific(){document.getElementById('notific_work_info').style.display="none";}
localStorage.getItem('notific_work_info')?off_notific():true;

/////////////////////////////////////////////////////////////////////////поиск по выпадающему списку
;(function($){
 var def_conf={
  placeholder:'Поиск по списку',
  noresult:'Ничего не найдено'
 };
 var methods={
  //инициализация
  init:function(user_conf){
   var conf=$.extend({},def_conf,user_conf);
   return this.each(function(){
    var self=$(this),//текущий список для поиска
        search=$('<input/>',{type:'text',class:'SelectSearch_input',placeholder:conf.placeholder}),//поле поска
        options=self.find('option'),//все элементы списка
        val,//значение в поле поиска
        opt;//результат поиска (отфильтрованные элементы списка)
    self.before(search);//прикрепляю поле поиска к списку
    search.on('keyup',function(){//искать в списке
     val=$(this).val();
     opt=options.map(function(){//запись в переменную всех найденных элементов
      if($(this).text().indexOf(val)+1){return this;}
     });
     //проверить, нашло ли что-то. если нашло - наполнить список нашедшим, если нет - выдать "Не найдено"
     opt.length>0?self.html(opt):self.html('<option>'+conf.noresult+'</option>');
    });
    search.on('blur',function(){//при впотере фокуса поля поиска
     self.html(options);//наполнить список всеми элементами
     $(this).val('');//очистить поле поиска
    });
   });
  }
 };
 $.fn.SelectSearch=function(method){//работа методов
  if(methods[method]){
   return methods[method].apply(this,Array.prototype.slice.call(arguments,1));
  }else if(typeof method==='object'||!method){
   return methods.init.apply(this,arguments);
  }else{$.error('Метод с именем '+method+' не существует');}
 };
})(jQuery);
window.addEventListener('load',function(){
 $('.SelectSearch').SelectSearch();//запуск плагина на всех select с классом SelectSearch
});

//////////////////////////////////////////////////отправка формы
function subm(form/*this - проверяемая форма*/,s_opts/*обьект{имя поля:/рег.выражение/}*/){
 var self=$(form),
     control=self.find('.this_form_control');
 if(typeof s_opts==='object'){//первый параметр - обьект, проверка полей
  for(var key in s_opts){//перебор обьекта циклом
   var el=self.find('[name="'+key+'"]');//проверяемое поле
   if(el.length>1){//если группа полей типа name="name[]" или такие поля динамически создаются
    for(var i=0;el.length>i;++i){//перебрать коллекцию
     if(s_opts[key].test(el[i].value)){//тест на регулярку проверки поля пройден
      el[i].className=el[i].className.replace(/(\snovalid)/g,'');//если поле с классом novalid - удалить этот класс
     }else{//тест не пройден
      el[i].className+=' novalid';//добавляю к полю класс novalid
      el[i].focus();//устанавливаю фокус
      alert("Отмеченое красным поле некоректно заполнено!\nЛибо в нем недопустимый символ, или оно пустое.\nЗаполните его правильно.");
      return false;
     }
    }
   }else{//если обычное поле типа name="name"
    //выделить поле. если поле - select или file - выделить родительский label
    if(s_opts[key].test(el.val())){
     (el.is('select')||el.is('input:file'))?el.parent('label').removeClass('novalid'):el.removeClass('novalid');
    }else{
     (el.is('select')||el.is('input:file'))?el.parent('label').addClass('novalid').focus():el.addClass('novalid').focus();
     alert("Отмеченое красным поле некоректно заполнено!\nЛибо в нем недопустимый символ, или оно пустое.\nЗаполните его правильно.");
     return false;
    }
   }
  }
 }
 control.html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...&nbsp;&nbsp;');
 self.submit();
}

/////////////////////////////////////////////////////////////////////////транслителяция кириллицы в латиницу
function translit(kiril,latin,prefix){
//kiril - поле с исходной строкой;
//latin - поле для обработанной строки. пример: translit(this,'#alias');
//prefix - префикс для большей уникальности алиаса. пример для страниц: translit(this,'#alias','page-')
 if(prefix===undefined){prefix='';}//по умолчанию - пустая строка
 var text=$(kiril).val();//значение из исходного поля
 var char={//объект для транслитерации
  'А':'A','Б':'B','В':'V','Г':'G','Д':'D','Е':'E','Ё':'Yo','Ж':'Zh','З':'Z','И':'I','Й':'Y','К':'K','Л':'L','М':'M','Н':'N','О':'O','П':'P','Р':'R','С':'S','Т':'T','У':'U','Ф':'F','Х':'Kh','Ц':'Ts','Ч':'Ch','Ш':'Sh','Щ':'Shch','Ъ':'','Ы':'Y','Ь':'','Э':'E','Ю':'Yu','Я':'Ya','Є':'E','І':'I','Ї':'Yi','Ґ':'G',
  'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh','з':'z','и':'i','й':'y','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'kh','ц':'ts','ч':'ch','ш':'sh','щ':'shch','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya','є':'e','і':'i','ї':'yi','ґ':'g'
 };
 var result='';
 for(var i=0;i<text.length;i++){
  if(char[text[i]]!==undefined){//символ найден - заменить его
    result+=char[text[i]];
  }else{//символ не найден - оставить как есть
   result+=!/\w|-|~/g.test(text[i])?'-':text[i];
  }
 }
 $(kiril).val($.trim($(kiril).val()).replace(/"([^"]+)"/g,"«$1»").replace(/"/g,"'"));//формат исходной строки (замена двойных кавычек)
 $(latin).val(prefix+result.replace(/-{2,}/g,'-').replace(/^-|-$/g,''));//результат в значение поля назначения
}

/////////////////////////////////////////////////////////////////////////генерирование пароля с вставкой в поле
function gen_pass(pass/*поле для вставки пароля*/){
 var passwd='',
     chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~@#$[]_-';
 for(i=1;i<11;i++){
  var c=Math.floor(Math.random()*chars.length+1);
  passwd+=chars.charAt(c);
 }
 $('#'+pass).val(passwd);
}

//////////////////////////////////////////////////////////////////////////проверка на уникальность title в таблице БД
function check_title(elem/*поле title*/,id/*id материала*/,tab/*таблица материала*/,msg/*сообщение если найдет совпадение*/){
 $.post(
  "/admin/check_title",
  {title:$(elem).val(),id:id,tab:tab},
  function(data){
   switch(data){
    case 'ok':
     $(elem).removeClass('novalid');
     $(':submit').attr('disabled',false);
    break;
    case 'found':
     $(elem).addClass('novalid').focus();
     $(':submit').attr('disabled',true);
     alert(msg);
    break;
    default:
     $(elem).addClass('novalid').focus();
     $(':submit').attr('disabled',true);
     alert(data);
   }
  }
 );
}

////////////////////////////////////////////////////////////////////////опубликовать\не опубликовывать аяксом
function toggle_public(elem/*для клика и вставки ответа сервера*/,id/*id страницы*/,tab/*искать в таблице*/,pub/*текущее on или off*/){
 $(elem).parent().load(
  "/admin/toggle_public",
  {id:id,tab:tab,pub:pub}
 );
}

////////////////////////////////////////////////////////////////////////удалить запись аяксом
function del_tab(elem/*для клика и вставки ответа сервера*/,id/*id страницы*/,tab/*искать в таблице*/){
 if(!confirm('Запись будет удалена!\nВыполнить действие?')){
  return false;
 }else{
  $(elem).parents('tr').load(
   "/admin/del",
   {id:id,tab:tab}
  );
 }
}

////////////////////////////////////////////////////////////////////////удалить раздел аяксом
function del_section(elem/*для клика и вставки ответа сервера*/,alias/*alias раздела*/){
 if(!confirm('Раздел и все комментарии к нему будут удалены, а дочерние разделы, галереи и страницы не будут пренадлежать ниодному разделу!\nВыполнить действие?')){
  return false;
 }else{
  $(elem).parents('tr').load(
   '/admin/section/del',
   {alias:alias}
  );
 }
}

////////////////////////////////////////////////////////////////////////удалить галерею аяксом
function del_gallery(elem/*для клика и вставки ответа сервера*/,alias/*alias галереи*/){
 if(!confirm('Галерея и все комментарии к ней будет удалены!\nВыполнить действие?')) {
  return false;
 }else{
  $(elem).parents('tr').load(
   '/admin/gallery/del',
   {alias:alias}
  );
 }
}

////////////////////////////////////////////////////////////////////////удалить страницу аяксом
function del_page(elem/*для клика и вставки ответа сервера*/,alias/*alias страницы*/){
 if(!confirm('Страница и все комментарии к ней будет удалены!\nВыполнить действие?')){
  return false;
 }else{
  $(elem).parents('tr').load(
   '/admin/page/del',
   {alias:alias}
  );
 }
}

////////////////////////////////////////////////////////////////////////вызов файлового менеджера
function files(
 field,/*(id/или id через запятую) поля в которое будет встален путь к файлу*/
 other_opt/*объект с допонительными опциями или опциями, перекрывающими основные*/){
 var insert=true;
 if(field===undefined||field===''){insert=false;field='';}
 var opt={//формирую основные опции
  title:'Менеджер файлов',
  leftpanel:false,
  width:720,
  height:400,
  rootpath:'/upload/',
  fields:field,
  insert:insert,
  onopen:setTimeout(files_notifer,500)
 };
 if(typeof other_opt==='object'){//параметр - объект
  $.extend(true,opt,other_opt);//объединение объектов
 }
 moxman.browse(opt);
 function files_notifer(){$('.moxman-window-head').after('<div style="padding:4px;background-color:#ffc0a2">Внимание! Не используйте кириллицу и пробелы в именах файлов и папок!</div>');}
}