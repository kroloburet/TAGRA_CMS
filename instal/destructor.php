<?php

/**
 * Деструктор инсталлятора системы
 */

// перенаправить пользователя на главную
header("Location: /");

/**
 * Рекурсивное удаление каталога с вложениями
 *
 * @param string $dir Абсолютный путь к каталогу
 * @return boolean
 */
function delTree(string $dir)
{
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

// удалить каталог  /instal (родительский каталог этого скрипта)
delTree(dirname(__FILE__));
