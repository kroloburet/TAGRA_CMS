<?php defined('BASEPATH') OR exit('No direct script access allowed');
/////////////////////////////////////////////////////////////////
$CI=&get_instance();
$prefix=$this->config->item('db_tabl_prefix');
$pages=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'pages')->result_array();
$sections=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'sections')->result_array();
$gallerys=$CI->db->select('title,alias,section')->order_by('title','ASC')->get($prefix.'gallerys')->result_array();
?>

<script src="<?=base_url('scripts/tinymce_4.7.11/tinymce.min.js')?>"></script>
<script>
var mce_link_list=[//выпадающий список ссылок на страницы сайта
 <?php if($pages){?>
  {title:'Страницы',menu:[
   <?php foreach($pages as $i){?>{title:'<?=$i['title']?>',value:'<?='/'.$i['alias']?>'},<?php }?>
  ]},
 <?php }?>
 <?php if($sections){?>
  {title:'Разделы',menu:[
   <?php foreach($sections as $i){?>{title:'<?=$i['title']?>',value:'<?='/section/'.$i['alias']?>'},<?php }?>
  ]},
 <?php }?>
 <?php if($gallerys){?>
  {title:'Галереи',menu:[
   <?php foreach($gallerys as $i){?>{title:'<?=$i['title']?>',value:'<?='/gallery/'.$i['alias']?>'},<?php }?>
  ]},
 <?php }?>
  {title:'Главная',value:'/'},
  {title:'Контакты',value:'/contact'}
 ];
var mce_overall_conf={//глобальная конфигурация редактора
 language:"ru",//язык редактора
 content_css:"/css/back/redactor.css",//стили для редактируемого контента
 menubar:false,
 element_format:"html",//теги в формате
 code_dialog_width:800,
 relative_urls:false,//относительные или абсолютные урлы
 remove_script_host:true,
 style_formats_merge:true,//добавлять или нет свои классы к классам по умолчанию в меню "формат"
 browser_spellcheck:true,//проверка орфографии
 forced_root_block:false,//оборачивать или нет в <p>
 valid_elements:"*[*]",//разрешенные
 //allow_script_urls: true,//разрешить\запретить внешние скрипты
 //invalid_elements:"strong,em",//запрещенные
 //extended_valid_elements:"img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]",//добавить разрешенные
 image_advtab:true,//расширенный диалог вставки изображения
 image_title: true,
 image_class_list:[//предустановленные классы для картинок
  {title:'Нет применять',value:''},
  {title:'По центру',value:'to_c'},
  {title:'Справа',value:'to_r'},
  {title:'Слева',value:'to_l'},
  {title:'Не обтекать',value:'to_none'}
 ],
 plugins:"advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code textcolor media table contextmenu paste nonbreaking moxiemanager fullscreen",
 toolbar:"undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table charmap link image media insertfile | fullscreen code",
 link_list:mce_link_list,
 ////////////////////////настройки файлового менеджера
 moxiemanager_rootpath:'/upload/',
 moxiemanager_title:'Mенеджер файлов',
 moxiemanager_leftpanel:false
}
//////////////////////////////////////////////////настройки текстового редактора по умолчанию
tinymce.init(Object.assign({},mce_overall_conf,{
 selector: "#layout_t,#layout_l,#layout_r,#layout_b,#conf_special,#special",
 inline: true//редактор появляется после клика в елементе
}));
//////////////////////////////////////////////////////////////////////////////////////инициализация текстового редактора
$(function(){//готовность DOM
var layouts=$("#layout_t,#layout_l,#layout_r,#layout_b");
layouts.on('click',function(){
 if($(this).hasClass('nav_layout_active')){
  return false;
 }else{
  layouts.removeClass('nav_layout_active');
  $(this).addClass('nav_layout_active');
 }
});
});
</script>