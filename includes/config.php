<?php

    define ('SITE_NAME', 'Mini URL');           // Имя сайта
    define ('HOST', 'http://' . 'res/projects/miniurl');     // Домен сайта с протоколом http

    define ('DB_HOST', 'localhost');            // Адрес хоста (IP или имя)
    define ('DB_NAME', 'resume');               // Имя БД
    define ('DB_USER', 'root');
    define ('DB_PASS', '');

    define ('DB_TABLE_USERS', 'miniurl_users');
    define ('DB_TABLE_LINKS', 'miniurl_links');

    define ('URL_CHARS', 'abcdefghijklmnopqrstuvwxyz0123456789-');

    session_start();
?>