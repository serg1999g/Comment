<?php
//fetch_comment.php

require_once 'db.php';

$comment = '';
$level = 0;
$query[0] = "SELECT * FROM tbl_comment WHERE parent_id = 0";
$result = mysqli_query($link, $query[0]);
$comments[0] = mysqli_fetch_all($result, 1);

if (!empty($comments[0])) {
    for ($id_counter = 1; $id_counter < 10; $id_counter++) {
        $query[$id_counter] = 'SELECT * FROM tbl_comment WHERE parent_id = ' . $comments[$id_counter - 1][0]['id'];

        for ($i = 1; $i < count($comments[$id_counter - 1]); $i++) {
            $query[$id_counter] .= ' OR parent_id = ' . $comments[$id_counter - 1][$i]['id'];
        }
        $result = mysqli_query($link, $query[$id_counter]);
        $comments[$id_counter] = mysqli_fetch_all($result, 1);

        if (empty($comments[$id_counter])) {
            break;
        }
    }
}
// print_r($comments);
for ($i = 0; $i < count($comments) - 1; $i++) {
    $level = $level + 1;
    foreach ($comments[$i] as $row) {
        $output['comment'] .= '
            <div class="wrapper id-' . $row["id"] . ' level-' . $level . ' parent-id-' . $row["parent_id"] . '">
         <div class="panel panel-default">
            <div class=img-block>

            </div>
            <div class=wrapper-panel>
            <div class="panel-heading"><span>' . $row["sender_name"] . ' </span> <i>' . $row["date"] . '</i></div>
          <div class="panel-body">' . $row["text"] . '</div>
          <div class="panel-footer" ><button type="button" class="btn btn-secondary reply" id="' . $row["id"] . '">Ответить</button></div>
         <hr>
            </div>
          </div>
          </div>
         ';
        if ($i > 0) {
            $output['script'][] = [
                'parent_id' => $row["parent_id"],
                'id' => $row["id"]
            ];
        }
    }
}

echo json_encode($output);
