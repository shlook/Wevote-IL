<?php include("checkSession.php"); ?>
 <?

include("../utils.php");
include("../db.php");
include("../vote/vote.class.php");

$p_vote_id = gp("vi");

$msgData = "";
if($_POST)
	$issubmit =  $_POST['issubmit'];
if(isset($issubmit))
{
	if($p_vote_id=="0")
	{
			$sqlText = "INSERT INTO `vote` ( `vote_subject`, `vote_body`, `vote_created`, `vote_finish`) VALUES
				( '".$_POST['subject']."','".$_POST['body']."' , now(), '".$_POST['finish']."');";
		
			mysql_query($sqlText);
			$sqlText = "select max(vote_id) as vote_id from vote";

			$result = mysql_query($sqlText);
			if($row1 = mysql_fetch_array( $result )) 
			{
				$p_vote_id = $row1["vote_id"];
				$sqlText = "INSERT INTO `vote_vote_option` (`vote_id`, `vote_option_id`) VALUES($p_vote_id, 1),($p_vote_id, 2)";
		
				mysql_query($sqlText);
			}
			//header("Location:index.php?is=1&status=0&city=".$city);			
			$msgData = "הכנסת רשומה הצליחה";
			//header("Location:voteManager.php");
	}
	else
	{
		$sqlText = "update `vote` set `vote_subject`='".$_POST['subject']."',`vote_body`='".$_POST['body']."',`vote_created`='".$_POST['start']."',`vote_finish`='".$_POST['finish']."' where vote_id = $p_vote_id";
		$msgData = "שמירה הצליחה";
		mysql_query($sqlText);
	}
}
$vote = new Vote($p_vote_id);
if($vote->finish=="")
{
	$vote->finish = "2011-01-01 22:00:00";
	$vote->start = "אוטומטי -  לא להכניס";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>עריכת הצבעה</title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta http-equiv=content-type content="text/html; charset=UTF-8">
  <meta name="Description" content="">
 </head>

 <body>
 <table align=center>
 <tr>
	<td><a href="voteManager.php">הצבעות</a>&nbsp;&nbsp;&nbsp;
	 <a href="vote.php">הצבעה חדשה</a></td>
 </tr>
 </table>
<hr>

<form method=post action="vote.php?vi=<?php echo $p_vote_id?>">
<input type="hidden" name="issubmit"  value="1">

 <table align=center dir=rtl>
 <tr>
	<td colspan=2>
	<?
if($msgData)
	echo "<font color=red>$msgData</font>";
?>
<br>
	 סוג הצבעה:
 <input type="radio" checked>בעד\נגד
 <input type="radio" >אפשרויות

	</td>
 </tr>
 <tr>
	<td>
		כותרת:
	</td>
	<td><input type="text" name="subject" value="<?=$vote->subject?>" size=40></td>
 </tr>
 <tr>
	<td>
		תיאור:
	</td>
	<td>
	<textarea cols=40 rows=3 name="body"><?=$vote->body?></textarea>
	</td>
 </tr>
  <tr>
	<td>
		התחלה:
	</td>
	<td><input type="text" disbaled name="start" value="<?=$vote->start?>"></td>
 </tr>
  <tr>
	<td>
		סיום:
	</td>
	<td><input type="text" name="finish" value="<?=$vote->finish?>"></td>
 </tr>
  <tr>
	<td colspan=2 align=center>
	<input type=submit value="שלח">

	</td>
 </tr>
 </table>
</form> 

 </body>
</html>
