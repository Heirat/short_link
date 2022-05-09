<?php
require_once 'connect.php';

$conn = mysqli_connect('localhost', 'root', '', 'short-url');

if (!$conn)
    die("Ошибка подключения к базе данных: \n" . mysqli_connect_error());

if (isset($_GET['long_link']))
    $request = $conn->real_escape_string(trim($_GET['long_link']));

if (!empty($request)) {

    $sel = $conn->query("SELECT * FROM links WHERE link = '" . $request . "'");

    if ($sel->num_rows == 0) {
        $token = token_gen();

        $ins = $conn->query("INSERT INTO links (link, token) VALUES ('" . $request . "', '" . $token . "')");

        if ($ins) {
            echo 'Добавлена ссылка';
        }
        else {
            echo 'Ссылка не добавлена';
        }
    }
    else {
        echo 'Эта ссылка уже есть в базе данных';
    }

}

/**
 * Генератор токена. Использует псевдослучайную последовательность
 * из маленьких и больших букв английского алфавита и цифр
 * @param int $min Минимальная длина токена
 * @param int $max Максимальная длина токена
 * @return string Токен
 */
function token_gen(int $min = 5, int $max = 8): string
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