/*
 ###########################################################
 # @author Sergey Nizhnik kroloburet@gmail.com
 ###########################################################
*/

///////////////////////////////////////////////////////////
// FVGallery JS
///////////////////////////////////////////////////////////

;(function($){
 var _item,_conf,_content_box,_noactive;
 var _builder=function(){//построитель окна
  _item=this;//текущий элемент галереи
  _content_box=$('<div/>',{class:'FVG_pop_content_box',html:loader});//окно
  var close=$('<div/>',{class:'FVG_pop_close fa-times-circle'}),//кнопка деактивации окна
      prev=$('<div/>',{class:'FVG_pop_prev fa-angle-left'}).on('click.FVG',function(){_prev();}),//кнопка "назад"
      next=$('<div/>',{class:'FVG_pop_next fa-angle-right'}).on('click.FVG',function(){_next();}),//кнопка "вперед"
      loader='<div class="FVG_pop_loader"><i class="fa fa-spin fa-spinner"></i></div>',//лоадер
      pop=$('<div/>',{class:'FVG_pop',html:[close,next,prev,_content_box]}),//корневой контейнер окна
      back=$('<div/>',{class:'FVG_pop_back',html:pop});//фоновый контейнер окна
  $('body').append(back);//активировать окно
  _insert();//вставить в окно текущий элемент галереи
  back.add(close).on('click.FVG',function(e){if(e.target===this){back.remove();}});//деактивация окна по клику
 };
 var _insert=function(){//вставка в окно текущий элемент галереи
  _content_box.html('<div class="FVG_pop_loader"><i class="fa fa-spin fa-spinner"></i></div>');//запустить лоадер
  var opt=JSON.parse(_item.find('.opt').val());//объект с опциями текущего элемента
  switch(_conf.type){//тип галереи
   case 'foto_folder'://галерея из папки с изображениями
    var img=$('<img/>',{class:'FVG_pop_img',src:opt.f_url,alt:opt.f_url}).on('click.FVG',function(){_next();});
    _content_box.html(img);
    break;
   case 'foto_desc'://галерея изображений с описаниями
    var img=$('<img/>',{class:'FVG_pop_img',src:opt.f_url,alt:opt.f_title}).on('click.FVG',function(){_next();}),
        title=$('<h2/>',{class:'FVG_pop_desc_title',text:opt.f_title}),
        desc=$('<div/>',{class:'FVG_pop_desc_desc',text:opt.f_desc}),
        desc_box=opt.f_title||opt.f_desc?$('<div/>',{class:'FVG_pop_desc',html:[title,desc]}):null;
    img.on('load.FVG',function(){_content_box.html([img,desc_box]);desc_box.outerWidth(img.outerWidth());});
    break;
   case 'video_yt'://галерея youtube
    var yt_iframe_opt='?autoplay=1&controls=2&rel=0&modestbranding=1',
        iframe=$('<iframe/>',{class:'FVG_pop_video',src:'https://www.youtube.com/embed/'+opt.yt_id+yt_iframe_opt,allowfullscreen:true}),
        pop=_content_box.parent('.FVG_pop');
    pop.css('display','block');
    _content_box.html(iframe).addClass('FVG_video_wrap');
    break;
  }
 };
 var _prev=function(){//вставка предыдущего элемента галереи
  var prev_item=_item.prev('.FVG_item');
  _item=prev_item.length>0?prev_item:$('.FVG_item').last();
  _insert();
 };
 var _next=function(){//вставка следующего элемента галереи
  var next_item=_item.next('.FVG_item');
  _item=next_item.length>0?next_item:$('.FVG_item').first();
  _insert();
 };
//////////////////////////////////////////////////////
 var def_conf={//конфигурация по умолчанию
  type:'foto_folder'
 };
 var methods={//публичные методы
  init:function(user_conf){//инициализация плагина
   $('head').eq(0).append('<link href="/scripts/libs/FVGallery/FVGallery.css" rel="stylesheet">');//загрузить стили
   _conf=$.extend({},def_conf,user_conf);//получить конфигурацию
   var items=$('.FVG_item');//все элементы галереи
   items.find('img').each(function(){$(this).attr('src',$(this).data('src'));});//отложеная загрузка изображений
   _noactive=function(){//располагаются ли элементы в один столбец, чтобы не отрабатывать всплывающее окно. добравить/убрать стиль деактивации.
    if(items.eq(0).outerWidth(true)===items.parent('#FVGallery_layout').outerWidth(true)){items.addClass('FVG_noactive');return true;}
    else{items.removeClass('FVG_noactive');return false;}
   };
   setTimeout(_noactive,100);//без задержки ширина элемента определяется неправильно (хз почему)
   $(window).on('resize.FVG',_noactive);//следить за колонкой элементов
   return this.each(function(){//добавить события на все элементы
    $(this).on('click.FVG',function(e){//клик запускает окно
     if(_noactive()&&_conf.type!=='video_yt'){return false;}//элементы расположены в одну колонку
     e.preventDefault();
     _builder.apply($(this),null);
    });
    if(_conf.type==='foto_desc'){
     $(this).hover(function(e){//появление/скрытие описания
      if(_noactive()){return false;}//элементы расположены в одну колонку
      e.preventDefault();
      $(this).find('.FVG_item_f_desc_preview').slideToggle();
     });
    }
   });
  }
 };
//////////////////////////////////////////////////////
 $.fn.FVGallery=function(method){
  if(methods[method]){
   return methods[method].apply(this,Array.prototype.slice.call(arguments,1));
  }else if(typeof method==='object'||!method){
   return methods.init.apply(this,arguments);
  }else{$.error('Метод с именем '+method+' не существует');}
 };
})(jQuery);
