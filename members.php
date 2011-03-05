<? 
include("utils.php");
include_once("db.php");

$ONLOAD_STR = "";
$TITLE_STR = "חברים";
$p_show_option = gp("m");


include("header.php");


?>

<TABLE >

<h2>חברי המפלגה</h2>

<div id="viewOption">
<?php
	if ($p_show_option == 1) {
?>
		<a href="members.php?m=2">כולם</a> <a href="members.php?m=1"> <b>רק מובילי דעה</b></a> 
<?php
	} else {
?>
		<a href="members.php?m=2"><b>כולם</b></a> <a href="members.php?m=1"> רק מובילי דעה</a> 
<?php
	}



?>

</div>
<hr>
<?php
if ($p_show_option == 1) {
	$allMembersQuery = "select member_id, name, fb_uid from member where is_movil = 1";	
} else {
	$allMembersQuery = "select member_id, name, fb_uid from member";	
}

$allMembersResult = $db->query($allMembersQuery);
while ($allMembersResultRow = $db->fetchNextObject($allMembersResult)) {
?>

<TR height=30>								

<TD><img src="https://graph.facebook.com/<?php echo $allMembersResultRow->fb_uid; ?>/picture" ></TD>
<TD valign=top><a href="member.php?mi=<?=$allMembersResultRow->member_id?>" style="font-size:15px">

<?php
	echo $allMembersResultRow->name; 
?> </a>
</TR>
<?
}
?>
</TABLE>
			

<? include("footer.php")?>