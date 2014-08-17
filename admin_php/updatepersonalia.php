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
	$eMail = mysqli_real_escape_string($con, $_POST['email']);
	//$formerMember = mysqli_real_escape_string($con, $_POST['isFormerMember']);
	$address = mysqli_real_escape_string($con, $_POST['address']);
	$postalNumber = mysqli_real_escape_string($con, $_POST['postalNumber']);
	$town = mysqli_real_escape_string($con, $_POST['town']);
	$phone = mysqli_real_escape_string($con, $_POST['phonenumber']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);
	$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateofbirth']);
	
	//formerMember='".$formerMember."', 
	$query = "update ".$dbprefix."Person set name='".$name."', address='" . $address . "', eMail='" . $eMail . "', phone='" . $phone . "', gender='" . $gender . "', dateOfBirth='" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "', postalNumber='" . $postalNumber . "', town='" . $town . "'
				where id=".$id;
	if(mysqli_query($con,$query)){
		echo "Personalia updated.<br />";
	}else exit("Problem updating personalia.<br />".mysqli_error($con)."<br />".$query);
	
	if($_POST['submit']=="Oppdater personopplysninger og kurs"){
		$courses = $_POST['course'];
		$priorities = $_POST['priority'];
		$partners = $_POST['partner'];
		$registrationIds = $_POST['registrationId'];
		echo "Det er ikke mulig å endre kurs ennå, men Erik jobber kanskje med saken";
		//Idé: 
	}
}
mysqli_close($con);
?>
</body>
</html>