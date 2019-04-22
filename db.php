<?php


// $connect = new PDO('mysql:host=localhost;dbname=comment', 'root', '');
$link = mysqli_connect('localhost', 'root', '', 'comment');
if (mysqli_connect_errno()) {
    print_r('Ошибка подключения бд ' . mysqli_connect_errno() . ': ' . mysqli_connect_error());
} else {
    // print_r('Всё збс');
}
