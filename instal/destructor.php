<?php

//перенаправление
header("Location: /");

//удаление папки инсталятора (родительская папка этого скрипта)
function delTree($dir){
 $files=array_diff(scandir($dir),array('.','..'));
 foreach($files as $file){(is_dir("$dir/$file"))?delTree("$dir/$file"):unlink("$dir/$file");}
 return rmdir($dir);
}
delTree(dirname(__FILE__));
