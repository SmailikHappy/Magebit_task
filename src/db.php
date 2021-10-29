<?php
    $connection = mysqli_connect('127.0.0.1', 'root', '', 'subscriptions');

    if ($connection == false) {
        echo 'Connection with database failed';
        echo mysqli_connect_error();
        exit();
    }
?>