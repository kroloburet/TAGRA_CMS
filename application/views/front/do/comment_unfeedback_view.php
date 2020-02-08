<!DOCTYPE html>
<html>
  <head>
    <meta name="generator" content="Powered by Tagra CMS. Development and design by Sergey Nizhnik kroloburet@gmail.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <link href="/Tagra_UI/fonts/FontAwesome/css/all.min.css" rel="stylesheet">
    <link href="/Tagra_UI/style.css" rel="stylesheet">
    <title><?= $title ?></title>
    <style>
      .comment_action_box{display:block;width:50%;margin:15% auto 11% auto;}
      @media(max-width:1000px){.comment_action_box{width:80%;}}
      @media(max-width:800px){.comment_action_box{width:90%;}}
      @media(max-width:600px){.comment_action_box{width:98%;}}
    </style>
  </head>
  <body>
    <div class="comment_action_box <?= $msg_class ?>"><?= $msg ?></div>
  </body>
</html>
