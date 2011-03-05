<?php include("../db.php"); ?>
<?php
// Login & Session example by sde 
// auth.php 

// start session 
session_start();  

// convert username and password from _POST or _SESSION 
$username="";
$password="";
if($_POST){ 
  $username=$_POST["username"]; 
  $password=$_POST["password"];   
} 

// query for a user/pass match 
$result=mysql_query("select * from admin  
  where username='" . $username . "' and password='" . $password . "'"); 
//echo "select * from admin  
//  where username='" . $username . "' and password='" . $password . "'";
// retrieve number of rows resulted 
$num=mysql_num_rows($result);  

// print login form and exit if failed. 

if($num < 1){ 
  echo "<TABLE align=center>
  <TR>
	<TD><br><br><br><br><br><br><br><br>You are not authenticated.  Please login.<br><br>    
  <form method=POST action=login.php> 
  username: <input type=text name=\"username\" value='".$username."'><br> 
  password: <input type=password name=\"password\"> <br>
  <input type=submit value=LOGIN> 
  </form></TD>
  </TR>
  </TABLE>"; 
   
  exit; 
} 
else
{
	$_SESSION['islogged'] = 1;
	 header("Location:voteManager.php");
}
?> 