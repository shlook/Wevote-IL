<?
session_start(); 
include_once("db.php");
class Login
{
	function Login()
	{

	}
	function doLogin($facebookUser) {
		global $db;
		$sql = "select * from member where fb_uid = " . $facebookUser['id'];
		$facebookUsermberResult = $db->query($sql);
		if ($rec = $db->fetchNextObject($facebookUsermberResult)) {
			$_SESSION['member_id'] = $rec->member_id;
			if ($rec->email != $facebookUser['email']) {
				$sql = sprintf("update member set email = '%s' where member_id = %d", 
								mysql_real_escape_string($facebookUser['email']),
								mysql_real_escape_string($rec->member_id)
							   );
				$db->query($sql);
			}
		} else {
			
			$sql = "insert into member(name, fb_uid, is_movil, email) values ('" . $facebookUser['name'] . "', " . $facebookUser['id'] . ", 0,'" . $facebookUser['email'] . "')";
			$db->query($sql);
			$_SESSION['member_id'] = mysql_insert_id();
		}
	}
	
	function doLogout() {
			unset ($_SESSION['member_id']);
	}
		
	function isLogged() {
		if(isset($_SESSION['member_id'])) {
			return true;
		} else {
			return false;
		}
	}
}
$login = new Login();
?>