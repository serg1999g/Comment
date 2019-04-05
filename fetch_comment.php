<?php
 //fetch_comment.php

require_once('db.php');


$query = "
SELECT * FROM tbl_comment 
WHERE parent_id = '0' 
ORDER BY id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';

/* adding a comment */

foreach ($result as $row) {
    $output .= '
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
 ';
    $output .= get_reply_comment($connect, $row["id"]);
}

echo $output;

/* add child comment */

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
    $query = "
 SELECT * FROM tbl_comment WHERE parent_id = '" . $parent_id . "'
 ";
    $output = '';
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();
    if ($parent_id == 0) {
        $marginleft = 0;
    } else {
        $marginleft = $marginleft + 100;
    }
    if ($count > 0) {
        foreach ($result as $row) {
            $output .= '
   <div class="panel panel-default" style="margin-left:' . $marginleft . 'px">
   <div class=img-block>
    
    </div>
    <div class=wrapper-panel>
    <div class="panel-heading"> <span>'. $row["sender_name"] . '</span> <i>' . $row["date"] . '</i></div>
    <div class="panel-body">' . $row["text"] . '</div>
    <div class="panel-footer" ><button type="button" class="btn btn-secondary reply" id="' . $row["id"] . '">Ответить</button></div>
   <hr>
    </div>
    </div>
   ';
            $output .= get_reply_comment($connect, $row["id"], $marginleft);
        }
    }
    return $output;
}
 