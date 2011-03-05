<?php
session_start(); 
include("utils.php");
include_once("db.php");

$aboutMe = gp("value", 2, true);
$aboutMe = strip_tags($aboutMe);
$query = "update member set about_me = '$aboutMe' where member_id = " . $_SESSION['member_id'];
$db->query($query);

function replace_newline($string) {
  return (string)str_replace(array("\r", "\r\n", "\n", "\\r", "\\r\\n", "\\n"), ' ', $string);
}



echo replace_newline($aboutMe);

?>