<?php

    define ('SITE_NAME', 'Mini URL');
    define ('HOST', 'http://' . $_SERVER['HTTP_HOST']);

    define ('DB_HOST', 'localhost');    // localhost
    define ('DB_NAME', 'miniurl');
    define ('DB_USER', 'root');
    define ('DB_PASS', '');

    define ('URL_CHARS', 'abcdefghijklmnopqrstuvwxyz0123456789-');

    session_start();
?>