<?php
// Локализация интерфейса шаблона пользовательской части ресурса

$lang=[

    // Наиболее общие (базовые)
    'basic'=>[
        'loading'=>'&nbsp;&nbsp;обработка...',
        'bot'=>'Ой! Вы робот!? Вам здесь не рады..(',
        'error'=>'Ой! Ошибка..(<br>Возможно это временные неполадки, попробуйте снова.',
        'server_error'=>'Ой! Возникла ошибка на сервере..(<br>Возможно это временные неполадки, попробуйте снова.',

        // Header
        'nojs'=>'Внимание! В вашем браузере выключен Javascript. Для корректной работы сайта рекомендуем включить Javascript.',
        'home'=>'Главная',
        'to_home'=>'На главную',

        // Footer
        'follow_title'=>'Мы в соцсетях',
    ],

    // Галерея
    'gallery'=>[
        'playing'=>'Воспроизводится...',
        'paused'=>'Пауза...',
        'error_path_img_dir'=>'Неверный путь к папке изображений!',
        'noimgs'=>'Галерея не может быть отображена! В галерее нет ни одного изображения.',
        'novideos'=>'Галерея не может быть отображена! В галерее нет ни одного видео.',
        'noaudios'=>'Галерея не может быть отображена! В галерее нет ни одного аудио.',
        'nohtml5_audio'=>'Ваш браузер устарел и не поддерживает HTML5 Audio!',
    ],

    // Раздел
    'section'=>[
        'sub_page'=>'Страница',
        'sub_section'=>'Подраздел',
        'sub_gallery'=>'Галерея',
        'sub_pages_title'=>'Раздел содержит страницы:',
        'sub_sections_title'=>'Раздел содержит подразделы:',
        'sub_gallerys_title'=>'Раздел содержит галереи:',
        'more'=>'Подробнее',
    ],

    // Страница "Контакты"
    'contact'=>[
        'print'=>'Печатать',
        'list_view'=>'Контакты списком',
        'map_view'=>'Контакты на карте',
        'big_map_view'=>'Показать на большой карте',
        'form_title'=>'Форма обратной связи',
        'your_mail'=>'Ваш e-mail',
        'your_name'=>'Ваше имя',
        'your_msg'=>'Ваше сообщение',
        'send_form'=>'Отправить сообщение',
        'novalid_field'=>'Недопустимый символ или не заполнено поле: ',
        'nomail'=>'Переданный e-mail некорректный!',
        'send_ok'=>'Ваше сообщение успешно отправлено!',
    ],

    // Страница "Ошибка 404"
    '404'=>[
        'title'=>'Упс! Страница не найдена',
        'content'=>'<h1>Упс! Страница не найдена..(</h1>Запрошенный вами материал не существует, удален или снят с публикации. Не огорчайтесь. Вы можете <a href="javascript:history.back()">вернуться назад</a> или <a href="/">перейти на главную</a>.',
    ],

    // Модуль навигации "Хлебные крошки"
    'breadcrumbs'=>[
        'home'=>'Главная',
    ],

    // Модуль комментариев
    'comments'=>[
        'mod_title'=>'Комментарии',
        'your_name'=>'Ваше имя',
        'your_name_or_mail'=>'Ваше имя или e-mail',
        'your_comment'=>'Ваш комментарий',
        'your_reply'=>'Ваш ответ',
        'send_form'=>'Отправить комментарий',
        'send_reply'=>'Отправить',
        'reply'=>'Ответить',
        'reply_to'=>'в ответ на',
        'like'=>'Мне нравится',
        'dislike'=>'Мне не нравится',
        'public_date'=>'Дата публикации',
        'more'=>'Еще комментарии',
        'hide'=>'Свернуть',
        'hide_form'=>'Скрыть форму',
        'feedback_title'=>'Ответ на ваш комментарий с ',
        'feedback_msg'=>'Чтобы получать уведомления об ответе на этот комментарий, укажите e-mail вместо имени. Ваш e-mail будет скрыт и защищен от третьих лиц.',
        'feedback_again'=>'Если пожелаете получать уведомления об ответах на ваши новые комментарии, снова укажите e-mail вместо имени.',
        'novalid_field'=>'Недопустимый символ или не заполнено поле: ',
        'novalid_mail'=>'Некорректный e-mail в поле: ',
        'premod_msg'=>'Ваш комментарий будет опубликован после проверки модератором.',
        'reserved_name_msg'=>'может быть использовано только администратором!<br>Если вы администратор, авторизуйтесь в системе.',
        'published'=>'опубликован ',
        'new'=>'Новый комментарий',
        'go_to'=>'Перейти к этому ответу в материале',
        'unfeedback_page_title'=>'Отписка от обратной связи',
        'unfeedback_msg'=>'Если это уведомление пришло вам по ошибке или вы больше не хотите получать такие уведомления:',
        'uncomment_more'=>'Больше не уведомлять об ответах на этот комментарий',
        'uncomment_less'=>'Не уведомлять об ответах на этот комментарий',
        'uncomment_ok'=>'Успешно! На этот e-mail больше не будут приходить уведомления об ответах на ваш комментарий.',
        'unpage_more'=>'Больше не уведомлять об ответах на мои комментарии в этом материале',
        'unpage_less'=>'Не уведомлять об ответах в этом материале',
        'unpage_ok'=>'Успешно! На этот e-mail больше не будут приходить уведомления об ответах на ваши комментарии в материале.',
        'unsite_more'=>'Больше не уведомлять об ответах на мои комментарии на всем сайте',
        'unsite_less'=>'Не уведомлять об ответах на сайте',
        'unsite_ok'=>'Успешно! На этот e-mail больше не будут приходить уведомления об ответах на ваши комментарии с сайта.',
        'try_again'=>'Попробовать снова',
        'close_window'=>'Закрыть страницу',
        'data_error'=>'Ошибка..( Данные для работы сценария повреждены или не переданы!',
    ],

];