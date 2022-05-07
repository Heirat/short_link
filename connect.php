<?php

$conn = mysqli_connect('localhost', 'root', '', 'short-url');

if (!$conn)
    die("Ошибка подключения к базе данных: \n" . mysqli_connect_error());


