<?
	include_once("secure/login.class.php");
	$login->doLogin("","");
	header("Location:votes.php");
?>