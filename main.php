<?
include("utils.php");
include_once("db.php");
include("vote/vote.class.php");
$TITLE_STR = "עמוד ראשי";
include("header.php");

$i = 0;

# Get latest members

$timeline = array ();
$query = "SELECT member_id, creation_date
			FROM member
			ORDER BY creation_date DESC
			LIMIT 0 , 5";

			
$result = $db->query($query);
while ($rec = $db->fetchNextObject($result)) {
	$i++;
	$member = new Member($rec->member_id);
	$timeline[$rec->creation_date . $i] = $member->getUrl() . " הצטרף למערכת.";
}

# get movil chooses

$query = "select * from movil_muval
			ORDER BY creation_date DESC
			LIMIT 0 , 5";

			
$result = $db->query($query);
while ($rec = $db->fetchNextObject($result)) {
	$i++;
	$muval = new Member($rec->member_id_muval);
	$movil = new Member($rec->member_id_movil);
	$timeline[$rec->creation_date . $i] = $muval->getUrl() . " בחר ב" .$movil->getUrl() . " כמוביל.";
}

# Get latest ballots;

$query = "SELECT vb.vote_id, 	vb.vote_option_id, 	vb.member_id, 	vb.movil_id, 	vb.creation_date, vo.name
			FROM vote_ballot vb, vote_option vo
			where vb.vote_option_id = vo.vote_option_id
			ORDER BY creation_date DESC
			LIMIT 0 , 5";
$result = $db->query($query);

while ($rec = $db->fetchNextObject($result)) {
	$i++;
	$member = new Member($rec->member_id);
	$vote = new Vote ($rec->vote_id);
	$timeline[$rec->creation_date . $i] = $member->getUrl() . " הצביע '" . $rec->name . "' בהצבעה " . $vote->getUrl();
	if ($rec->movil_id) {
		$movil = new Member ($rec->movil_id);
		$timeline[$rec->creation_date  . $i] = $timeline[$rec->creation_date  . $i] . "(דרך " . $movil->getUrl() . ")";
	}
}			

# get Latest closed Votes

foreach (Vote::getAllClosedVotes() as $vote) {
	$i++;
	$timeline[$vote->finish . $i] = "ההצבעה " . $vote->getUrl() . " נסגרה";
	if ($electedOption = $vote->getElectedOption()) {
		$timeline[$vote->finish . $i] = $timeline[$vote->finish . $i] . " בתוצאה '" . $electedOption["name"] . "'";
	} else {
		$timeline[$vote->finish . $i] = $timeline[$vote->finish . $i] . " ללא הכרעה.";
	}
}

# get Latest open votes


$query = "SELECT vote_id, vote_created
			FROM vote
			ORDER BY vote_created DESC
			LIMIT 0 , 5";

			
$result = $db->query($query);

while ($rec = $db->fetchNextObject($result)) {
	$i++;
	$vote = new Vote($rec->vote_id);
	if ($vote->isOpen()) {
		$timeline[$rec->vote_created . $i] = "נוספה הצבעה חדשה:" . $vote->getUrl();
	}
}
?>
<h2>מן הנעשה באתר...</h2>
<div id="operation">
<?php
krsort($timeline);
foreach ($timeline as $key => $val) {
    echo "<p>$val</p>\n";
}

?>
</div>


<? include("footer.php")?>