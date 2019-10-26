<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('versions')){
function versions($material){
$CI=&get_instance();
if(count($CI->app('conf.langs'))<2){return false;}//в системе один язык
$data=$CI->app('data');
$m=$CI->db->where('lang !=',$data['lang'])->select('title,id,lang')->order_by('title','ASC')->get($material)->result_array();
foreach($m as $k=>$v){
 switch($material){
  case 'pages':$url='/page/'.$v['id'];break;
  case 'sections':$url='/section/'.$v['id'];break;
  case 'gallerys':$url='/gallery/'.$v['id'];break;
 }
 $m[$v['lang']][$v['id']]=['id'=>$v['id'],'title'=>$v['title'],'url'=>$url];
 unset($m[$k]);
}
$m=json_encode($m,JSON_FORCE_OBJECT);
?>

<div class="touch" id="versions">
 <h3 class="float_l">Версии материала</h3> <i class="fa-info-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Здесь вы можете выбрать версии этого
материала на других языках ресурса.
Когда пользователь изменит язык ресурса,
находясь на странице этого материала,
он будет перенаправлен на выбранный вами
материал.</pre>
 <hr>
 <button type="button" class="v_add_btn">Добавить версии</button>
 <div class="v_box">
  <label class="select">
   <select class="v_lang">
    <?php foreach($CI->app('conf.langs') as $v){if($v['tag']!==$data['lang']){?>
    <option value="<?=$v['tag']?>"><?="{$v['title']} [{$v['tag']}]"?></option>
    <?php }}?>
   </select>
  </label>
  <label class="select v_viewer"></label>
  <div class="button algn_r">
   <button type="button" class="v_done_btn">Готово</button>
  </div>
 </div>
 <textarea class="v_opt" class="no-emmet" name="versions" hidden><?=isset($data['versions'])?$data['versions']:''?></textarea>
 <div class="v_prev"></div>
</div>

<script>
;(function($){
 var _=$('#versions'),//контейнер модуля
     _add_btn=_.find('.v_add_btn'),//кнопка "Добавить версии"
     _del_all_btn=$('<button/>',{type:'button',text:'Удалить все'}).on('click.Versions',function(){__del_all();}),//кнопка "Удалить все"
     _box=_.find('.v_box'),//контейнер полей
     _lang=_.find('.v_lang'),//список языков
     _viewer=_.find('.v_viewer'),//label списка материалов языка
     _prev=_.find('.v_prev'),//превью версий
     _v_opt=_.find('.v_opt'),//поле для отправки
     _opt=!_v_opt.val()?{}:JSON.parse(_v_opt.val()),//объект версий материала
     _m=<?=$m?>;//объект материалов языков
 ////////////////////////////////скрыть, очистить форму
 var __clear=function(){
  _box.slideUp(200);
  scrll('versions');
  _lang.attr('disabled',false);
 };
 ////////////////////////////////открыть форму добавления
 var __add_form=function(){
  __clear();
  _lang.change();//показать список материалов языка
  _box.slideDown(200);
  scrll('versions');
 };
 ////////////////////////////////открыть форму редактирования
 var __edit_form=function(tag){
  _lang.val(tag).attr('disabled',true);//установить язык и заблокировать список
  _lang.change();//показать список материалов языка
  _box.slideDown(200);
  scrll('versions');
 };
 ////////////////////////////////добавить/редактировать
 var __set=function(){
  var l=_lang.val(),//выбранный язык
      m=_viewer.find('select').val();//выбранный материал
  if(!_m[l]||!m){return false;}
  _opt[l]=_m[l][m];//записать или перезаписать версию языка
  _v_opt.val(JSON.stringify(_opt));//обновить поле отправки
  __show();
 };
 ////////////////////////////////удалить
 var __del=function(tag){
  if(!_opt[tag]||!confirm('Эта версия будет удалена!\nВыполнить действие?')){return false;}
  __clear();
  delete _opt[tag];
  if($.isEmptyObject(_opt)){
   _prev.empty();
   _v_opt.val('');
   _del_all_btn.detach();
  }else{
   _v_opt.val(JSON.stringify(_opt));
   __show();
  }
 };
 ////////////////////////////////удалить все
 var __del_all=function(){
  if(!confirm('Все версии будут удалены!\nВыполнить действие?')){return false;}
  __clear();
  _opt={};
  _prev.empty();
  _v_opt.val('');
  _del_all_btn.detach();
 };
 ////////////////////////////////отобразить превью
 var __show=function(){
  if($.isEmptyObject(_opt)){return false;}
  _add_btn.after(_del_all_btn);//добавить кнопку "удалить все"
  _prev.empty();//очистить превью
  for(var i in _opt){//заполнять превью
   var str='<span class="v_prev_lang">'+i+'</span><span>'+_opt[i].title.substring(0,100)+'</span>',
       edit_btn=$('<div/>',{class:'v_prev_edit_btn fa-edit',title:'Редактировать'}).data('tag',i),
       del_btn=$('<div/>',{class:'v_prev_del_btn fa-trash-o',title:'Удалить'}).data('tag',i),
       prev_str_box=$('<div/>',{class:'v_prev_str_box',html:str}),
       control_box=$('<div/>',{class:'v_prev_control',html:[edit_btn,del_btn]}),
       prev_item=$('<div/>',{class:'v_prev_item',html:[prev_str_box,control_box]});
   edit_btn.on('click.Versions',function(){__edit_form($(this).data('tag'));scrll('versions');});
   del_btn.on('click.Versions',function(){__del($(this).data('tag'));});
   _prev.append(prev_item);
  }
 };
 ////////////////////////////события
 _add_btn.on('click.Versions',function(){__add_form();});//открыть форму добавления
 _.find('.v_done_btn').on('click.Versions',function(){__clear();});//скрыть, очистить форму
 _lang.on('change.Versions',function(){//заполнить список материалами выбранного языка
  var s=$('<select/>',{class:'SelectSearch',size:5,html:'<option disabled>Нет материалов</option>'}),
      m=_m[$(this).val()],
      o;
  if(m){
   for(var i in m){o+='<option value="'+i+'">'+m[i].title+'</option>';}
   s.html(o);
  }
  _viewer.html(s);
  s.SelectSearch().on('change.Versions',function(){__set();});
 });
 ///////////////////////////после загрузки модуля
 __show();
}(jQuery));
</script>
<?php }}?>