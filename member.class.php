<?
class Member
{
	var $name;  
	var $id;
	function Member($memberId, $loadData = true) {		
		$this->id = $memberId;
		if($loadData)
			$this->startMemberData($memberId);	

	}
	
	function startMemberData($memberId) {
		global $db;
		$query = "SELECT * from member where member_id = $memberId";
		$result = $db->query($query);
		
		if ($line = $db->fetchNextObject($result))
		{									
			$this->name = $line->name;
			$this->isMovil = $line->is_movil;
			$this->fbUid = $line->fb_uid;
			$this->aboutMe = $line->about_me;
			$this->isAdmin = $line->is_admin;
		}
		else
			die("Not Found!"); 
	}
	
	function getOptionIdVoted($voteId) {
		global $db;
		$query = "SELECT vote_option_id, movil_id from vote_ballot
					 WHERE vote_id =" . $voteId . " and member_id = " . $this->id;
		$result = $db->query($query);
		
		if ($line = $db->fetchNextObject($result))									
			return array($line->vote_option_id, $line->movil_id);						
		else		
			return array(0, 0);
		
	}
	
	function setAsMovil()	{
		global $db;
		$query = "update member set is_movil = 1 where member_id = " . $this->id;
		$db->query($query);
	}
	
	function unsetAsMovil()	{
		global $db;
		$query = "update member set is_movil = 0 where member_id = " . $this->id;
		$db->query($query);
		$query = "delete from movil_muval where member_id_movil = " . $this->id;
		$db->query($query);
		foreach (Vote::getAllOpenVotes() as $vote) {
			$query = "delete from vote_ballot where movil_id = " . $this->id . " and vote_id = " . $vote->id;
			$db->query($query);
		}
	}
	
	function chooseMovil($memberId) {
		global $db;
		$query = "insert into movil_muval (member_id_movil, member_id_muval) values ($memberId, " . $this->id . ")";
		$db->query($query);
		foreach (Vote::getAllOpenVotes() as $vote) {
			$isMemberVoted = $this->getOptionIdVoted($vote->id);
			if (!($isMemberVoted[0])) {
				$query = "INSERT INTO vote_ballot (member_id, vote_id, vote_option_id, movil_id) 
						  SELECT " . $this->id . ", vote_id, vote_option_id, $memberId from vote_ballot
						  WHERE member_id = $memberId and vote_id = $vote->id";
				$db->query($query);
			}
		}
	}

	function unChooseMovil() {
		global $db;
		$movil = $this->getMovil();
		foreach (Vote::getAllOpenVotes() as $vote) {
			$query = "delete from vote_ballot where movil_id = " . $movil->id . " and member_id = " . $this->id . " and vote_id = " . $vote->id;
	        $db->query($query);
		}
		$query = "delete from movil_muval where member_id_muval = " . $this->id ;
		$db->query($query);
		
	}	
	
	function getMovil() {
		global $db;
		$query = "select member_id_movil from movil_muval where member_id_muval = " . $this->id;
		$result = $db->query($query);
		if ($rec = $db->fetchNextObject($result)) {
			return new Member($rec->member_id_movil);
		} else {
			return 0;
		}
	}

	function getUrl() {
		return "<a id=\"member\" href=member.php?mi=" . $this->id . ">" . $this->name . "</a>";
	}

	function getMuvalsForVote($voteId) {
		global $db;
		$sql = "select member_id_muval from movil_muval where 
		        member_id_movil = " . $this->id . " and
				member_id_muval not in 
				(select member_id from vote_ballot vb where vote_id = $voteId and movil_id is null)";
		#print $sql;
		$muvals = array ();
	
		$muvalsResult =  $db->query($sql);
		while($rec = $db->fetchNextObject($muvalsResult)) {
			array_push($muvals, $rec->member_id_muval);
		}
		return $muvals;
	}
	
	function getMuvalsAsMemberObj() {
		global $db;
		$sql = "select member_id_muval from movil_muval where 
				member_id_movil = " . $this->id ;
		#print $sql;
		$muvals = array ();
		$muvalsResult =  $db->query($sql);
		while($rec = $db->fetchNextObject($muvalsResult)) {
			array_push($muvals, new Member($rec->member_id_muval));
		}
		return $muvals;
	}
}
?>