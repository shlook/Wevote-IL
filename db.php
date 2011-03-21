<?php
include("db.class.php");
$password = "***********";
$user = "******";
$server = "localhost";
$dbName = "*******";

$db = new DB($dbName, $server, $user, $password);
?>