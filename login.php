<?
	include("secure/login.class.php");
	$login->doLogin("","");
	header("Location:votes.php");
?>