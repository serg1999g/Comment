<?php
//add_comment.php

//Включение вывода всех ошибок и предупреждений в коде PHP-скриптов
/* ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); */

require_once('db.php');


$error = '';
$comment_name = '';
$comment_content = '';

$post_comment_name = $_POST['comment_name'];
$post_comment_content = $_POST["comment_content"];
$post_parent_id = (int)$_POST['parent_id'];


if (mb_strlen($post_comment_name) > 15) {
    $error .= '<p class="text-danger">Слишком длинное имя</p>';
}

if (empty($post_comment_name)) {
    $error .= '<p class="text-danger">Введите Имя</p>';
} else {
    $comment_name = $post_comment_name;
}

if (empty($post_comment_content)) {
    $error .= '<p class="text-danger">Введите комментарий</p>';
} else {
    $comment_content = $post_comment_content;
}

if ($error == '') {
    $query = "
 INSERT INTO tbl_comment 
 (parent_id, text, sender_name) 
 VALUES (:parent_id, :text, :sender_name)
 ";
    $statement = $connect->prepare($query);
    $varToCheck = $statement->execute(
        array(
            ':parent_id' => $post_parent_id,
            ':text'    => $comment_content,
            ':sender_name' => $comment_name
        )
    );
    $success = '<p class="text-success">Добавлен комментарий</p>';

}

if (empty($error)) {
    $data = array(
        'message'  => $success
    );
} else {
    $data = array(
        'message'  => $error
    );
}

echo json_encode($data);

