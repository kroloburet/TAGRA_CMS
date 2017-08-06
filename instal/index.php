<!DOCTYPE html>
<html>
 <head>
  <meta name="generator" content="Powered by «Tagra CMS» Development and design by kroloburet@gmail.com">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="none">
  <link href="/UI_fraimwork/css.css" rel="stylesheet">
  <link href="/UI_fraimwork/fonts/FontAwesome/style.css" rel="stylesheet">
  <link href="css.css" rel="stylesheet">
  <!--[if lt IE 8]>
  <link href="/UI_fraimwork/fonts/FontAwesome/ie7/ie7.css" rel="stylesheet">
  <!--<![endif]-->
  <title>Установка системы управления контентом</title>
 </head>
 <body>
  <div id="content">
<?php
$cms_path=str_replace('/instal', '', dirname(__FILE__));//абсолютный путь к директории с CMS
$f1='/application/';
$f2='/application/config/';
$f3='/application/config/config.php';
$f4='/application/config/database.php';
$f5='/scripts/';
$f6='/scripts/back/';
$f7='/scripts/back/overall_js.js';
if(//если файлы и папки доступны для записи
   @is_writable($cms_path.$f1)&&
   @is_writable($cms_path.$f2)&&
   @is_writable($cms_path.$f3)&&
   @is_writable($cms_path.$f4)&&
   @is_writable($cms_path.$f5)&&
   @is_writable($cms_path.$f6)&&
   @is_writable($cms_path.$f7)
  ){
?>
   <h1>Установка системы управления контентом</h1>
   <p>Заполните все поля и нажмите на кнопку «Установить систему» Если ви все правильно сделали и не возникло каких-либо программных сбоев, вы перейдете на страницу, где сможете увидеть статус установки. Если на этом или другом этапе у вас возникли проблемы — вы можете обратиться к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p>
   <div class="container">
    <form action="process.php" method="post">
     <div class="touch">
      <h2>База данных</h2>
      <label for="db_name">Имя базы данных:</label>
      <input type="text" name="db_name" id="db_name" class="width40" required><br>
      <label for="db_host">Хост базы данных:</label>
      <input type="text" name="db_host" id="db_host" class="width40" value="localhost" required><br>
      <label for="db_user">Пользлватель базы данных:</label>
      <input type="text" name="db_user" id="db_user" class="width40" required><br>
      <label for="db_pass">Пароль пользователя базы данных:</label>
      <input type="text" name="db_pass" id="db_pass" class="width40" required><br>
      <label for="db_tabl_prefix">Префикс таблиц базы данных:</label>
      <input type="text" name="db_tabl_prefix" id="db_tabl_prefix" class="width40" required>&nbsp;
      <i class="fa-question-circle blue" onmouseover="tt(this);"></i>
<pre class="tt">
В процессе установки система создаст необходимые
для ее работы таблицы в указанной базе данных.
Чтобы имена создаваемых таблиц были уникальны и
не возникло конфликтов с уже существующими таблицами
— введите короткий префикс на латинице,
не используя специальные символы.
Пример: <b>good</b></pre><br>
     </div>
     <div class="touch">
      <h2>Начальные установки системы</h2>
      <label for="site_name">Имя сайта:</label>
      <input type="text" name="site_name" id="site_name" class="width40" placeholder="Пример: Фирма «Рога и копыта»" required><br>
      <label for="domen">Домен:</label>
      <input type="url" name="domen" id="domen" class="width40" value="http://<?=$_SERVER['SERVER_NAME']?>/" required>&nbsp;
      <i class="fa-info-circle red" onmouseover="tt(this);"></i>
<pre class="tt">
URL к директории в которой будет запущена система
(куда вы выгрузили все файлы)
Внимание! Обязательно с «http://» и «/» в конце</pre><br>
      <label for="admin_name">Логин администратора:</label>
      <input type="text" name="admin_name" id="admin_name" class="width40" value="admin" required><br>
      <label for="admin_pass">Пароль администратора:</label>
      <input type="text" name="admin_pass" id="admin_pass" class="width40" value="admin" required>&nbsp;
      <a href="#" onclick="gen_pass('admin_pass');return false" class="fa-refresh" title="Генерировать новый пароль"></a><br>
      <label for="admin_mail">E-mail администратора:</label>
      <input type="email" name="admin_mail" id="admin_mail" class="width40" required><br>
      <label for="moder_name">Логин модератора:</label>
      <input type="text" name="moder_name" id="moder_name" class="width40" value="moderator" required><br>
      <label for="moder_pass">Пароль модератора:</label>
      <input type="text" name="moder_pass" id="moder_pass" class="width40" value="moderator" required>&nbsp;
      <a href="#" onclick="gen_pass('moder_pass');return false" class="fa-refresh" title="Генерировать новый пароль"></a><br>
      <label for="moder_mail">E-mail модератора:</label>
      <input type="email" name="moder_mail" id="moder_mail" class="width40" required><br>
     </div>
     <input type="submit" id="go_process" value="Установить систему">
    </form>
   </div>
<?php }else{
  echo '<h2>Все папки и файлы ниже должны быть доступны для записи!</h2>';
 if(@is_writable($cms_path.$f1)){
  echo '<div><i class="fa-check-circle green"></i> Папка <b>'.$f1.'</b> доступна для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Папка <b>'.$f1.'</b> не доступна для записи</div>';
 }
 if(@is_writable($cms_path.$f2)){
  echo '<div><i class="fa-check-circle green"></i> Папка <b>'.$f2.'</b> доступна для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Папка <b>'.$f2.'</b> не доступна для записи</div>';
 }
 if(@is_writable($cms_path.$f3)){
  echo '<div><i class="fa-check-circle green"></i> Файл <b>'.$f3.'</b> доступен для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Файл <b>'.$f3.'</b> не доступен для записи</div>';
 }
 if(@is_writable($cms_path.$f4)){
  echo '<div><i class="fa-check-circle green"></i> Файл <b>'.$f4.'</b> доступен для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Файл <b>'.$f4.'</b> не доступен для записи</div>';
 }
 if(@is_writable($cms_path.$f5)){
  echo '<div><i class="fa-check-circle green"></i> Папка <b>'.$f5.'</b> доступна для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Папка <b>'.$f5.'</b> не доступна для записи</div>';
 }
 if(@is_writable($cms_path.$f6)){
  echo '<div><i class="fa-check-circle green"></i> Папка <b>'.$f6.'</b> доступна для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Папка <b>'.$f6.'</b> не доступна для записи</div>';
 }
 if(@is_writable($cms_path.$f7)){
  echo '<div><i class="fa-check-circle green"></i> Папка <b>'.$f7.'</b> доступна для записи</div>';
 }else{
  echo '<div><i class="fa-times-circle red"></i> Папка <b>'.$f7.'</b> не доступна для записи</div>';
 }
echo '<p style="margin-top:1.5em;">Установите права на запись (777) и жмите <a href="index.php" class="btn">Попробовать снова</a> или обратитесь к разработчику по e-mail: <a href="mailto:kroloburet@gmail.com">kroloburet@gmail.com</a></p>';
}
?>
   <div id="copy">Веб-разработка и дизайн<a href="mailto:kroloburet@gmail.com" class="js"> <img src="/UI_fraimwork/img/kroloburet_18_18.jpeg" width="18" height="18" alt="Разработка и дизайн сайтов"> kroloburet@gmail.com</a><br>
 <img src="/UI_fraimwork/img/logo_tagra_18_18.svg" alt="Tagra CMS"> Tagra CMS<br></div>
  </div>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
  <script src="/UI_fraimwork/js.js" defer></script>
  <script>
  /////////////////////////////////////////////////////////////////////////генерирование пароля с вставкой в поле
  function gen_pass(pass/*поле для вставки пароля*/){
   var passwd='',
       chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~@#$[]_-';
   for(i=1;i<11;i++){
    var c=Math.floor(Math.random()*chars.length + 1);
    passwd+=chars.charAt(c);
   }
   $('#'+pass).val(passwd);
  }
  </script>
 </body>
</html>