<!DOCTYPE html>
<html>
 <head>
  <meta name="generator" content="Powered by «Tagra CMS» Development and design by kroloburet@gmail.com">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="none">
  <link href="/UI_fraimwork/css.css" rel="stylesheet">
  <link href="/css/back/general.css" rel="stylesheet">
  <link href="/UI_fraimwork/fonts/FontAwesome/style.css" rel="stylesheet">
  <!--[if lt IE 8]><!-->
	<link href="/UI_fraimwork/fonts/FontAwesome/ie7/ie7.css" rel="stylesheet">
	<!--<![endif]-->
  <title>Авторизация</title>
  <style>
   form{width:40%;margin:15% auto 11% auto;}
   .button{margin-top:0;}
   input,button{text-align:center;font-size:120% !important;}
   button,input[type="submit"]{width:50%;float:left;}
   button{width:auto;}
   .opts_pan{text-align:right;margin-top:3px;}
   #see_pass{width:auto;margin:0 5px 0 0;}
   #sand_pass{display:none;}
   @media(max-width:800px){form{width:75%;}}
  </style>
 </head>
 <body>
  <div class="authoriz_box">

<!--####### Авторизация в админке #######-->
   <form action="" method="POST" id="login_form">
    <label class="input">
     <input type="text" name="lgn" placeholder="логин" required>
    </label>
    <label class="input">
     <input type="password" name="pswd" id="pass" placeholder="пароль" required>
    </label>
    <div id="login_msg"><?=isset($msg)&&!empty($msg)?$msg:FALSE?></div>
    <input type="submit" value="Войти">
    <div class="opts_pan">
     <a href="#" class="fa-eye" title="Показать пароль" id="see_pass"></a>&nbsp;&nbsp;
     <a href="#" class="fa-unlock-alt" title="Восстановление доступа" id="show_sand_pass"></a>&nbsp;&nbsp;
     <a href="/" class="fa-home" title="Перейти на главную сайта"></a>
    </div>
   </form>

<!--####### Отослать логины\пароли на email #######-->
   <form id="sand_pass">
    Отправить логин и пароль на e-mail пользователя
    <label class="input">
     <input type="email" name="send_pass_mail" placeholder="e-mail пользователя" required>
    </label>
    <input name="fuck_bot">
    <div id="sand_pass_msg"></div>
    <div class="button inline">
     <button type="submit">Отправить</button>
     <button type="button" id="no_subm_pass">Отмена</button>
    </div>
   </form>

<!--####### Копирайт #######-->
   <div id="copy">
    Веб-разработка и дизайн<a href="mailto:kroloburet@gmail.com"> <img src="/img/i.jpg" alt="kroloburet"> kroloburet@gmail.com</a><br>
    <img src="/img/tagra_min.svg" alt="Tagra CMS"> Tagra CMS<br>
   </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
   ////////////////////////////////////////показать скрыть пароль в поле
    $('#see_pass').on('click',function(e){
     e.preventDefault();
     var btn=$(this),
         pass=$('#pass').get(0);
     if(pass.type==='password'){
      pass.type='text';
      btn.addClass('fa fa-eye-slash').attr('title','Скрыть пароль');
     }else{
      pass.type='password';
      btn.removeClass('fa fa-eye-slash').attr('title','Показать пароль');
     }
    });

    //////////////////////////////////////показать скрыть форму "Восстановить доступ"
    $('#show_sand_pass,#no_subm_pass').on('click',function(e){
     e.preventDefault();
     $('#login_form,#sand_pass').slideToggle(200);
    });

    //////////////////////////////////////отправить новые логин\пароль пользователю
    $('#sand_pass').on('submit',function(e){
     e.preventDefault();
     var form=$(this),
         msg=$('#sand_pass_msg'),
         btn=form.find(':submit'),
         cancel=$('#no_subm_pass');
     cancel.detach();
     btn.attr('disabled',true).html('<i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;обработка...');//блокирую кнопку
     $.ajax({
      url: '/do/change_login',
      type: 'post',
      data: form.serialize(),
      dataType: 'json',
      success: function(resp){
       switch(resp.status){
        case 'bot':
         form.find('[name=send_pass_mail]').val('');//сброс поля
         msg.html('<div class="notific_r mini full">Ой! Вы робот?! Вам здесь не рады..(</div>');
         btn.after(cancel).remove();//только отмена
         break;
        case 'nomail':
         msg.html('<div class="notific_r mini full">Ой! Ошибка..(<br>Пользователя с таким email нет в системе.</div>');
         btn.attr('disabled',false).html('Отправить').after(cancel);//разблокировка кнопки
         break;
        case 'noaccess':
         msg.html('<div class="notific_r mini full">Упс! Администратор запретил вам вход и все действия от имени модератора.</div>');
         btn.attr('disabled',false).html('Отправить').after(cancel);//разблокировка кнопки
         break;
        case 'ok':
         form.find('[name=send_pass_mail]').val('');//сброс поля
         msg.html('<div class="notific_g mini full">'+resp.html+'</div>');
         btn.attr('disabled',false).html('Отправить').after(cancel);//разблокировка кнопки
         setTimeout(function(){msg.empty();$('#login_form,#sand_pass').slideToggle();},5000);//очищаю сообщение об отправке, скрываю форму
       }
      },
      error:function(){
       alert('Ой! Возникла ошибка соединения..( Повторите попытку.');
      }
     });
    });
  </script>
 </body>
</html>