<?php

try {
  $db = new PDO('mysql:dbname=cbachula_bizcube; host=localhost', 'cbachula_bizcube', 'Bizcube2021', array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
  $db->exec("SET NAME UTF8");
} catch(PDOException $exc) {
  echo $exc->getMessage();
}

?>