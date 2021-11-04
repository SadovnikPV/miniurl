<!-- -------------- Скрипт добавления новой ссылки --------------- -->
<?php
include_once "config.php";
include_once "functions.php";

if (isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $new_link = is_http_in_link($_POST['link']);
    if (add_link($_POST['user_id'], $new_link)) {
        $_SESSION['success'] = 'Ссылка успешно добавлена!';
    } else {
        $_SESSION['error'] = "Во время добавления ссылки что-то пошло не так(";
    }
}

header('Location: /profile.php');
die;