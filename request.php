<?php

include 'connect.php';

$request = $_GET['long_link'];

if (!empty($request)) {
    echo 'Works!';

}
