$(function(){//готовность DOM

//////////////////////////////////////////////////////////////загрузка альтернативного изображения
 $('img').on('error',function(){
  $(this).attr('src','/img/noimg.jpg');
 });

//////////////////////////////////////////////////////////////показать в пункте меню (комментарии) колличество новых комментов
 $('#count_new_comments').load(
 "/admin/comment/get_count_new",
 function(data){
  var count=$('#count_new_comments');
  data>0?count.addClass('fa-bell-o red'):count.remove();
 }
 );

});//готовность DOM

////////////////////////////////////////////////превью изображения по url из поля
function img_prev(
targ,/*елемент с событием*/
input/*поле с url*/){
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
function off_notific(){
 document.getElementById('notific_work_info').style.display="none";
}
localStorage.getItem('notific_work_info')?off_notific():true;

/////////////////////////////////////////////////////////////////////////поиск по выпадающему списку
;
(function($){
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
      if(new RegExp(val,'i').test($(this).text())){
       return this;
      }
     });
     //проверить, нашло ли что-то. если нашло - наполнить список нашедшим, если нет - выдать "Не найдено"
     opt.length>0?self.html(opt):self.html('<option disabled>'+conf.noresult+'</option>');
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
  }else{
   $.error('Метод с именем '+method+' не существует');
  }
 };
})(jQuery);
window.addEventListener('load',function(){
 $('.SelectSearch').SelectSearch();//запуск плагина на всех select с классом SelectSearch
});

//////////////////////////////////////////////////отправка формы
function subm(
form/*this - проверяемая форма*/,
req/*обьект{имя поля:/рег.выражение/}*/){
 var self=$(form),
 control=self.find('.this_form_control');
 if(typeof req==='object'){//первый параметр - обьект, проверка полей
  for(var key in req){//перебор обьекта циклом
   var el=self.find('[name="'+key+'"]');//проверяемое поле
   if(el.length>1){//если группа полей типа name="name[]" или такие поля динамически создаются
    for(var i=0;el.length>i;++i){//перебрать коллекцию
     if(req[key].test(el[i].value)){//тест на регулярку проверки поля пройден
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
    if(req[key].test(el.val())){
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

/////////////////////////////////////////////////////////////////////////генерирование пароля с вставкой в поле
function gen_pass(
pass/*поле для вставки пароля*/){
 var passwd='',
 chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~@#$[]_-';
 for(i=1;i<11;i++){
  var c=Math.floor(Math.random()*chars.length+1);
  passwd+=chars.charAt(c);
 }
 $('#'+pass).val(passwd);
}

//////////////////////////////////////////////////////////////////////////проверка на уникальность title в таблице БД
function check_title(
elem/*поле title*/,
id/*id материала*/,
tab/*таблица материала*/,
msg/*сообщение если найдет совпадение*/){
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
function toggle_public(
el/*(this) ссылка "Опубликовать/не опубликовывать"*/,
id/*id материала*/,
tab/*таблица материала*/){
 var self=$(el),
 process=$('<i/>',{class:'fa fa-spin fa-spinner'});
 self.replaceWith(process);
 $.ajax({
  url:'/admin/toggle_public',
  type:'post',
  data:{id:id,tab:tab},
  dataType:'text',
  success:function(resp){
   switch(resp){
    case 'on':
     process.replaceWith(self.removeClass().addClass('fa-eye green'));
     break;
    case 'off':
     process.replaceWith(self.removeClass().addClass('fa-eye-slash red'));
     break;
    default :
     process.replaceWith(self);
     alert('Ой! Ошибка..( Повторите попытку.');
     console.log(resp);
   }
  },
  error:function(){
   process.replaceWith(self);
   alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
  }
 });
}

////////////////////////////////////////////////////////////////////////вызов файлового менеджера
function files(
field,/*(id/или id через запятую) поля в которое будет встален путь к файлу*/
lang,/*язык материала*/
other_opt/*объект с допонительными опциями или опциями, перекрывающими основные*/){
 var insert=true;
 if(typeof field==='undefined'||field===''){
  insert=false;
  field='';
 }
 var opt={//формирую основные опции
  title:'Менеджер файлов',
  view:'thumbs',
  leftpanel:false,
  width:720,
  height:400,
  rootpath:lang||(typeof lang!=='undefined')?'/upload/'+lang:'/upload/',
  fields:field,
  insert:insert,
  onopen:setTimeout(files_notifer,500)
 };
 if(typeof other_opt==='object'){//параметр - объект
  $.extend(true,opt,other_opt);//объединение объектов
 }
 moxman.browse(opt);
 function files_notifer(){
  $('.moxman-window-head').after('<div style="padding:4px;background-color:#ffc0a2">Внимание! Не используйте кириллицу и пробелы в именах файлов и папок!</div>');
 }
}