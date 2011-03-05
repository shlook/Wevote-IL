<? 
include("utils.php");
include_once("db.php");

$ONLOAD_STR = "";
$TITLE_STR = "הצבעות";
include("header.php");
include("vote/vote.class.php");
?>
<h2>הצבעות פתוחות</h2>

<?
if ($login->isLogged()) {
	$member = new Member ($_SESSION['member_id']);

	if ($member->isAdmin) {
		echo "<a href=addVote.php>הוסף הצבעה</a><br>";
	}
}
foreach (Vote::getAllOpenVotes() as $vote) 
{	
	print $vote->getUrl();
	?>
	
 
	<?
	echo " - נסגרת " . getHumanDateDiff(strtotime($vote->finish));
	?>

	<br>
	<?
}
	?>
	
<h2>הצבעות סגורות</h2>

<?

foreach (Vote::getAllClosedVotes() as $vote) 
{
	print $vote->getUrl();	
	?>
		<?php
		if ($electedOption = $vote->getElectedOption()) {
			echo " - נבחר (" . $electedOption["totalPercentage"] . "%) : " . $electedOption["name"];
		} else {
			echo " - אין הכרעה";
		}
		?>
	<br>
	<?
}
	?>
			

<? include("footer.php")?>