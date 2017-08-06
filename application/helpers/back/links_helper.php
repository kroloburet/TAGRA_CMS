<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
if(!function_exists('links')){
function links($opt=''){
$CI=&get_instance();
$prefix=$CI->config->item('db_tabl_prefix');
$sections=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'_sections')->result_array();
$gallerys=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'_gallerys')->result_array();
$pages=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'_pages')->result_array();
?>

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
будет выведен без заголовка.</pre><br>
<label class="input">
<input type="text" id="links_header" placeholder="Может оставаться пустым">
</label>
<a href="#" id="links_choice_btn"><i class="fa-plus-circle fa-lg blue"></i>&nbsp;Выбрать материалы</a>
<div id="links_choice">
 <label class="select">
 <select id="materials_select">
  <option value="pages" selected>Страницы</option>
  <option value="sections">Разделы</option>
  <option value="gallerys">Галереи</option>
 </select>
 </label>
 <div id="materials_viewer"></div>
</div>
<div id="prev_links_msg"></div>
<div id="prev_links"></div>
<textarea id="links_opt" class="no-emmet" name="links" hidden><?=$opt?></textarea>

<script>
;(function($){
 //////////////////////////////////////////////////////////
 //объявление приватных свойств по умолчанию
 //////////////////////////////////////////////////////////
 var _l_opt=$('#links_opt'),//textarea с опциями
     _opt=!_l_opt.val()?{}:JSON.parse(_l_opt.val()),//объект опций,
     _materials=$('<select/>',{class:'SelectSearch',size:5}),//список материалов,
     _pages='<?php if(empty($pages)){echo '<option>На сайте нет страниц</option>';}else{foreach($pages as $i){?><option value="/<?=$i['alias']?>"><?=$i['title']?></option><?php }}?>',
     _sections='<?php if(empty($sections)){echo '<option>На сайте нет разделов</option>';}else{foreach ($sections as $i){?><option value="<?='/section/'.$i['alias']?>"><?=$i['title']?></option><?php }}?>',
     _gallerys='<?php if(empty($gallerys)){echo '<option>На сайте нет галерей</option>';}else{foreach($gallerys as $i){?><option value="<?='/gallery/'.$i['alias']?>"><?=$i['title']?></option><?php }}?>';
     
 //////////////////////////////////////////////////////////
 //приватные методы
 //////////////////////////////////////////////////////////
 ////////////////////////получить уникальный id
 var _get_id=function(){return new Date().getTime().toString();};
 ////////////////////////отображение списка материалов
 var _materials_viewer=function(material){
  switch(material){
   case 'pages':_materials.html(_pages);break;
   case 'sections':_materials.html(_sections);break;
   case 'gallerys':_materials.html(_gallerys);break;
  }
  $('#materials_viewer').html(_materials);
  _materials.wrapAll('<label class="select"></label>').SelectSearch().on('change.Links',function(){_add();});
 };
 ////////////////////////показать превью ссылок
 var _show=function(){
  if($.isEmptyObject(_opt)){return false;}
  var clear_btn=$('<button/>',{type:'button',text:'Очистить все'}),
      clear_msg=$('<span/>',{text:'Чтобы удалить ссылку — кликните на ней в списке превью ниже.'}),
      preview_msg=$('#prev_links_msg'),
      preview=$('#prev_links').empty();
  $('#links_header').val(_opt.title);
  for(var k in _opt){
   if(_opt[k].url && _opt[k].title){
    var link=$('<span/>',{class:'fa-chain remove_link',id:k,title:'Удалить ссылку',text:' '+_opt[k].title});
    link.on('click.Links',function(){if(confirm('Эта ссылка будет удалена!\nВыполнить действие?'))_del($(this)[0].id);});
    preview.prepend(link);
   }
   preview_msg.html([clear_btn,clear_msg]);
   clear_btn.on('click.Links',function(){if(confirm('Все ссылки и заголовок блока будут удалены!\nВыполнить действие?'))_clear();});
  }
 };
 ////////////////////////добавить ссылку
 var _add=function(){
  var url=_materials.val(),
      added=false;
  if(url){
   for(var k in _opt){if(_opt[k].url===url){added=true;continue;}}//
   if(!added){_opt[_get_id()]={title:_materials.find('option:selected').text(),url:url};}
  }
  if(!$.isEmptyObject(_opt)){
   _opt.title=$.trim($('#links_header').val());
   _l_opt.val(JSON.stringify(_opt));
   _show();
  }
 };
 ////////////////////////удалить ссылку
 var _del=function(id){
  delete _opt[id];
  for(var k in _opt){
   if(k!=='title'){
    _l_opt.val(JSON.stringify(_opt));
    _show();
    return true;
   }
  }
  _clear();
 };
 ////////////////////////очистить все ссылки
 var _clear=function(){
  _opt={};
  _l_opt.add('#links_header').val('');
  $('#prev_links_msg,#prev_links').empty();
 };
 
 //////////////////////////////////////////////////////////
 //события
 //////////////////////////////////////////////////////////
 ////////////////////////показать\скрыть блок выбора материалов
 $('#links_choice_btn').on('click.Links',function(e){
  e.preventDefault();
  var choice=$('#links_choice');
  choice.slideToggle(200);
  choice.is(':hidden')?false:_materials_viewer($('#materials_select').val());
 });
 ////////////////////////отображение списка материалов
 $('#materials_select').on('change.Links',function(){_materials_viewer($(this).val());});
 ////////////////////////добавление ссылки на материал
 $('#links_header').on('change.Links',function(){_add();});
 
 //////////////////////////////////////////////////////////
 //после загрузки модуля
 //////////////////////////////////////////////////////////
 _show();//показать превью
 
}(jQuery));
</script>
<?php }}?>