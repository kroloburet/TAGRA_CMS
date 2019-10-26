<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('markup_data')){//разметка структурированных данных JSON-LD
function markup_data(){
global $CI,$conf,$data,$lexic;
$CI=&get_instance();
$data=$CI->app('data');
$conf=$CI->app('conf');
$lexic=$CI->app('lexic');
if(empty($data)||empty($conf)||empty($lexic)){return FALSE;}

////////////////////////////////////////////////
//переменные по умолчанию
////////////////////////////////////////////////
$seg1=$CI->uri->segment(1);//первый сегмент урл после домена
$data['lang']=isset($data['lang'])?$data['lang']:$conf['user_lang'];
$img_prev=empty($data['img_prev'])?empty($conf['img_prev_def'])?base_url('img/noimg.jpg'):$conf['img_prev_def']:$data['img_prev'];
$img_prev_size=@getimagesize($img_prev);
$creation_date=!empty($data['creation_date'])?$data['creation_date']:date('Y-m-d');
$last_mod_date=!empty($data['last_mod_date'])?$data['last_mod_date']:date('Y-m-d');
$layout=@$data['layout_t'].@$data['layout_l'].@$data['layout_r'].@$data['layout_b'];
$imgs=$audios=$cmnts=$cmnts_count=$breadcrumb=$tel=$mail=$address='';

if(!empty($layout)){
////////////////////////////////////////////////
//обработка контента
////////////////////////////////////////////////
//изображения в контенте
 preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i',$layout,$layout_imgs);
 if(!empty($layout_imgs[1])){
  foreach($layout_imgs[1] as $v){
   if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$v)){continue;}//тип не разрешен
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

if(isset($data['gallery_opt'])&&$data['gallery_opt']){
////////////////////////////////////////////////
//обработка галерей
////////////////////////////////////////////////
 switch($data['gallery_type']){
  //изображения в галерее
  case'foto_folder'://галерея из папки с изображениями
   function get_foto_folder_srcs($dir){
    $result='';
    if($dir_handle=@opendir('.'.$dir)){//открыть папку
     while($file=readdir($dir_handle)){//проход по файлам
      if($file=='.'||$file=='..')continue;//пропустить ссылки на другие папки
      if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$file)){continue;}//тип не разрешен
      $result.='{"@type":"ImageObject","url":"'.base_url($dir.'/'.$file).'"}';
     }
     closedir($dir_handle);//закрыть папку
    }
    return $result;
   }
   $imgs.=get_foto_folder_srcs(json_decode($data['gallery_opt'],TRUE)['f_folder']);
   break;
  case'foto_desc'://галерея изображений с описаниями
   foreach(json_decode($data['gallery_opt'],TRUE) as $v){//проход по изображениям
    if(!preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png)$/i',$v['f_url'])){continue;}//тип не разрешен
    $v['f_url']=preg_match('/^https?:\/\//i',$v['f_url'])?$v['f_url']:base_url($v['f_url']);//url должен быть абсолютный
    $imgs.='{"@type":"ImageObject","name":'.json_encode($v['f_title']).',"description":'.json_encode($v['f_desc']).',"url":"'.$v['f_url'].'"}';
   }
   break;
  //аудио в галерее
  case'audio':
   foreach(json_decode($data['gallery_opt'],TRUE) as $v){//проход по аудио
    $v['a_url']=preg_match('/^https?:\/\//i',$v['a_url'])?$v['a_url']:base_url($v['a_url']);//url должен быть абсолютный
    $audios.='{"@type":"AudioObject","name":'.json_encode($v['a_title']).',"url":"'.$v['a_url'].'"}';
   }
   break;
 }
}

if(isset($data['comments'])&&$data['comments']!=='off'){
////////////////////////////////////////////////
//обработка комментариев
////////////////////////////////////////////////
 $q=$CI->db->where(['public'=>'on','url'=>uri_string()])->get('comments')->result_array();//выборка комментариев
if(!empty($q)){//комментарии есть
 $tree_arr=[];
 foreach(array_reverse($q) as $v){$tree_arr[$v['pid']][]=$v;}//получить многомерный массив
 function build_tree($tree_arr,$pid=0){//построение дерева
   if(!is_array($tree_arr)||!isset($tree_arr[$pid])){return false;}//нет данных
  $tree='';
  foreach($tree_arr[$pid] as $v){
   $name=filter_var($v['name'],FILTER_VALIDATE_EMAIL)?explode('@',$v['name'])[0]:$v['name'];
    $tree.='{"@type":"Comment","datePublished":"'.$v['date'].'","text":'.json_encode($v['comment']).',"creator":{"@type":"Person","name":'.json_encode($name).'}}';
   $tree.=build_tree($tree_arr,$v['id']);
  }
  return $tree;
 }
 $cmnts_count=count($q);//всего комментариев
 $cmnts=build_tree($tree_arr);
 }
}

if(isset($conf['breadcrumbs']['public'])&&$conf['breadcrumbs']['public']=='on'){
////////////////////////////////////////////////
//обработка "хлебных крошек"
////////////////////////////////////////////////
 global $breadcrumb;//цепь
 $home=isset($conf['breadcrumbs']['home'])&&$conf['breadcrumbs']['home']=='on'?'{"@type":"ListItem","position":1,"name":'.json_encode($lexic['breadcrumbs']['home']).',"item":"'.base_url().'"}':'';//главная в цепи
 $breadcrumb=$home;//добавить главную в цепь
 function get_sub_sections($id,$pos){//добавить в цепь подразделы
  //$id-id родительского раздела в цепи
  //$pos-позиция звена в цепи
  global $CI,$data,$breadcrumb;
  $q=$CI->db->where(['public'=>'on','id'=>$id,'lang'=>$data['lang']])->select('title,id,section')->get('sections')->result_array();
  if(isset($q[0])&&!empty($q[0])){//такой id есть
   if($q[0]['section']){get_sub_sections($q[0]['section'],$pos);$pos=$pos+1;}//есть родитель - рекурсия;
   $breadcrumb.='{"@type":"ListItem","position":'.$pos.',"name":'.json_encode($q[0]['title']).',"item":"'.base_url('section/'.$q[0]['id']).'"}';
  }
 }
 if(@$data['section']){get_sub_sections($data['section'],$home?2:1);}//етсть раздел
 }

if($q=$CI->db->where('lang',$data['lang'])->get('contact_pages')->result_array()[0]['contacts']){//есть json с контактами
////////////////////////////////////////////////
//обработка контактов
////////////////////////////////////////////////
 $t=$m=[];//будут хранить все телефоны, emailы
 foreach(json_decode($q,TRUE) as $v){//json в массив и обход
  $t=$v['tel']?array_merge($t,explode(',',$v['tel'])):$t;//записать в массив
  $m=$v['mail']?array_merge($m,explode(',',$v['mail'])):$m;//записать в массив
  $address.='{"@type":"PostalAddress","streetAddress":'.json_encode($v['address']).'}';
 }
 $tel=implode(',',array_map(function($i){return '"'.preg_replace('/\s+/','',$i).'"';},array_unique($t)));//в строку, в кавычки, оставить уникальные
 $mail=implode(',',array_map(function($i){return '"'.preg_replace('/\s+/','',$i).'"';},array_unique($m)));//в строку, в кавычки, оставить уникальные
}

////////////////////////////////////////////////
//вывод разметки
////////////////////////////////////////////////
?>
<!--####### разметка структурированных данных #######-->
<!--Google-->
<meta itemprop="name" content="<?=htmlspecialchars($conf['site_name'])?>">
<meta itemprop="description" content="<?=htmlspecialchars($data['description'])?>">
<meta itemprop="image" content="<?=htmlspecialchars($img_prev)?>">
<!--Twitter-->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="<?=htmlspecialchars($conf['site_name'])?>">
<meta name="twitter:title" content="<?=htmlspecialchars($data['title'])?>">
<meta name="twitter:description" content="<?=htmlspecialchars($data['description'])?>">
<meta name="twitter:image:src" content="<?=htmlspecialchars($img_prev)?>">
<meta name="twitter:domain" content="<?=base_url()?>">
<!--Facebook-->
<meta property="og:title" content="<?=htmlspecialchars($data['title'])?>">
<meta property="og:description" content="<?=htmlspecialchars($data['description'])?>">
<meta property="og:image" content="<?=htmlspecialchars($img_prev)?>">
<meta property="og:image:width" content="<?=@$img_prev_size[0]?@$img_prev_size[0]:'1200'?>">
<meta property="og:image:height" content="<?=@$img_prev_size[1]?@$img_prev_size[1]:'630'?>">
<meta property="og:url" content="<?=current_url()?>">
<meta property="og:site_name" content="<?=htmlspecialchars($conf['site_name'])?>">
<!--Другие-->
<link rel="image_src" href="<?=htmlspecialchars($img_prev)?>">
<!--разметка контактов-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"Organization",
"name":<?=json_encode($conf['site_name'])?>,
"url":"<?=base_url()?>",
"logo":"<?=$img_prev?>"
<?php if(!empty($mail)){?>,"email":[<?=$mail?>]<?php }?>
<?php if(!empty($tel)){?>,"telephone":[<?=$tel?>]<?php }?>
<?php if($address){?>,"address":[<?=preg_replace('/\}\{/m','},{',$address)?>]<?php }?>
}
</script>
<?php if($seg1!=='contact'){//все кроме страницы "контакты""?>
<?php if($breadcrumb){?>
<!--разметка "хлебных крошек"-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"BreadcrumbList",
"itemListElement":[<?=preg_replace('/\}\{/m','},{',$breadcrumb)?>]
}
</script>
<?php }?>
<!--разметка статьи-->
<script type="application/ld+json">
{
"@context":"http://schema.org",
"@type":"Article",
"mainEntityOfPage":{"@type":"WebPage","@id":"<?=current_url()?>"},
"headline":<?=json_encode($data['title'])?>,
"description":<?=json_encode($data['description'])?>,
"datePublished":"<?=$creation_date?>",
"dateModified":"<?=$last_mod_date?>",
"author":{"@type":"Person","name":<?=json_encode($conf['site_name'])?>},
"publisher":{"@type":"Organization","name":<?=json_encode($conf['site_name'])?>,"logo":"<?=$img_prev?>"},
"image":[{"@type":"ImageObject","representativeOfPage":true,"url":"<?=$img_prev?>"}<?=$imgs?','.preg_replace('/\}\{/m','},{',$imgs):FALSE?>]
<?php if($audios){?>,"audio":[<?=preg_replace('/\}\{/m','},{',$audios)?>]<?php }?>
<?php if($cmnts){?>,"commentCount":"<?=$cmnts_count?>","comment":[<?=preg_replace('/\}\{/m','},{',$cmnts)?>]<?php }?>
}
</script>
<?php }}}