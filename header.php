<?php 

date_default_timezone_set("Asia/Jerusalem");
if ($currentEnv == "dev") {
	error_reporting(E_ALL);
}

include_once("secure/login.class.php");
include_once("member.class.php");
include_once 'facebook/src/facebook.php';
include_once "fbmain.php";


# Set Facebook properties
$config["prod"]['baseurl']  =   getCurrentPageURL();
$config["dev"]['baseurl']  =   getCurrentPageURL();

if ($me) {
  $logoutUrl = $facebook->getLogoutUrl(
	array(
		'next'      => $config[$currentEnv]['baseurl'],
	)
  );
} else {
  $loginUrl = $facebook->getLoginUrl(
	array(
		'display'   => 'popup',
		'next'      => addUrlParam($config[$currentEnv]['baseurl'], 'loginsucc=1'),
		'cancel_url'=> addUrlParam($config[$currentEnv]['baseurl'], 'fbcancel=1'),
		'req_perms' => 'email,user_birthday',
	)
  );
}
 
// if user click cancel in the popup window
if (isset($_REQUEST['fbcancel'])){
	echo "<script>
		window.close();
		</script>";
}

if ($me && isset($_REQUEST['loginsucc'])){
	//only if valid session found and loginsucc is set

	//after facebook redirects it will send a session parameter as a json value
	//now decode them, make them array and sort based on keys
	$sortArray = get_object_vars(json_decode($_GET['session']));
	ksort($sortArray);

	$strCookie  =   "";
	$flag       =   false;
	foreach($sortArray as $key=>$item){
		if ($flag) $strCookie .= '&';
		$strCookie .= $key . '=' . $item;
		$flag = true;
	}

	//now set the cookie so that next time user don't need to click login again
	setCookie('fbs_' . "{$fbconfig[$currentEnv]['appid']}", $strCookie);

	echo "<script>
		window.close();
		window.opener.location.reload();
		</script>";
}	
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
  <title><?=$TITLE_STR?></title>  
  <link rel="stylesheet" type="text/css" href="style/main.css">
  <link REL="SHORTCUT ICON" HREF="images/miflagaicon.bmp">
 </head>
 <body>
    <script type="text/javascript">
        <?php
		if (isset($loginUrl) && ($loginUrl)) { 
		?>
			var newwindow;
			var intId;
			function login(){
				var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
					 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
					 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
					 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
					 width    = 500,
					 height   = 270,
					 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
					 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
					 features = (
						'width=' + width +
						',height=' + height +
						',left=' + left +
						',top=' + top
					  );
	 
				newwindow=window.open('<?=$loginUrl?>','Login_by_facebook',features);
	 
				 if (window.focus) {newwindow.focus()}
				return false;
			}
 
		<?php
		} 
		?>
    </script>
	 <div id="content">
		 <div id="navBar">
		 <div id="menubar">
			<a href="about.php"><img src="images/logo.png" align="center" border="0">אודות</a>				
			<a href="main.php"><img src="images/main.png" width="45" align="center" border="0">ראשי</a>
			<a href="votes.php"><img src="images/ballot.jpg" width="45" align="center" border="0">הצבעות</a>
			<a href="members.php"><img src="images/member.png" width="45" align="center" border="0">חברי המפלגה</a>
			<?php if ($me) {
			?>
				<a href="mypage.php"><img src="images/user.png" width="45" align="center" border="0">העמוד שלי</a>
			<?php
			}
			?>
		</div>
		<div id="login">
			<?php
			if ($me) { 
			?>
				<div id="LoginStatus">מחובר כ-<b><?=$me['name']?></b></div>
				<a href="<?=$logoutUrl?>">
					<img src="images/fb_disconnect_heb.png" border="0">
				</a>
			<?php 
			} else { 
			?>
				<div id="LoginStatus">התחבר באמצעות פייסבוק כדי להשתתף</div>
				<a href="#" onclick="login();return false;">
					<img src="images/fb_connect_heb.png" border="0">
				</a>
			<?php 
			} 
			?>


			<?php
			if ($me) { 
				$login->doLogin($me);


			?>
			<?php
			} else {
				$login->doLogout();
			}
			?>
		</div>
		</div>
