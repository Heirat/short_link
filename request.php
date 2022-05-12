<?php
/*
 * У каждой ссылки должен быть один токен. У каждого токена должна быть одна ссылка.
 * (Ссылка Токен - 1 к 1)
 * Токен - это последовательность алфавитно-цифровых символов.
 * Если задан параметр long_link - то генерирует токен если неоходимо и отображает короткую ссылку в html
 */
require_once 'connect.php';
$conn = mysqli_connect('localhost', 'root', '', 'short-url');
if (!$conn)
    die("Ошибка подключения к базе данных: \n" . mysqli_connect_error());

$GLOBALS['short_link'] = '';

if (isset($_GET['long_link'])) {
    $long_link = $conn->real_escape_string(trim($_GET['long_link']));
    $sel = $conn->query("SELECT * FROM links WHERE link = '" . $long_link . "'");

    // Если этой ссылки нет в БД
    if ($sel->num_rows == 0) {
        $canInsert = false;
        $token = generate_unique_token($conn);

        $ins = $conn->query("INSERT INTO links (link, token) VALUES ('" . $long_link . "', '" . $token . "')");

        if ($ins) {
            $GLOBALS['short_link'] = "{$_SERVER['SERVER_NAME']}/$token";
        } else {
            echo 'Ссылка не добавлена';
        }
    }
    // Если в БД уже есть эта ссылка
    else {
        $row = $sel->fetch_assoc();
        $token = $row['token'];
        // Отобразить в html
        $GLOBALS['short_link'] = "{$_SERVER['SERVER_NAME']}/$token";
    }
}
else {
    $token = substr($_SERVER['REQUEST_URI'], 1);

    // Если ввели токен
    if (iconv_strlen($token)) {
        $select = $conn->query("SELECT * FROM links WHERE token = '" . $token . "'");

        // Если токен есть в БД
        if ($select->num_rows > 0) {
            $row = $select->fetch_assoc();
            header("Location: {$row['link']}");
        } else {
            // Если такого токена нет, то
            // Редирект на основную страницу
            //header("Location: /");
        }
    }
}

/**
 * Генерирует токен и делает запрос к БД, повторяя, пока не получит уникальный токен.
 * @param mysqli $connection Дескриптор соеднинения с БД
 * @param int $min Минимальная длина токена
 * @param int $max Максимальная длина токена
 * @return string Токен
 */
function generate_unique_token(mysqli $connection, int $min = 5, int $max = 8): string {
    $token = '';
    while (true) {
        $token = generate_token($min, $max);
        // Получаем этот токен из БД
        $select = $connection->query("SELECT * FROM links WHERE token = '" . $token . "'");

        // Если получен токен, которого нет в БД
        if ($select->num_rows == 0) {
            break;
        }
    }

    return $token;
}

/**
 * Генератор токена. Использует псевдослучайную последовательность
 * из маленьких и больших букв английского алфавита и цифр
 * @param int $min Минимальная длина токена
 * @param int $max Максимальная длина токена
 * @return string Токен
 */
function generate_token(int $min = 5, int $max = 8): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $new_chars = str_split($chars);
    $token = '';
    $rand_end = mt_rand($min, $max);

    for ($i = 0; $i < $rand_end; $i++) {
        $token .= $new_chars[ mt_rand(0, count($new_chars) - 1)];
    }

    return $token;
}