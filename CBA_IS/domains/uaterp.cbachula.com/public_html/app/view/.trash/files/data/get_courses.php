<?php

require 'config.php';

$sql = $db->prepare("select * from batches join courses on courses.course_no = batches.course_no");
$sql->execute();

echo json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);

?>