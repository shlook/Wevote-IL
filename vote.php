<?
include("utils.php");
include_once("db.php");
include("vote/vote.class.php");
addJsTag("js/vote.js");


$p_vote_id = gp("vi");
$p_option_id = gp("oi",1, true);
$p_is_cancel = gp ("cancel", 1, true);
$vote = new Vote($p_vote_id);
$TITLE_STR = "הצבעה - ".$vote->subject;
include("header.php");

if ($me) {
	$memberId = $_SESSION['member_id'];
	$member = new Member($memberId);
}

if ($p_is_cancel) {
	$vote->removeBallot($memberId);
}

if($p_option_id)
	$vote->setUserSelection($memberId, $p_option_id);
$ONLOAD_STR = "startVote();";
$TITLE_STR = "הצבעה";

?>
  <script type="text/javascript">
	function makePost(frmName)
	{
		document.forms[frmName].submit();
	}
	var lastOptionId = null;
	function showMembers(optionId)
	{
		if(lastOptionId&& lastOptionId!=optionId)
		{
			//alert(3)
			document.getElementById('members_'+lastOptionId).style.display='none';
			eval("op_"+lastOptionId+"=false;");

		}
		
		if(eval("window.op_"+optionId))
		{
			//alert(3)
			document.getElementById('members_'+optionId).style.display='none';
			eval("op_"+optionId+"=false;");

		}
		else
		{
			//alert(1)
			lastOptionId = optionId;
			document.getElementById('members_'+optionId).style.display='';
			eval("op_"+optionId+"=true;");
		}
	}
  </script>
<?php
$humanDateVoteStart = getHumanDateDiff(strtotime($vote->start));
$humanDateVoteFinish = getHumanDateDiff(strtotime($vote->finish));
?>
<div id="voteContent">
	<div id="voteName"><?=$vote->subject?></div>
	<div id="voteStatus">הצבעה #<?=$vote->id?>, נפתחה <?=$humanDateVoteStart?>,  
			<? if ($vote->isOpen()) {
				echo "תיסגר ";
			   } else {
				echo "נסגרה ";
			   }
			   echo $humanDateVoteFinish;
			?>
	</div>
	<div id="voteBody"> <?=$vote->body?> </div>
						
			<?
			$message = "";
			$option_id = 0;
			if(!$login->isLogged())
			{
					$message = "היכנס למערכת כדי להצביע.";
			}
			else
			{
				list($option_id, $throughtMovilId) = ($member->getOptionIdVoted($p_vote_id));
				if(!$option_id)
				{
					$message = "לא הצבעת בהצבעה זו.";
				}													
			}
			?>

			<?
			if($message)
			{
			?>
				<?=$message?>
			<?
			}
			?>

				    <?
						if ($vote->isOpen()) {
							echo "<h4>ההצבעה פתוחה</h4>";
					?>
						<h5>ישנן <?=$vote->getOptionsCount()?> אפשרויות בחירה להצבעה:</h5>
					<?
						} else {
							echo "<h4>ההצבעה סגורה</h4>";
					?>
						<h5>היו <?=$vote->getOptionsCount()?> אפשרויות בחירה להצבעה:</h5>
					<?
						}
					
					?>
					
					
					<?
					$query = "SELECT vo.vote_option_id, name, body 
					FROM vote_option vo, vote_vote_option vvo where vo.vote_option_id = vvo.vote_option_id and vvo.vote_id=" . $p_vote_id;
					$result = $db->query($query);
					$count = 0;
					while ($line = $db->fetchNextObject($result))
					{	
						$count++;
						?>
						
						<p><?=$count . ". " . $line->name?></p>
						
						<?
						if($option_id!=$line->vote_option_id)
						{
							if(($login->isLogged()) and ($vote->isOpen()))
							{
						?>
						    
							<div id='voteOptionStatus'>
								<form id="frmForOptionId<?=$line->vote_option_id?>" action="vote.php?vi=<?=$p_vote_id?>" method="post">
									<input type="hidden" name="oi" value="<?=$line->vote_option_id?>">
									<a href="javascript:makePost('frmForOptionId<?=$line->vote_option_id?>')">בחר באפשרות הזאת</a>
								</form>
							</div>
						<?
							}
						}
						else
						{
							echo "<div id='voteOptionStatusVoted'>";
							if ($throughtMovilId) {
								$movil = new Member ($throughtMovilId);
								echo "בחרת באפשרות הזאת(דרך " . $movil->getUrl() . ")" . "";
							} else {
								echo "בחרת באפשרות הזאת";
							}
							if ($vote->isOpen()) {
							?>
								<form id="frmForOptionId<?=$line->vote_option_id?>" action="vote.php?vi=<?=$p_vote_id?>" method="post">
									<input type="hidden" name="cancel" value="1">
									<a href="javascript:makePost('frmForOptionId<?=$line->vote_option_id?>')">(בטל)</a>
								</form>
								
							<?php
							}
							echo "</div>";
						}
						?>
						
						<?
					}
						?>
</div>
<div id="VoteResultContent">						
			<h4>
			<?php
				if ($vote->isOpen()) {
					echo "תוצאות ההצבעה עד כה:";
				} else {
					echo "תוצאות ההצבעה הסופיות:";
				}
			?>
			</h4>
			<?php
if ($vote->getBallotCount()) {
include_once 'ofc-library/open_flash_chart_object.php';
open_flash_chart_object( 200, 200, 'data-2.php?vi='.$p_vote_id, false );
} else {
echo "<br><br><br><br><br>";
}
?>
					<?
					$query = "SELECT vo.vote_option_id, name, body 
					FROM vote_option vo, vote_vote_option vvo where vo.vote_option_id = vvo.vote_option_id and vvo.vote_id=" . $p_vote_id;
					$result = $db->query($query);
					$count = 0;
					$query2 = "SELECT count(*) as count from vote_ballot where vote_id = $p_vote_id" ;
					$result2 = $db->query($query2);
					$totalVotes = 0;	
					if ($line2 = $db->fetchNextObject($result2))
					{
						$totalVotes = $line2->count;
					}
					while ($line = $db->fetchNextObject($result))
					{	
						$count++;
						?>
						<p><?=$count . ". " . $line->name?></p>
											
						<?
						$query = "SELECT count(*) as count from vote_ballot where vote_id = $p_vote_id and vote_option_id = $line->vote_option_id" ;
						$result2 = $db->query($query);
						
						if ($line2 = $db->fetchNextObject($result2))
						{
							$count2 =  $line2->count;
							?>
							<div class="countBallot"><a class="ballotMembersCount" href="javascript:showMembers(<?=$line->vote_option_id?>)"><?=$count2?> חברים</a>
							<div id="members_<?=$line->vote_option_id?>" style="z-index:100;border:1px solid black;background:white;position:absolute;display:none"><div style="position:relative;">
							<?php
								$votedByMembersQuery = "select m.member_id member_id, m.name name, m.fb_uid fb_uid, vb.movil_id movil_id from member m, vote_ballot vb
								          where m.member_id = vb.member_id and vb.vote_id = " . $p_vote_id . 
										  " and vb.vote_option_id = " . $line->vote_option_id;
								$votedByMembersResult = $db->query($votedByMembersQuery);
							?>
							<TABLE >
							<?php
							while ($votedByMembersRow = $db->fetchNextObject($votedByMembersResult)) {
							?>
								<TR height=30>								
									<TD><img src="https://graph.facebook.com/<?php echo $votedByMembersRow->fb_uid; ?>/picture" ></TD>
									<TD valign=top><a href="member.php?mi=<?=$votedByMembersRow->member_id?>" style="font-size:11px">
									<?php
										echo $votedByMembersRow->name; 
									?> </a>
									<?php
										if ($votedByMembersRow->movil_id) {
											$movilName = $db->fetchNextObject($db->query("select name from member where member_id = " . $votedByMembersRow->movil_id));
											echo "<span style='font-size:11px'> דרך </span>";
									?>		
											<a href="member.php?mi=<?=$votedByMembersRow->movil_id?>" style="font-size:11px">
									<?php
											echo $movilName->name;
									?>
											</a>
									<?php
										}
									?>
									</TD>
								</TR>
							<?php
							}
							?>
							</TABLE>
							
							</div></div>
							הצביעו לאפשרות הזאת
							<?php
							
							if ($totalVotes) {
								echo "(" . round($count2*100/$totalVotes) . "%)";
							}
							
							
							?>
							</div>

							<?
						}

					}


						?>
</div>


<div id="voteBottom">
<br><br><br>
<hr>

<h2>תגובות</h2>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=<?php echo $fbconfig[$currentEnv]['appid' ] ?>&amp;xfbml=1"></script><fb:comments xid="<?php echo "vote_" . $vote->id ?>" numposts="10" width="625" publish_feed="false" css="http://kol1.org/wevote/Vote1/style/FBComments.css"></fb:comments>
</div>
<? include("footer.php")?>