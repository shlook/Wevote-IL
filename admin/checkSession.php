<?php
session_start();  
if(!$_SESSION||$_SESSION['islogged']!=1)
{
	header("Location:login.php");
	exit;
}
?>