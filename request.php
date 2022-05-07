<?php

require_once 'connect.php';
$conn = mysqli_connect('localhost', 'root', '', 'short-url');
if (!$conn)
    die("Ошибка подключения к базе данных: \n" . mysqli_connect_error());

if (!empty($request)) {
    $sel = $conn->query("SELECT * FROM links WHERE link = '" . $request . "'");

    if ($sel->num_rows > 0) {
        echo 'Найдена ссылка';
    }
    else {
        echo 'Этой ссылки в бд нет';
    }

}
