<?php
include "includes/config.php";

function get_url($page = '') {
    return HOST . "/" . $page;
}

function db() {
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch(PDOException $e) {
        die($e->getMessage());
    }
}

function db_query($sql = '', $exec = false) {
    if (empty($sql)) return false;

    if ($exec) {
        return db()->exec($sql);
    }

	return db()->query($sql);
}

function get_user_count() {
    return db_query("SELECT COUNT(`id`) FROM `users`")->fetchColumn();
}

function get_links_count() {
    return db_query("SELECT COUNT(`long_link`) FROM `links`")->fetchColumn();
}

function get_views_count() {
    return db_query("SELECT SUM(`views`) FROM `links`")->fetchColumn();
}

function get_link_info($url) {
    if (empty($url)) return [];

    return db_query("SELECT * FROM `links` WHERE `short_link` = '$url'")->fetch();
}

function get_user_info($login) {
    if (empty($login)) return [];

    return db_query("SELECT * FROM `users` WHERE `login` = '$login'")->fetch();
}

function update_views($url) {
    if (empty($url)) return false;

    return db_query("UPDATE `links` SET `views` = `views` + 1 WHERE `short_link` = '$url'", true);
}

function add_user($login, $pass) {
    $password = password_hash($pass, PASSWORD_DEFAULT);
    return db_query("INSERT INTO `users` (`id`, `login`, `pass`) VALUES (NULL, '$login', '$password');", true);
}

function register_user($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || 
    !isset($auth_data['pass']) || !isset($auth_data['pass2'])) {
        return false;
    }

    $user = get_user_info($auth_data['login']);
    if (!empty($user)) {
        $_SESSION['error'] = "Пользователь '" . $auth_data['login'] . "' уже существует. Пожалуйста, выберите другое имя.";
        header('Location: register.php');
        die;
    }

    if ($auth_data['pass'] !== $auth_data['pass2']) {
        $_SESSION['error'] = "Введенные пароли не совпадают. Пожалуйста, попробуйте еще раз.";
        header('Location: register.php');
        die;
    }

    if (add_user($auth_data['login'],$auth_data['pass'])) {
        $_SESSION['success'] = "Регистрация прошла успешно!";
        header('Location: login.php');
        die;
    };

    return true;
}

function login($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || 
    !isset($auth_data['pass']) || empty($auth_data['pass'])) {
        $_SESSION['error'] = "Логин или пароль не могут быть пустыми.";
        header('Location: login.php');
        die;
    }

    $user = get_user_info($auth_data['login']);
    if (empty($user)) {
        $_SESSION['error'] = "Логин или пароль неверен!";
        header('Location: login.php');
        die;
    }

    if (password_verify($auth_data['pass'], $user['pass'])) {
        $_SESSION['user'] = $user;
        header('Location: profile.php');
        die;
    } else {
        $_SESSION['error'] = "Пароль неверен!";
        header('Location: login.php');
        die;
    }
}