<?php defined('BASEPATH') OR exit('No direct script access allowed');
///////////////////////////////////////////////////////////////////////////
if(!function_exists('display_menu_list')){//вывод меню сайта
 function display_menu_list($list){
  foreach($list as $i){
   echo '<li>'.PHP_EOL;
   //содержимое елемента списка
   echo $i['url']?'<a href="'.$i['url'].'" target="'.$i['target'].'">'.$i['title'].'</a>'.PHP_EOL:'<span>'.$i['title'].'</span>'.PHP_EOL;
   //если есть вложенные елементы
   if(isset($i['nodes'])){
    echo '<ul class="sub_menu">'.PHP_EOL;
    display_menu_list($i['nodes']);//рекурсия
    echo '</ul>'.PHP_EOL;
   }
   echo '</li>'.PHP_EOL;
  }
 }

}