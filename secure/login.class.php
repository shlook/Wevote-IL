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
		} else {
			$sql = "insert into member(name, fb_uid, is_movil) values ('" . $facebookUser['name'] . "', " . $facebookUser['id'] . ", 0)";
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