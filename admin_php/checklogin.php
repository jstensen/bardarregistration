<?php
// Connect to database
$con=mysqli_connect("localhost","root","","Registrations");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br />";
}

// username and password sent from form 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);
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