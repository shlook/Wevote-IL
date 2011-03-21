<?
define("PNUM", 1);
define("PSTR", 2); 
define("V_OK", 1); 
define("V_FAILED", 2); 
$ONLOAD_STR = "";
$JS_STR = "";
$currentVersion = "0.12";
$currentEnv = "prod";
function getCurrentPageURL() {
 $pageURL = 'http';
 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function addUrlParam($url, $param) {

	if (strrpos($url, '?')) {
		return $url . '&' . $param;
	} else {
		return $url . '?' . $param;
	}

}


function getXmlFromSQL($query,$name, $extraNodes = '')
{
	global $db;
	//echo $query;
	$result = $db->query($query);

	$xml = "<?xml version=\"1.0\"?>"; 
	$xml.= "<data>";
	$xml.= $extraNodes;
	$xml.= "<count>".mysql_num_rows($result)."</count>";
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		$xml.= "<$name>";
		$i=0;
		while($i < mysql_num_fields($result)) 
		{
			$meta = mysql_fetch_field($result, $i);			
			$xml.= "<".$meta->name."><![CDATA[";
			$xml.= $row[$meta->name];
			$xml.= "]]></".$meta->name.">";
			$i++;
		}
		$xml.= "</$name>";
	}
	$xml.= "</data>";
	//$xml = substr($xml, 0, -1);
	return $xml;
}
function gp($name, $type = PNUM, $isPost = false, $default = 0) // GET PARAMERTERS FROM GET OR POST (gp for convenient)
{	
	if($isPost)
	{
		if(isset($_POST[$name]))
			$val = $_POST[$name];
		else
			return $default;
	}
	else
	{
		if(isset($_GET[$name]))
			$val = $_GET[$name];
		else
			return $default;
	}
	switch($type)
	{
		case PNUM:
			if(is_numeric($val))
				return $val;
			else
			{
				//write to log
			}
			break;
		case PSTR:
			return mysql_real_escape_string($val);
			break;			
	}	
	return $default;	
}

function getHumanDateDiff ($dateParam) {
	$dateDiff = $dateParam - time();
	$fullDays = floor($dateDiff/(60*60*24));
	switch ($fullDays) {
		case 0:
			$humanStr = "היום";
			break;
		case 1:
			$humanStr = "מחר";
			break;
		case 2:
			$humanStr = "מחרתיים";
			break;
		case -1:
			$humanStr = "אתמול";
			break;
		case -2:
			$humanStr = "שלשום";
			break;
		default:
			$humanStr = "ב-" . date("j/n/y", $dateParam);
	}
	return $humanStr;
		
}

function addJsTag($url)
{
	global $JS_STR;
	$JS_STR.="<script language=\"javascript\" src=\"$url\"></script>";
}?>