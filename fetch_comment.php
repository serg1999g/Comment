<?php
//fetch_comment.php

require_once('db.php');

$comment = '';
$level = 0;


/* Создаем таблицу */
$statement = $connect->prepare("CHECK TABLE tbl_comment");
$statement->execute();
$result = $statement->fetchAll();
if ($result[0]['Msg_type'] == 'Error') {
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Error Handling
    $sql = "CREATE TABLE `tbl_comment` (
        `id` int(11) NOT NULL,
        `parent_id` int(11) NOT NULL,
        `text` text NOT NULL,
        `sender_name` varchar(40) NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
      
      ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`id`);
  
  ALTER TABLE `tbl_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;";
    $connect->exec($sql);
}



$query[0] = "SELECT * FROM tbl_comment WHERE parent_id = 0";
$statement = $connect->prepare($query[0]);
$statement->execute();
$comments[0] = $statement->fetchAll();


if (!empty($comments[0])) {
    for ($id_counter = 1; $id_counter < 10; $id_counter++) {
        $query[$id_counter] = 'SELECT * FROM tbl_comment WHERE parent_id = ' . $comments[$id_counter - 1][0]['id'];
        for ($i = 1; $i < count($comments[$id_counter - 1]); $i++) {
            $query[$id_counter] .= ' OR parent_id = ' . $comments[$id_counter - 1][$i]['id'];
        }

        $result = $connect->prepare($query[$id_counter]);
        $result->execute();
        $comments[$id_counter] = $result->fetchAll();
        if (empty($comments[$id_counter])) {
            break;
        }
    }
}


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
