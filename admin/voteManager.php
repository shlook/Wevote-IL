<?include("checkSession.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> New Document </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <meta http-equiv=content-type content="text/html; charset=UTF-8">
 </head>

 <body>
<? 
include("../utils.php");

include("../db.php");
$p_vote_id = gp("vi");
$msgData = "";
if($p_vote_id)
{
		$sqlText = "delete from vote where vote_id = $p_vote_id";
		
		mysql_query($sqlText);
		$msgData = "מחיקה הצליחה";
}
include("../vote/vote.class.php");
?>
<script type="text/javascript">
<!--
	function del1(voteId)
	{
		if(confirm("Are you sure you want to delete this vote?"))
		{
			location.href="voteManager.php?vi="+voteId;
		}
	}
//-->
</script>
<table align=center>
 <tr>
	<td><a href="voteManager.php">הצבעות</a>&nbsp;&nbsp;&nbsp;
	 <a href="vote.php">הצבעה חדשה</a></td>
 </tr>
 </table>
<hr>
<table align=center>
<tr>
	<td>
		<?
if($msgData)
	echo "<font color=red>$msgData</font>";
?>
<br>
<h2>הצבעות פתוחות</h2>

<?

foreach (Vote::getAllOpenVotes() as $vote) 
{	
	print $vote->getUrl();
	?>
	
 
	<?
	echo " - נסגרת " . getHumanDateDiff(strtotime($vote->finish));
	echo "<a href='javascript:del1($vote->id)'>מחיקה</a>"
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
</td>
</tr>
</table>
 </body>
</html>
