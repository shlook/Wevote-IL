<?
include_once("utils.php");
include_once("db.php");
include_once("vote/vote.class.php");


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