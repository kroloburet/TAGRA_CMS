/*
 ###########################################################
 # Developer: Sergey Nizhnik kroloburet@gmail.com
 ###########################################################
*/

///////////////////////////////////////////////////////////
// FVGallery JS
///////////////////////////////////////////////////////////

;(function($){
 //////////////////////////////////////////////////////////
 //приватное
 //////////////////////////////////////////////////////////
 var _item,_conf,_content_box;
 var _builder=function(){
  _item=this;
  _content_box=$('<div/>',{class:'FVG_pop_content_box',html:loader});
  var close=$('<div/>',{class:'FVG_pop_close fa-times-circle'}),//кнопка деактивации окна
      prev=$('<div/>',{class:'FVG_pop_prev fa-angle-left'}),//кнопка "назад"
      next=$('<div/>',{class:'FVG_pop_next fa-angle-right'}),//кнопка "вперед"
      loader='<div class="FVG_pop_loader"><i class="fa fa-spin fa-spinner"></i></div>',//лоадер
      pop=$('<div/>',{class:'FVG_pop',html:[close,next,prev,_content_box]}),//корневой контейнер окна
      back=$('<div/>',{class:'FVG_pop_back',html:pop});//фоновый контейнер окна
  $('body').append(back);//
  _insert();//
  /////////////////////////
  back.add(close).on('click.FVG',function(e){if(e.target===this){back.remove();}});//деактивировать окно при клике 
  /////////////////////////
  prev.on('click.FVG',function(e){
   e.preventDefault();
   _prev();
  });
  /////////////////////////
  next.on('click.FVG',function(e){
   e.preventDefault();
   _next();
  });
 };
 var _insert=function(){
  _content_box.html('<div class="FVG_pop_loader"><i class="fa fa-spin fa-spinner"></i></div>');
  var opt=JSON.parse(_item.find('.opt').val());
  switch(_conf.type){
   case 'foto_folder':
    var img=$('<img/>',{class:'FVG_pop_img',src:opt.f_url,alt:opt.f_url});
    _content_box.html(img);
    img.on('click.FVG',function(e){
     e.preventDefault();
     _next();
    });
    break;
   case 'foto_desc':
    var img=$('<img/>',{class:'FVG_pop_img',src:opt.f_url,alt:opt.f_title}),
        title=$('<h2/>',{class:'FVG_pop_desc_title',text:opt.f_title}),
        desc=$('<div/>',{class:'FVG_pop_desc_desc',text:opt.f_desc}),
        desc_box=opt.f_title||opt.f_desc?$('<div/>',{class:'FVG_pop_desc',html:[title,desc]}):null;
    img.on('load.FVG',function(){_content_box.html([img,desc_box]);desc_box.outerWidth(img.outerWidth());});
    img.on('click.FVG',function(e){
     e.preventDefault();
     _next();
    });
    break;
   case 'video_yt':
    var yt_iframe_opt='?autoplay=1&controls=2&rel=0&modestbranding=1',
        iframe=$('<iframe/>',{class:'FVG_pop_video',src:'http://www.youtube.com/embed/'+opt.yt_id+yt_iframe_opt,allowfullscreen:true}),
        pop=_content_box.parent('.FVG_pop');
    pop.css('display','block');
    _content_box.html(iframe).addClass('FVG_video_wrap');
    break;
  }
 };
 var _prev=function(){
  var prev_item=_item.prev('.FVG_item');
  if(prev_item.length>0){
   _item=prev_item;
   _insert();
  }
 };
 var _next=function(){
  var next_item=_item.next('.FVG_item');
  if(next_item.length>0){
   _item=next_item;
   _insert();
  }
 };
 //////////////////////////////////////////////////////////
 //публичное
 //////////////////////////////////////////////////////////
 var def_conf={
  type:'foto_folder'
 };
 var methods={
  init:function(user_conf){
   _conf=$.extend({},def_conf,user_conf);
   return this.each(function(){
    $(this).on('click.FVG',function(e){
     e.preventDefault();
     _builder.apply($(this),null);
    });
    if(_conf.type==='foto_desc'){
     $(this).hover(function(e){
      e.preventDefault();
      $(this).find('.FVG_item_f_desc_preview').slideToggle();
     });
    }
   });
  }
 };
 //////////////////////////////////////////////////////////
 //работа публичных методлв
 //////////////////////////////////////////////////////////
 $.fn.FVGallery=function(method){
  if(methods[method]){
   return methods[method].apply(this,Array.prototype.slice.call(arguments,1));
  }else if(typeof method==='object'||!method){
   return methods.init.apply(this,arguments);
  }else{$.error('Метод с именем '+method+' не существует');} 
 };
})(jQuery);
