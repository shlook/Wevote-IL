<?
class Vote
{
	function Vote($voteId)
	{			
		$this->id = $voteId;
		$this->start = "";
		$this->subject ="";						
		$this->finish ="";	
		$this->body = "";
		$this->startVoteData($voteId);	

	}	
	

	function getAllOpenVotes () {
		global $db;
		$query = "select vote_id from  vote order by vote_finish desc";
		$result = $db->query($query);
		$openVotes = array();
		while ($voteRec = $db->fetchNextObject($result)) {
			$currentVote =  new Vote($voteRec->vote_id);
			
			#print_r ($voteRec);
			if ($currentVote->isOpen()) {
				array_push($openVotes, $currentVote);
			}
		}
		return $openVotes;
	}


	function getAllOpenVotes2 () {
		global $db;
		$query = "select vote_id from vote where unix_timestamp(vote_finish) > " . time() . " order by vote_finish desc";
		$result = $db->query($query);
		$openVotes = array();
		while ($voteRec = $db->fetchNextObject($result)) {
			#print_r ($voteRec);
			array_push($openVotes, new Vote($voteRec->vote_id));
		}
		return $openVotes;
	}
	

	function getAllClosedVotes () {
		global $db;
		$query = "select vote_id from vote order by vote_finish desc";
		$result = $db->query($query);
		$openVotes = array();
		while ($voteRec = $db->fetchNextObject($result)) {
			$currentVote =  new Vote($voteRec->vote_id);
			
			#print_r ($voteRec);
			if (!($currentVote->isOpen())) {
				array_push($openVotes, $currentVote);
			}
		}
		return $openVotes;
	}
	
	
	function getAllClosedVotes2 () {
		global $db;
		$query = "select vote_id from vote where unix_timestamp(vote_finish) <= " . time() . " order by vote_finish desc";
		$result = $db->query($query);
		$openVotes = array();
		while ($voteRec = $db->fetchNextObject($result)) {
			#print_r ($voteRec);
			array_push($openVotes, new Vote($voteRec->vote_id));
		}
		return $openVotes;
	}
	
	function startVoteData($voteId)
	{
		global $db;
		$query = "SELECT vote_subject, vote_body, vote_finish, vote_created
						FROM vote where vote_id = " . $voteId;;
		$result = $db->query($query);
		
		if ($line = $db->fetchNextObject($result))
		{
			$this->start = $line->vote_created;
			$this->subject = $line->vote_subject;						
			$this->finish = $line->vote_finish;	
			$this->body = $line->vote_body;
		}
		//else
			//die("Not Found!"); 
	}
	function setUserSelection($memberId, $voteOptionId)
	{
		global $db;
		
		if ($this->isOpen()) {
			$member = new Member($memberId);
			if ($member->isMovil) {
				$sql = "DELETE FROM vote_ballot WHERE member_id = $memberId and vote_id = $this->id";
				$db->query($sql);
				$sql = "DELETE FROM vote_ballot WHERE movil_id = $memberId and vote_id = $this->id";
				$db->query($sql);
				$sql = "INSERT INTO vote_ballot (member_id, vote_id, vote_option_id) VALUES ($memberId, $this->id, $voteOptionId)";
				$db->query($sql);
				foreach ($member->getMuvalsForVote($this->id) as $muval) {
					$sql = "INSERT INTO vote_ballot (member_id, vote_id, vote_option_id, movil_id) VALUES ($muval, $this->id, $voteOptionId, $memberId)";
					$db->query($sql);
				}

			} else {
			$query = "delete from vote_ballot where  vote_id = $this->id  and member_id = $memberId" ;
			$db->query($query);
			$query = "INSERT INTO vote_ballot (member_id, vote_id, vote_option_id) VALUES ($memberId, $this->id, $voteOptionId)";		
			//echo 
			$db->query($query);
			}
		}
	}


	function getBallotCount() 
	{
		global $db;
		$query = "SELECT count(*) as count from vote_ballot where vote_id = " . $this->id;
		$result = $db->query($query);
		if ($line = $db->fetchNextObject($result))									
		{
			return $line->count;
		}
		return 0;
		
	}
	
	
	function getOptionsCount() 
	{
		global $db;
		$query = "SELECT count(*) as count from vote_vote_option
					 WHERE vote_id =" . $this->id ;
		$result = $db->query($query);
		
		if ($line = $db->fetchNextObject($result))									
		{
			return $line->count;
		}
		return 0;
		
	}
	
	function getOrderedResults () {
		global $db;
		$voteOptions = array();
		$voteOption = array();
		$countQuery = "select count(*) cnt from vote_ballot where vote_id = " . $this->id;
		$result = $db->query($countQuery);
		if ($rec = $db->fetchNextObject($result)) {
			$totalBallots = $rec->cnt;
		}
		$query = 	"SELECT vo.vote_option_id option_id, vo.name name, count(*) cnt
					FROM vote_ballot vb, vote_option vo
					WHERE vb.vote_option_id = vo.vote_option_id
					AND vote_id = $this->id
					GROUP BY vo.vote_option_id
					ORDER BY cnt DESC";
		$result = $db->query($query);
		while ($rec = $db->fetchNextObject($result)) {
			$voteOption["id"] = $rec->option_id;
			$voteOption["name"] = $rec->name;
			$voteOption["totalNumber"] = $rec->cnt;
			$voteOption["totalPercentage"] = @round(($rec->cnt / $totalBallots) * 100);
			array_push($voteOptions, $voteOption);
		}
		return $voteOptions;
	}
	
	function getElectedOption () {
		$orderedOptions = $this->getOrderedResults();
		if ((isset($orderedOptions[0])) && ($orderedOptions[0]["totalNumber"] != 0)) {
			if ( (isset($orderedOptions[1])) && ($orderedOptions[1] != $orderedOptions[0])) {
				return $orderedOptions[0];
			} else {
				return $orderedOptions[0];
			}
		}
	}

	function isOpen () {
		$voteFinishEpoc = strtotime($this->finish);
		return (time() < $voteFinishEpoc );
	}
	
	function getUrl () {
		return "<span id=\"voteLink\"><a href=vote.php?vi=" . $this->id . "> " . $this->subject . "</a></span>";
	}

	function removeBallot ($memberId) {
		global $db;
		if ($this->isOpen()) {
			$query = "delete from vote_ballot where member_id = $memberId and vote_id = " . $this->id;
			$db->query($query);
			$query = "delete from vote_ballot where movil_id = $memberId and vote_id = " . $this->id;
			$db->query($query);
		}
	}
}
?>