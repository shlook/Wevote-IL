<?

include("utils.php");
include_once("db.php");
include("vote/vote.class.php");
$p_member_id = gp("mi");
include_once("member.class.php");
$memberPage = new Member ($p_member_id);
$TITLE_STR = "דף חבר - " . $memberPage->name;
include("header.php");

$isSetAsMovil = gp("setmovil",1, true);
$isChooseAsMovil = gp("choosemovil", 1, true);
$i = 0;

if ($isSetAsMovil == 1) {
	if ($login->isLogged()) {
		$currentMember = new Member($_SESSION['member_id']);
		$currentMember->setAsMovil();
		$message = "נקבעת כמוביל דעה";
	}
}
if ($isSetAsMovil == 2) {
	if ($login->isLogged()) {
		$currentMember = new Member($_SESSION['member_id']);
		$currentMember->unsetAsMovil();
		$message = "בוטלת כמוביל דעה";
	}
}

if ($isChooseAsMovil == 1) {
	if ($login->isLogged()) {
		$currentMember = new Member($_SESSION['member_id']);
		$currentMember->chooseMovil($p_member_id);
		$message = "מוביל דעה נקבע בהצלחה";
	}
}

if ($isChooseAsMovil == 2) {
	if ($login->isLogged()) {
		$currentMember = new Member($_SESSION['member_id']);
		$currentMember->unChooseMovil($p_member_id);
		$message = "ביטול מינוי מוביל דעה בוצע בהצלחה";
	}
}


$isMyPage = ($login->isLogged()) && ($p_member_id == $_SESSION['member_id']);


if (!($p_member_id)) {
	die ("Error, no member id");
} else {
	$member = new Member($p_member_id);
	if ($login->isLogged()) {
		$me = new Member($_SESSION['member_id']);
	}
	?>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<SCRIPT LANGUAGE="JavaScript" SRC="http://www.appelsiini.net/download/jquery.jeditable.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
 $(document).ready(function() {
     $('.edit').editable('http://www.example.com/save.php', {
         indicator : 'שומר',
         tooltip   : 'לחץ כדי לערוך...'
     });
     $('.edit_area').editable('saveaboutme.php', { 
         type      : 'textarea',
         cancel    : 'ביטול',
         submit    : 'עדכן',
         indicator : '<img src="images/spinner4-black.gif">',
         tooltip   : 'לחץ כדי לערוך...',
		 rows      : 10
     });
 });
</script>
	  <script type="text/javascript">
	function makePost(frmName)
	{
		document.forms[frmName].submit();
	}
  </script>

	<?php
		if ((isset($message)) && ($message!="")) {
			echo "<div id=\"message\">";
			echo "<img  ALIGN=right src=\"images/show_info.png\">";
			echo "<p>$message</p>";
			echo "</div>";
		}
	?>

<br>
	
<h2><?=$member->name?></h2>	
	
<img src="https://graph.facebook.com/<?php echo $member->fbUid; ?>/picture"></td><td class="memberName">
			
					<?
	if ($member->isMovil) {
		echo "<div id='MemberStatus'> מוביל דעה </div>";
		#echo "<h2> אפשרויות </h2>";
		if ($isMyPage) {
		?>
		    <div id="MemberOption">
			<form id="frmForMember<?=++$i?>" action="member.php?mi=<?=$me->id?>" method="post">
				<input type="hidden" name="setmovil" value="2">
				<a href="javascript:makePost('frmForMember<?=$i?>')" class="setMovil"><img  ALIGN=right src="images/remove.png"> בטל עצמך כמוביל דעה </a>
			</form>
			</div>
		<?php
		} else {
			if (($login->isLogged()) and (!($me->isMovil))) {
				if ((isset($me->getMovil()->id)) and ($me->getMovil()->id == $member->id)) {
					
					?>
					<div id="MemberOption">
								<form id="frmForMember<?=++$i?>" action="member.php?mi=<?=$member->id?>" method="post">
									<input type="hidden" name="choosemovil" value="2">
									<a href="javascript:makePost('frmForMember<?=$i?>')" class="setMovil"><img ALIGN=right src="images/remove.png"> בטל כמוביל הדעה שלך </a>
								</form>
								</div>
					<?php
				} else {
				?>
				<div id="MemberOption">
								<form id="frmForMember<?=++$i?>" action="member.php?mi=<?=$member->id?>" method="post">
									<input type="hidden" name="choosemovil" value="1">
									<a href="javascript:makePost('frmForMember<?=$i?>')" class="setMovil"><img ALIGN=right src="images/applications.png"> קבע כמוביל הדעה השלך </a>
								</form>
								</div>
				<?php
				}
			}
			
		}		
		
	} else if ($member->getMovil()) {
		echo "<div id='MemberStatus'>מובל על ידי " . $member->getMovil()->getUrl() . "</div>" ;
		if ($isMyPage) {
		?>
		
		<div id="MemberOption">
			<form id="frmForMember<?=++$i?>" action="member.php?mi=<?=$member->id?>" method="post">
				<input type="hidden" name="choosemovil" value="2">
				<a href="javascript:makePost('frmForMember<?=$i?>')" class="setMovil"><img ALIGN=right src="images/remove.png"> בטל כמוביל הדעה שלך </a>
			</form>
			</div>
		
<?php		
		}
	} else {
		if ($isMyPage) {
		#echo "<h2> אפשרויות </h2>";
		?>
		<div id="MemberOption">
			<form id="frmForMember<?=++$i?>" action="member.php?mi=<?=$me->id?>" method="post">
				<input type="hidden" name="setmovil" value="1">
				<a href="javascript:makePost('frmForMember<?=$i?>')" class="setMovil"><img ALIGN=right src="images/applications.png"> קבע עצמך כמוביל דעה </a>
			</form>
			</div>
		<?php
		}
	}	
					?>

<h2>עליי:</h2>
<?php
	if ($isMyPage) {
		$className = "edit_area";
	} else {
		$className = "viewOnly";
	}
?>

<div class="<?=$className?>" id="div_2" style="width:400px;">	
<?php
if ($member->aboutMe) {
	echo $member->aboutMe;
} else {
	if ($isMyPage) {
		echo "(לחץ כדי לשנות)";
	} else {
		echo "(טרם נכתב)";
	}
}
	
?>
</div>
<br>
<br>

					

	<?
			if ($member->isMovil)
			{
	?>
	
		<?php
		if ($member->getMuvalsAsMemberObj()) {
		?>
				<h2>מובלים:</h2>
			<?php
				foreach ($member->getMuvalsAsMemberObj() as $muvalMember) 
				{	
					echo $muvalMember->getUrl()."<br>";
				}
			
			?>
		<?php
		} else {
		?>
			<br>
		<?php
		}
		?>

	<?
		}
		?>
	<h2>הצבעות אחרונות:</h2>
		
			<?
				$timeline = array ();
				$query = "SELECT vb.vote_id, 	vb.vote_option_id, 	vb.member_id, 	vb.movil_id, 	vb.creation_date, vo.name
			FROM vote_ballot vb, vote_option vo
			where vb.vote_option_id = vo.vote_option_id and vb.member_id = " . $member->id .
			" ORDER BY creation_date DESC
			LIMIT 0 , 5";
$result = $db->query($query);
while ($rec = $db->fetchNextObject($result)) {
	$member = new Member($rec->member_id);
	$vote = new Vote ($rec->vote_id);
	$timeline[$rec->creation_date] = "'" . $rec->name . "' בהצבעה " . $vote->getUrl();
	if ($rec->movil_id) {
		$movil = new Member ($rec->movil_id);
		$timeline[$rec->creation_date] = $timeline[$rec->creation_date] . "(דרך " . $movil->getUrl() . ")";
	}
}
?>
<div id="operation">
<?
krsort($timeline);
foreach ($timeline as $key => $val) {
    echo "<p>$val</p>\n";
}

?>
</div>


<hr>

<h2>תגובות</h2>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=<?php echo $fbconfig[$currentEnv]['appid' ] ?>&amp;xfbml=1"></script>
<fb:comments xid="<?php echo "member_" . $member->id ?>" numposts="10" width="625" publish_feed="false" css="http://kol1.org/wevote/Vote1/style/FBComments.css"></fb:comments>


	
<?php	
}

include ("footer.php");
?>

