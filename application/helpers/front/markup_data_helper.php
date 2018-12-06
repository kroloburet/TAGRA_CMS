<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('markup_data')){//разметка структурированных данных JSON-LD
function markup_data($data){
if(empty($data)){return FALSE;}
extract($data);

////////////////////////////////////////////////
//переменные по умолчанию
////////////////////////////////////////////////
global $CI,$prefix;
$CI=&get_instance();
$prefix=$CI->config->item('db_tabl_prefix');
$seg1=$CI->uri->segment(1);//первый сегмент урл после домена
$seg2=$CI->uri->segment(2);//второй сегмент урл после домена
$img_prev=empty($img_prev)?empty($conf_img_prev_def)?base_url('img/noimg.jpg'):$conf_img_prev_def:$img_prev;
$img_prev_size=@getimagesize($img_prev);
$creation_date=!empty($creation_date)?$creation_date:date('Y-m-d');
$last_mod_date=!empty($last_mod_date)?$last_mod_date:date('Y-m-d');
$layout=@$layout_l.@$layout_r.@$layout_t.@$layout_b;
$imgs=$audios=$cmnts=$cmnts_count=$breadcrumb_list=$tel=$mail=$address='';

if(!empty($layout)){
////////////////////////////////////////////////
//обработка контента
////////////////////////////////////////////////
//изображения в контенте
 preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i',$layout,$layout_imgs);
 if(!empty($layout_imgs[1])){
  foreach($layout_imgs[1] as $v){
   if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$v))continue;//если тип не разрешен
   $v=preg_match('/^https?:\/\//i',$v)?$v:base_url($v);//url должен быть абсолютный
   $imgs.='{"@type":"ImageObject","url":"'.$v.'"}';
  }
 }
//аудио в контенте
 preg_match_all('/<audio[^>]+src="([^"]+)"[^>]*>/i',$layout,$layout_audios);
 if(!empty($layout_audios[1])){
  foreach($layout_audios[1] as $v){
   $v=preg_match('/^https?:\/\//i',$v)?$v:base_url($v);//url должен быть абсолютный
   $audios.='{"@type":"AudioObject","url":"'.$v.'"}';
  }
 }
}

if(isset($gallery_opt)&&$gallery_opt){
////////////////////////////////////////////////
//обработка галерей
////////////////////////////////////////////////
 switch($gallery_type){
  //изображения в галерее
  case'foto_folder'://галерея из папки с изображениями
   function get_foto_folder_srcs($dir){
    $result='';
    if($dir_handle=@opendir('.'.$dir)){//пробуем открыть папку
     while($file=readdir($dir_handle)){//поиск по файлам
      if($file=='.'||$file=='..')continue;//пропустить ссылки на другие папки
      if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$file))continue;//если тип не разрешен
      $result.='{"@type":"ImageObject","url":"'.base_url($dir.'/'.$file).'"}';
     }
     closedir($dir_handle);//закрыть папку
    }
    return $result;
   }
   $imgs.=get_foto_folder_srcs(json_decode($gallery_opt,TRUE)['f_folder']);
   break;
  case'foto_desc'://галерея изображений с описаниями
   foreach(json_decode($gallery_opt,TRUE) as $v){//читаю json изображений
    if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$v['f_url']))continue;//если тип не разрешен
    $v['f_url']=preg_match('/^https?:\/\//i',$v['f_url'])?$v['f_url']:base_url($v['f_url']);//url должен быть абсолютный
    $imgs.='{"@type":"ImageObject","name":"'.$v['f_title'].'","description":"'.$v['f_desc'].'","url":"'.$v['f_url'].'"}';
   }
   break;
  //аудио в галерее
  case'audio':
   foreach(json_decode($gallery_opt,TRUE) as $v){//читаю json аудио
    $v['a_url']=preg_match('/^https?:\/\//i',$v['a_url'])?$v['a_url']:base_url($v['a_url']);//url должен быть абсолютный
    $audios.='{"@type":"AudioObject","name":"'.$v['a_title'].'","url":"'.$v['a_url'].'"}';
   }
   break;
 }
}

if(isset($comments)&&$comments!=='off'){
////////////////////////////////////////////////
//обработка комментариев
////////////////////////////////////////////////
$url=uri_string();
$q=$CI->db->where(array('public'=>'on','url'=>$url))->get($prefix.'comments')->result_array();//выборка комментариев
if(!empty($q)){//комментарии есть
 $tree_arr=array();
 foreach(array_reverse($q) as $v){$tree_arr[$v['pid']][]=$v;}//получить многомерный массив
 function build_tree($tree_arr,$pid=0){//построение дерева
  if(!is_array($tree_arr) || !isset($tree_arr[$pid])){return false;}//нет данных
  $tree='';
  foreach($tree_arr[$pid] as $v){
   $name=filter_var($v['name'],FILTER_VALIDATE_EMAIL)?explode('@',$v['name'])[0]:$v['name'];
   $tree.='{"@type":"Comment","datePublished":"'.$v['date'].'","text":"'.$v['comment'].'","creator":{"@type":"Person","name":"'.$name.'"}}';
   $tree.=build_tree($tree_arr,$v['id']);
  }
  return $tree;
 }
 $cmnts_count=count($q);//всего комментариев
 $cmnts=build_tree($tree_arr);
 }
}

if(isset($conf_breadcrumbs_public)&&$conf_breadcrumbs_public=='on'){
////////////////////////////////////////////////
//обработка "хлебных крошек"
////////////////////////////////////////////////
 global $breadcrumb_list;//объявляю лист
 $q=array();//будет хранить выборку
 $home=$conf_breadcrumbs_home!==''?'{"@type":"ListItem","position":1,"name":"'.$conf_breadcrumbs_home.'","item":"'.base_url().'"}':'';//главная в цепи
 $breadcrumb_list=$home;//лист+главная
 function get_sub_sections(/*алиас родительского раздела в цепи*/$sect,/*позиция в цепочке для разметки*/$pos){//дополнить лист цепочкой подразделов
  global $CI,$prefix,$breadcrumb_list;
  if($d=$CI->front_basic_model->get_where_alias($prefix.'sections',$sect)){//если такой алиас есть
   if($d['section']){get_sub_sections($d['section'],$pos);$pos=$pos+1;}//если есть родитель - рекурсия;
   $breadcrumb_list.='{"@type":"ListItem","position":'.$pos.',"name":"'.$d['title'].'","item":"'.base_url('section/'.$d['alias']).'"}';
  }
 }
 switch($seg1){//это:
  case'section':$q=$CI->front_basic_model->get_where_alias($prefix.'sections',$seg2);break;//раздел
  case'gallery':$q=$CI->front_basic_model->get_where_alias($prefix.'gallerys',$seg2);break;//галерея
  default:$q=$CI->front_basic_model->get_where_alias($prefix.'pages',$seg1);//страница
 }
 if(!empty($q)){//выборка не пуста
  if(@$q['section']){get_sub_sections($q['section'],$home?2:1);}//етсть раздел
 }
}

if($q=$CI->db->get($prefix.'contact_page')->result_array()[0]['contacts']){//есть json с контактами
////////////////////////////////////////////////
//обработка контактов
////////////////////////////////////////////////
 $tel=$mail=array();//будут хранить все телефоны, emailы
 foreach(json_decode($q,TRUE) as $v){//json в массив и обход
  $tel=$v['tel']?array_merge($tel,explode(',',$v['tel'])):$tel;//записать в массив
  $mail=$v['mail']?array_merge($mail,explode(',',$v['mail'])):$mail;//записать в массив
  $address.='{"@type":"PostalAddress","streetAddress":"'.$v['address'].'"}';
 }
 $tel=implode(',',array_map(function($i){return '"'.preg_replace('/\s+/','',$i).'"';},array_unique($tel)));//в строку, в кавычки, оставить уникальные
 $mail=implode(',',array_map(function($i){return '"'.preg_replace('/\s+/','',$i).'"';},array_unique($mail)));//в строку, в кавычки, оставить уникальные
}

////////////////////////////////////////////////
//вывод разметки
////////////////////////////////////////////////
?>
<!--####### разметка структурированных данных #######-->
<!-------------соцсети-->
<!--Google-->
<meta itemprop="name" content="<?=$conf_site_name?>">
<meta itemprop="description" content="<?=$description?>">
<meta itemprop="image" content="<?=$img_prev?>">
<!--Twitter-->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="<?=$conf_site_name?>">
<meta name="twitter:title" content="<?=$title?>">
<meta name="twitter:description" content="<?=$description?>">
<meta name="twitter:image:src" content="<?=$img_prev?>">
<meta name="twitter:domain" content="<?=base_url()?>">
<!--Facebook-->
<meta property="og:title" content="<?=$title?>">
<meta property="og:description" content="<?=$description?>">
<meta property="og:image" content="<?=$img_prev?>">
<meta property="og:image:width" content="<?=@$img_prev_size[0]?@$img_prev_size[0]:'1200'?>">
<meta property="og:image:height" content="<?=@$img_prev_size[1]?@$img_prev_size[1]:'630'?>">
<meta property="og:url" content="<?=current_url()?>">
<meta property="og:site_name" content="<?=$conf_site_name?>">
<!--Другие-->
<link rel="image_src" href="<?=$img_prev?>">
<!-------------JSON-LD-->
<!--разметка контактов-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"Organization",
"name":"<?=$conf_site_name?>",
"url":"<?=base_url()?>",
"logo":"<?=$img_prev?>"
<?php if(!empty($mail)){?>,"email":[<?=$mail?>]<?php }?>
<?php if(!empty($tel)){?>,"telephone":[<?=$tel?>]<?php }?>
<?php if($address){?>,"address":[<?=preg_replace('/\}\{/m','},{',$address)?>]<?php }?>
}
</script>
<?php if($seg1!=='contact'){//все кроме страницы "контакты""?>
<?php if($breadcrumb_list){?>
<!--разметка "хлебных крошек"-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"BreadcrumbList",
"itemListElement":[<?=preg_replace('/\}\{/m','},{',$breadcrumb_list)?>]
}
</script>
<?php }?>
<!--разметка статьи-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"Article",
"mainEntityOfPage":{"@type":"WebPage","@id":"<?=current_url()?>"},
"headline":"<?=$title?>",
"description":"<?=$description?>",
"datePublished":"<?=$creation_date?>",
"dateModified":"<?=$last_mod_date?>",
"author":{"@type":"Person","name":"<?=$conf_site_name?>"},
"publisher":{"@type":"Organization","name":"<?=$conf_site_name?>","logo":"<?=$img_prev?>"},
"image":[{"@type":"ImageObject","representativeOfPage":true,"url":"<?=$img_prev?>"},<?=$imgs?preg_replace('/\}\{/m','},{',$imgs):FALSE?>]
<?php if($audios){?>,"audio":[<?=preg_replace('/\}\{/m','},{',$audios)?>]<?php }?>
<?php if($cmnts){?>,"commentCount":"<?=$cmnts_count?>","comment":[<?=preg_replace('/\}\{/m','},{',$cmnts)?>]<?php }?>
}
</script>
<?php }}}