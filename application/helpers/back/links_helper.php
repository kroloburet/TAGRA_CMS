<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('links')){
function links(){
$CI=&get_instance();
$data=$CI->app('data');
$m['pages']=$CI->db->where('lang',$data['lang'])->select('title,alias')->order_by('title','ASC')->get('pages')->result_array();
$m['sections']=$CI->db->where('lang',$data['lang'])->select('title,alias')->order_by('title','ASC')->get('sections')->result_array();
$m['gallerys']=$CI->db->where('lang',$data['lang'])->select('title,alias')->order_by('title','ASC')->get('gallerys')->result_array();
if(!empty($m['pages'])){foreach($m['pages'] as $k=>$v){$m['pages'][]=['title'=>$v['title'],'url'=>'/'.$v['alias']];unset($m['pages'][$k]);};}
if(!empty($m['sections'])){foreach($m['sections'] as $k=>$v){$m['sections'][]=['title'=>$v['title'],'url'=>'/section/'.$v['alias']];unset($m['sections'][$k]);};}
if(!empty($m['gallerys'])){foreach($m['gallerys'] as $k=>$v){$m['gallerys'][]=['title'=>$v['title'],'url'=>'/gallery/'.$v['alias']];unset($m['gallerys'][$k]);};}
$m=json_encode($m,JSON_FORCE_OBJECT);
?>

<div id="links">
 <h3 class="float_l">Связанные ссылки</h3> <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Здесь вы можете выбрать материалы сайта,
ссылки на которые будут показанны в отдельном
блоке на странице. Это может быть полезно когда
нужно предложить посетителю перейти на другие
материалы, а так же для перелинковки страниц.</pre>
 <hr>
 Заголовок блока <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
 <pre class="tt">
Заголовок блока связанных ссылок.
Например: «Смотрите так же»
Если поле не заполнено, блок ссылок
будет выведен без заголовка.</pre>
 <label class="input">
  <input type="text" class="links_header" placeholder="Может оставаться пустым">
 </label>
 <button type="button" class="links_add_btn">Добавить ссылки</button>
 <span class="prev_links_msg"></span>
 <div class="links_box">
  <label class="select">
   <select class="links_type">
    <option value="pages" selected>Страницы</option>
    <option value="sections">Разделы</option>
    <option value="gallerys">Галереи</option>
   </select>
  </label>
  <label class="select links_viewer"></label>
  <div class="button algn_r">
   <button type="button" class="links_done_btn">Готово</button>
  </div>
 </div>
 <div class="links_prev"></div>
 <textarea class="links_opt" class="no-emmet" name="links" hidden><?=isset($data['links'])?$data['links']:''?></textarea>
</div>

<script>
;(function($){
 var _=$('#links'),
     _header=_.find('.links_header'),//поле "Заголовок блока"
     _box=_.find('.links_box'),//контейнер полей
     _type=_.find('.links_type'),//список типа материала
     _viewer=_.find('.links_viewer'),//label списка материалов,
     _l_opt=_.find('.links_opt'),//поле для отправки
     _opt=!_l_opt.val()?{}:JSON.parse(_l_opt.val()),//объект ссылок,
     _m=<?=$m?>;//объект материалов
 ////////////////////////получить уникальный id
 var __get_id=function(){return new Date().getTime().toString();};
 ////////////////////////отображение списка материалов
 var __viewer=function(){
  var m=_m[_type.val()],
      s=$('<select/>',{class:'SelectSearch',size:5,html:'<option disabled>Нет материалов</option>'}),
      o;
  if(m){
   for(var i in m){o+='<option value="'+m[i].url+'">'+m[i].title+'</option>';}
   s.html(o);
  }
  _viewer.html(s);
  s.SelectSearch().on('change.Links',function(){__add();});
 };
 ////////////////////////показать превью ссылок
 var __show=function(){
  if($.isEmptyObject(_opt)){return false;}
  var clear_btn=$('<button/>',{type:'button',text:'Удалить все'}),
      clear_msg=$('<span/>',{text:'Чтобы удалить ссылку — кликните по ней в превью ниже.'}),
      preview=$('.links_prev').empty();
  _header.val(_opt.title);
  for(var i in _opt){
   if(_opt[i].url && _opt[i].title){
    var link=$('<span/>',{class:'fa-chain links_remove',id:i,title:'Удалить ссылку',text:' '+_opt[i].title});
    link.on('click.Links',function(){__del($(this)[0].id);});
    preview.prepend(link);
   }
  }
  _.find('.prev_links_msg').html([clear_btn,clear_msg]);
  clear_btn.on('click.Links',function(){if(confirm('Все ссылки и заголовок блока будут удалены!\nВыполнить действие?'))__del_all();});
 };
 ////////////////////////добавить ссылку
 var __add=function(){
  var url=_viewer.find('select').val(),
      added=false;
  if(url){
   for(var i in _opt){if(_opt[i].url===url){added=true;continue;}}//
   if(!added){_opt[__get_id()]={title:_viewer.find('select option:selected').text(),url:url};}
  }
  if(!$.isEmptyObject(_opt)){
   _opt.title=$.trim(_header.val());
   _l_opt.val(JSON.stringify(_opt));
   __show();
  }
 };
 ////////////////////////удалить
 var __del=function(id){
  if(!confirm('Эта ссылка будет удалена!\nВыполнить действие?')){return false;}
  delete _opt[id];
  for(var i in _opt){
   if(i!=='title'){
    _l_opt.val(JSON.stringify(_opt));
    __show();
    return true;
   }
  }
  __del_all();
 };
 ////////////////////////очистить все
 var __del_all=function(){
  _opt={};
  _l_opt.add(_header).val('');
  _.find('.prev_links_msg,.links_prev').empty();
  scrll('links');
 };
 ////////////////////////события
 _.find('.links_add_btn').on('click.Links',function(){_box.slideDown(200);scrll('links');__viewer();});//открыть форму добавления
 _.find('.links_done_btn').on('click.Links',function(){_box.slideUp(200);scrll('links');});//скрыть форму добавления
 _type.on('change.Links',function(){__viewer();});//отображение списка материалов
 _header.on('change.Links',function(){__add();});//добавление ссылки на материал
 ////////////////////////после загрузки модуля
 __show();
}(jQuery));
</script>
<?php }}?>
