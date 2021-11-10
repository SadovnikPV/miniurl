<?php
// Подключение файла с глобальными переменными и константами
include_once "config.php";

// Получение полного адреса страницы. Сложение имени хоста и имени страницы -page-
function get_url($page = '')
{
    return HOST . "/" . $page;
}

// Подключение к БД MySQL
function db()
{
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

// Запрос к БД 
function db_query($sql = '', $exec = false)
{
    if (empty($sql)) return false;

    if ($exec) {
        return db()->exec($sql);
    }

    return db()->query($sql);
}

// Запрос к БД на получение количества пользователей на сайте
function get_user_count()
{
    return db_query("SELECT COUNT(`id`) FROM `users`")->fetchColumn();
}

// Запрос к БД на получение количества различных ссылок на сайте
function get_links_count()
{
    return db_query("SELECT COUNT(`long_link`) FROM `links`")->fetchColumn();
}

// Запрос к БД на получение количества переходов по сокращенным ссылкам
function get_views_count()
{
    return db_query("SELECT SUM(`views`) FROM `links`")->fetchColumn();
}

// Запрос к БД на получение всех параметров ссылки по сокращенной ссылке
function get_link_info($url)
{
    if (empty($url)) return [];

    return db_query("SELECT * FROM `links` WHERE `short_link` = '$url'")->fetch();
}

// Запрос к БД на получение всех параметров ссылки по номеру -id-
function get_link_by_id($id)
{
    if (empty($id)) return [];

    return db_query("SELECT * FROM `links` WHERE `id` = '$id'")->fetch();
}


function get_user_info($login)
{
    if (empty($login)) return [];

    return db_query("SELECT * FROM `users` WHERE `login` = '$login'")->fetch();
}

function update_views($url)
{
    if (empty($url)) return false;

    return db_query("UPDATE `links` SET `views` = `views` + 1 WHERE `short_link` = '$url'", true);
}

function add_user($login, $pass)
{
    $password = password_hash($pass, PASSWORD_DEFAULT);
    return db_query("INSERT INTO `users` (`id`, `login`, `pass`) VALUES (NULL, '$login', '$password');", true);
}

// -------------------------------------------------------
// --- РЕГИСТРАЦИЯ. Добавляет нового пользователя в БД ---
// -------------------------------------------------------
function register_user($auth_data)
{
    // Не пустые ли поля в форме регистрации
    if (
        empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) ||
        !isset($auth_data['pass']) || !isset($auth_data['pass2'])
    ) {
        return false;
    }

    // Проверка на содержание недопустимых символов в логине
    if (!is_login_correct($auth_data['login'])) {
        $_SESSION['error'] = "Логин содержит недопустимые символы! Разрешено использовать 
            маленькие и большие латинские буквы, цифры, тире и нижнее подчеркивание. 
            Длина логина должна быть от двух до двадцати символов.";
        header('Location: register.php');
        die;
    }

    // Проверка на содержание недопустимых символов в пароле
    if (!is_pass_correct($auth_data['pass'])) {
         $_SESSION['error'] = "Пароль содержит недопустимые символы! Разрешено использовать 
            маленькие и большие латинские буквы, а так же цифры. 
            Длина логина должна быть от двух до двадцати символов.";
        header('Location: register.php');
        die;
    }

    // Существует ли уже такое имя пользователя в БД
    $user = get_user_info($auth_data['login']);
    if (!empty($user)) {
        $_SESSION['error'] = "Пользователь '" . $auth_data['login'] . "' уже существует. Пожалуйста, выберите другое имя.";
        header('Location: register.php');
        die;
    }

    // Совпадают ли пароли
    if ($auth_data['pass'] !== $auth_data['pass2']) {
        $_SESSION['error'] = "Введенные пароли не совпадают. Пожалуйста, попробуйте еще раз.";
        header('Location: register.php');
        die;
    }

    // Добавление нового пользователя в БД, если все проверки пройдены
    if (add_user($auth_data['login'], $auth_data['pass'])) {
        $_SESSION['success'] = "Регистрация прошла успешно!";
        header('Location: login.php');
        die;
    };

    return true;
}

// ------------------- АВТОРИЗАЦИЯ -----------------------

function login($auth_data)
{
    if (
        empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) ||
        !isset($auth_data['pass']) || empty($auth_data['pass'])
    ) {
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

function get_user_links($user_id)
{
    if (empty($user_id)) return [];
    return db_query("SELECT * FROM `links` WHERE `user_id` = $user_id")->fetchAll();
}

// Запрос в БД на удаление ссылки
function delete_link($id)
{
    if (empty($id)) return false;

    return db_query("DELETE FROM `links` WHERE `id` = $id", true);
}

// Запрос в БД на добавление новой ссылки
function add_link($user_id, $link)
{
    $short_link = generate_string();

    return db_query("INSERT INTO `links` (`id`, `user_id`, `long_link`, `short_link`, `views`) VALUES (NULL, '$user_id', '$link', '$short_link', '0')", true);
}

// Генерация случайной последовательности символов
function generate_string($size = 6)
{
    $new_string = str_shuffle(URL_CHARS);
    return substr($new_string, 0, $size);
}

// Запрос в БД на изменение полной ссылки пользователем
function edit_link($link, $link_id)
{
    if (empty($link) || empty($link_id)) return false;
    $link = preg_replace('/\s/', '', $link);
    if ($link) {
        return db_query("UPDATE `links` SET `long_link` = '$link' WHERE `id` = '$link_id';", true);
    }
}

// Проверка, указан ли протокол вначале ссылки. Если нет - добавление протокола http
function is_http_in_link($link)
{
    $link = preg_replace('/\s/', '', $link);
    $is_http = strpos($link, 'http://', 0);
    $is_https = strpos($link, 'https://', 0);
    if ($is_http === 0 || $is_https === 0) {
        return true;
    } else {
        return 'http://' . $link;
    }
}

// Проверяет корректность введенного логина
function is_login_correct($login)
{
    if (preg_match("/^[a-z0-9-_]{2,20}$/i", $login)) return true;
    else
        return false;
}

// Проверяет корректность введенного пароля (при регистрации)
function is_pass_correct($pass)
{
    if (preg_match("/^[a-z0-9]{2,20}$/i", $pass)) return true;
    else
        return false;
}
