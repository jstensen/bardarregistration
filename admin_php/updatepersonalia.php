<html>
<body>
<?php
require_once("../backend/config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();
	
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$name = mysqli_real_escape_string($con, $_POST['name']);
	$address = mysqli_real_escape_string($con, $_POST['address']);
	$eMail = mysqli_real_escape_string($con, $_POST['eMail']);
	$phone = mysqli_real_escape_string($con, $_POST['phone']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);
	$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateOfBirth']);
	
	$query = "update Person set name='".$name."', address='" . $address . "', eMail='" . $eMail . "', phone='" . $phone . "', gender='" . $gender . "', dateOfBirth='" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "'
				where id=".$id;
	if(mysqli_query($con,$query)){
		echo "Personalia updated.<br />";
	}else exit("Problem updating personalia.<br />".mysqli_error($con)."<br />".$query);
}
mysqli_close($con);
?>
</body>
</html>