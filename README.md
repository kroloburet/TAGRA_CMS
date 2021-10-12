<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->

<img src="https://raw.githubusercontent.com/kroloburet/TAGRA_CMS/master/img/tagra_share.jpg" style="max-width:900px;margin-bottom:1.5em;">

## Кратко о системе
Система управления контентом Tagra разработана для реализации веб-ресурсов (корпоративных, визиток, промо) с адаптивным дизайном и высокой производительностью. Также система может служить базой для разработки более масштабных и сложных веб-проектов. В отличии от более <q>тяжеловесных</q> CMS (Joomla, WordPress и т.д), использует меньше программного кода, библиотек, не перегружена лишним и проста в администрировании. Tagra написана на базе PHP-фреймворка [CodeIgniter](https://www.codeigniter.com).

## Требования к серверу
* PHP <=7.0
* MySQL <=5.1, mysql и pdo драйвера

## Установка системы на удаленный хост
1. Скачай архив с системой и распакуй его. [Загрузить архив TAGRA_CMS-master.zip](https://github.com/kroloburet/TAGRA_CMS/archive/master.zip)
2. Выгрузи все перечисленные ниже файлы и каталоги вместе с их содержимым в рабочий каталог сервера.
   ```
   application/
   css/
   img/
   instal/
   scripts/
   system/
   Tagra_UI/
   .htaccess
   favicon.ico
   403.html
   plug.html
   index.php
   robots.txt
   ```
3. Создай базу данных MySQL если она еще не создана
4. Перейди по адресу `www.твойсайт`
5. Заполни поля начальных установок для сайта и жми <q>Установить систему</q>. Инсталлятор создаст необходимые таблицы в базе данных, каталог `/upload` для медиа файлов, файлы конфигурации системы и базы данных.
6. Если установка прошла успешно, жми на <q>Удалить инсталлятор</q>

## Установка в локальном окружении

### Понадобится
* Git
* PHP <=7.0
* [Composer](https://getcomposer.org/)
* [Docker](https://docs.docker.com/) и [Docker Compose](https://docs.docker.com/compose/install/)

### Установка
1. В консоли перейди в каталог куда будет клонирован корневой каталог системы и выполни:
   ```
   git clone --depth 1 https://github.com/kroloburet/TAGRA_CMS.git \
   && cd ./TAGRA_CMS \
   && git checkout -b dev \
   && docker-compose up -d --build
   ```
2. Перейди по адресу `localhost:80` и заполни поля начальных установок:
   ```
   Имя базы данных: tagra
   Хост базы данных: tagra_db
   Пользлватель базы данных: admin
   Пароль пользователя базы данных: admin
   ```
3. Инсталлятор создаст необходимые таблицы в базе данных, каталог `/upload` для медиа файлов, файлы конфигурации системы и базы данных.
4. Если установка прошла успешно, жми на <q>Удалить инсталлятор</q> или удали каталог `/instal` со всем ее содержимым вручную.
5. PhpMyadmin для управления базой доступен по адресу `localhost:8080`
   ```
   Root пользователь: root
   Пароль root пользователя: root
   ```
6. В `/local` находятся каталоги сервисов используемыми Docker. Контейнер базы данных использует для записи и хранения данных каталог `/local/db/data`

## Лицензия
Свободная лицензия [MIT license](https://opensource.org/licenses/MIT).

***
Если возникли трудности или выше перечисленные инструкции для тебя сложны, обращайся к разработчику системы: <img src="https://raw.githubusercontent.com/kroloburet/TAGRA_CMS/master/img/i.jpg"> kroloburet@gmail.com