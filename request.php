<?php
require_once 'connect.php';

$conn = mysqli_connect('localhost', 'root', '', 'short-url');

if (!$conn)
    die("Ошибка подключения к базе данных: \n" . mysqli_connect_error());

if (isset($_GET['long_link']))
    $long_link = $conn->real_escape_string(trim($_GET['long_link']));

if (!empty($long_link)) {
    $sel = $conn->query("SELECT * FROM links WHERE link = '" . $long_link . "'");

    // Одна ссылка - один токен
    // Если этой ссылки нет в БД
    if ($sel->num_rows == 0) {
        $canInsert = false;
        $token = generate_unique_token($conn);


        $ins = $conn->query("INSERT INTO links (link, token) VALUES ('" . $long_link . "', '" . $token . "')");

        if ($ins) {
            echo 'Добавлена ссылка';
        } else {
            echo 'Ссылка не добавлена';
        }
    }
    else {
        echo 'Эта ссылка уже добавлена в БД. Отобразить';
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