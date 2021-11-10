<?php

    define ('SITE_NAME', 'Mini URL');           // Имя сайта
    define ('HOST', 'http://' . 'res/projects/miniurl');     // Домен сайта с протоколом http

    define ('DB_HOST', 'localhost');            // Адрес хоста (IP или имя)
    define ('DB_NAME', 'miniurl');              // Имя БД
    define ('DB_USER', 'root');
    define ('DB_PASS', '');

    define ('URL_CHARS', 'abcdefghijklmnopqrstuvwxyz0123456789-');

    session_start();
?>