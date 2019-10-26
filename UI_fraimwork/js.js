/*
 ###########################################################
 # @author Sergey Nizhnik kroloburet@gmail.com
 ###########################################################
 */

///////////////////////////////////////////////////////////
// UI_fraimwork JS
///////////////////////////////////////////////////////////

window.addEventListener('load',function(){
//################################################документ загружен

//////////////////////////////////////////////////меню
 var mobile_menu=$('.mobile_menu'),menu=$('.menu');
 $('.sub_menu').prev('a,span').addClass('sub_menu_link').end().after('<i class="sub_icon">&#8801;</i>');
 mobile_menu.on('click.UIF',function(){
  menu.slideToggle(200);
 });//показать\скрыть меню
 $(window).on('load.UIF resize.UIF',function(){
  mobile_menu.is(':hidden')?menu.show():menu.hide();
 });//
 $('.sub_icon').on('click.UIF',function(){
  $(this).prev('.sub_menu').slideToggle(200);
 });//показать\скрыть субменю
 menu.find('a').each(function(){//выделение ссылок с url совпавшими с url текущей страницы
  var l=window.location,h=$(this).attr('href');
  if(l.pathname===h||l.pathname+l.search===h||l.href===h){
   $(this).parents('li').addClass('active');
  }
 });

//////////////////////////////////////////////////////////////плавный скроллинг
 if(window.location.hash){//в строке запроса есть # — скролю на него
  $('body,html').animate({scrollTop:$(window.location.hash).offset().top},800);
 }

////////////////////////////////////////////////////////////////TABS
 $('dl.tabs').on('click.UIF','dt',function(){
  $(this).siblings().removeClass('tab_active').end().next('dd').addBack().addClass('tab_active');
 });

//////////////////////////////////////////////////////////////построитель модальных окон
 $('.popup').each(function(){
  var popup=$(this),
  popup_lay=$('<div/>',{class:'popup_lay'}),
  close_popup=$('<span/>',{class:'close_popup fa-times-circle'});
  popup.prepend(close_popup).wrapAll(popup_lay).css('display','inline-block');
 });

//////////////////////////////////////////////////input type="file"
 $('input[type="file"]').on('change.UIF',function(){
  var label=$(this).parent().find('span');
  if(typeof (this.files)!=='undefined'){//fucking IE
   if(this.files.length===0){
    label.removeClass('withFile').text(label.data('default'));
   }else{
    var name='',size=0;
    for(var i=0;i<this.files.length;++i){
     name+=this.files[i].name+' ';
     size+=this.files[i].size;
    }
    size=(size/1048576).toFixed(3);//size in mb
    label.addClass('withFile').text(name+' ('+size+'mb)');
   }
  }else{
   var name=this.value.split('\\');
   label.addClass('withFile').text(name[name.length-1]);
  }
 });

//////////////////////////////////////////////////input type="range"
 $('.range input[type="range"]').on('change.UIF',function(){
  $(this).parent().find('.range_val').html($(this).val());
 });

//////////////////////////////////////////////////tables
 $('table').each(function(){
  var $table=$(this),$bodyRows=$table.find('tbody tr'),$headCells=$table.find('thead th,thead td');
  $bodyRows.each(function(){
   var $row=$(this),$cells=$row.find('th,td');
   $cells.each(function(i){
    var $cell=$(this),title=$headCells.eq(i).text();
    $cell.attr('title',title);
   });
  });
 });
//##########################################################документ загружен
});

////////////////////////////////////////////////////////////показать модальное окно
function popup(el){
 var popup=$('#'+el),
 popup_lay=popup.parent('.popup_lay'),
 close_popup=popup.find('.close_popup');
 popup_lay.fadeIn().on('click.UIF',function(e){
  var e=e||window.event;
  if(e.target===this){
   popup_lay.fadeOut();
  }
 });
 close_popup.on('click.UIF',function(){
  popup_lay.fadeOut();
 });
}

////////////////////////////////////////////////////////////всплывающая подсказка
//функция всплытия элемента следующего после элемента=trig по событию обработчика;e_hide=событие для скрытия тултипа ('c' или '2c');
function tt(trig,e_hide){
 //инициализация переменных
 var win=$(window),tool=$(trig).next(),toolW=tool.width(),toolH=tool.height();
 trig.onmousemove=function(event){
  var e=event||window.event,
  mouse={x:e.pageX,y:e.pageY},//положение курсора
  distance={right:0,bottom:0};//дистанция до правого и нижнего края
  //считать растояния к правому, нижнему краю
  distance.right=win.width()-(mouse.x-win.scrollLeft());
  distance.bottom=win.height()-(mouse.y-win.scrollTop());
  //Если очень близко к правому краю, размещать слева от указателя мыши
  distance.right<toolW?tool.css('left',mouse.x-toolW-20+'px'):tool.css('left',20+mouse.x+'px');
  //Если очень близко к нижнему краю, размещать сверху над указателем мыши
  distance.bottom<toolH+30?tool.css('top',mouse.y-toolH-15+'px'):tool.css('top',15+mouse.y+'px');
 };
 //показать тултип
 tool.css({'visibility':'visible','z-index':'1000'});
 function HideTool(){ //функция прячет тултип
  tool.css({'visibility':'hidden','z-index':'-1000'});
 }
 //проверка переданного события, спрятать тултип
 switch(e_hide){
  case 'c':
   document.onclick=HideTool;
   break;//скрывает при клике
  case '2c':
   document.ondblclick=HideTool;
   break;//скрывает при даблклике
  default:
   trig.onmouseout=HideTool;
 }
}

//////////////////////////////////////////////////////////////скрол страницы по событию к targ
function scrll(targ){
 $('body,html').animate({scrollTop:$('#'+targ).offset().top},800);
}

//////////////////////////////////////////////////////////////свертывание/развертывание контента
function opn_cls(el/*id елемента*/){
 $('#'+el).slideToggle(200);
}

//////////////////////////////////////////////////////////////лимит ввода символов в поле
function lim(elm/*this*/,lim/*(число) - лимит символов в поле*/){
 var el=$(elm),//поле
 val=el.val(),//значение поля
 l=el.next('span.lim').get(0),//существующий елемент счетчика
 l_c=$(l).find('.lim_count');//контейнер подсчета в счетчике
 if(!l){//счетчик еще не создан
  var l_c=$('<i/>',{class:'lim_count',text:parseInt(lim,10)-val.length}),//контейнер подсчета в счетчике
  l=$('<span/>',{class:'lim fa-angle-left',html:l_c}),//счетчик
  el_p=el.css('padding-right');//отступ в поле (начальный)
  el.after(l).on('blur.UIF',function(){//вставка в DOM и потеря фокуса
   el.val(el.val().substr(0,parseInt(lim,10)));//обрезка строки если лимит превышен
   l.remove();//удаление счетчика
   el.css('padding-right',el_p);//вернуть начальный отступ в поле
  }).css('padding-right',parseInt(el_p,10)+l.outerWidth(true));//отступ в поле на длину счетчика
  l.css('margin-left','-'+l.outerWidth(true)+'px');//отрицательный отступ счетчика чтобы сдвинуть его в поле
 }
 if(val.length<=parseInt(lim,10)){//лимит не превышен
  l_c.text(parseInt(lim,10)-val.length);//обновить контейнер подсчета
 }else{//лимит превышен
  el.val(el.val().substr(0,parseInt(lim,10)));//обрезать строку
  l_c.text('0');//подсчет 0
 }
}