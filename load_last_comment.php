<?php
//load comment

require_once('db.php');

// загружием последний коммент


$query = "SELECT * FROM `tbl_comment` ORDER BY id DESC LIMIT 1";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();


if (isset($_POST['level'])) {
  $level = $_POST['level'];
  $level = substr($level, 6, 7);
  $marginleft = $level * 100;
}
$level = $level + 1;
$row=[];

foreach ($result as $comment[0]) {
  $output['comment'] .= '
  <div class="wrapper id-' . $comment[0]["id"] . ' level-' . $level . ' parent-id-' . $row["parent_id"] . '">
  <div class="panel panel-default">
  <div class=img-block>

  </div>
  <div class=wrapper-panel>
  <div class="panel-heading"><span>' . $comment[0]["sender_name"] . ' </span> <i>' . $comment[0]["date"] . '</i></div>
  <div class="panel-body">' . $comment[0]["text"] . '</div>
  <div class="panel-footer"><button type="button" class="btn btn-secondary reply" id="' . $comment[0]["id"] . '">Ответить</button></div>
  <hr>
  </div>
  </div>
  </div>
';
}

$output['script'][] = [
  'parent_id' => $comment[0]["parent_id"],
  'id' => $comment[0]["id"]
];
echo json_encode($output);
