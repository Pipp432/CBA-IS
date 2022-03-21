<?php

require 'config.php';

echo $_GET['id'];
echo $_GET['salt'];

$sql = $db->prepare("update customers set customers.customer_verified_date = current_timestamp where customers.customer_no = ? and customers.customer_salt = ?");
$sql->execute([$_GET['id'], $_GET['salt']]);

?>