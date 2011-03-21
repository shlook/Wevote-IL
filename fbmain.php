<?php
   
	$fbconfig["prod"]['appid' ]  = "172277482794601";
    $fbconfig["prod"]['api'   ]  = "00869a9afb5e28de86f006d4c9707c09";
    $fbconfig["prod"]['secret']  = "6ddd5057c79a497bb97ddaadaeec6155";
	
	$fbconfig["dev"]['appid' ]  = "210500612298547";
    $fbconfig["dev"]['api'   ]  = "afb3cfa03b7575e7c39b0a0e305c09bb";
    $fbconfig["dev"]['secret']  = "54597957cffd9d50d97b58e26d10e120";
	
	if ($currentEnv == "dev") {
		$isDebug = 0;
	} else {
		$isDebug = 0;
	}
   
   try{
        include_once "./facebook/src/facebook.php";
    }
    catch(Exception $e){
		d($e);
    }
    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => $fbconfig[$currentEnv]['appid'],
      'secret' => $fbconfig[$currentEnv]['secret'],
      'cookie' => true,
    ));
 
    // We may or may not have this data based on a $_GET or $_COOKIE based session.
    // If we get a session here, it means we found a correctly signed session using
    // the Application Secret only Facebook and the Application know. We dont know
    // if it is still valid until we make an API call using the session. A session
    // can become invalid if it has already expired (should not be getting the
    // session back in this case) or if the user logged out of Facebook.
    $session = $facebook->getSession();
 
    $me = null;
    // Session based graph API call.
    if ($session) {
      try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
      } catch (FacebookApiException $e) {
          d($e);
      }
    }
 
    function d($d){
		global $isDebug;
        if ($isDebug) {
			echo '<pre>';
			print_r($d);
			echo '</pre>';
		}
    }
?>