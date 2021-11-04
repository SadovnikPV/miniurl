<!-- ------------ Скрипт редактирования полной ссылки пользователем ------------ -->
<?php
include_once "config.php";
include_once "functions.php";

if (isset($_POST['edited_link']) && !empty($_POST['edited_link']) && isset($_POST['link_id']) && !empty($_POST['link_id'])) {
    
    if (edit_link($_POST['edited_link'], $_POST['link_id'])) {
        $_SESSION['success'] = 'Ссылка успешно изменена!';
    } else {
        $_SESSION['error'] = "Во время изменения ссылки что-то пошло не так(";
    }
}

header('Location: /profile.php');