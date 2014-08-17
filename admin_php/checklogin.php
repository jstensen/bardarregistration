<?php
require_once("../backend/config.php");
//Connect to database
$con=connectToDb();	

// username and password sent from form 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysqli_real_escape_string($con,$myusername);
$mypassword = mysqli_real_escape_string($con,$mypassword);
$sql="SELECT * FROM ".$dbprefix."Member WHERE username='".$myusername."' and password='".$mypassword."'";
$result=mysqli_query($con, $sql);

// Mysql_num_row is counting table row
$count=mysqli_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_start();
	$_SESSION['username'] = $myusername;
	$_SESSION['mypassword'];
	header("location:login_success.php");
}
else {
	echo "Wrong Username or Password";
}
?>