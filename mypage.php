<?php
error_reporting(E_ALL);
include("secure/login.class.php");
include("member.class.php");

if ($login->isLogged()) {
	 header('Location: member.php?mi=' . $_SESSION['member_id']);
} else {
	include("header.php");
?>
	<p>עלייך להיות מחובר</p>
<?php
}
?>