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
	
	$personId = mysqli_real_escape_string($con, $_POST['id']);
	$firstName = mysqli_real_escape_string($con, $_POST['firstname']);
	$surname = mysqli_real_escape_string($con, $_POST['surname']);
	$eMail = mysqli_real_escape_string($con, $_POST['email']);
	//$formerMember = mysqli_real_escape_string($con, $_POST['isFormerMember']);
	$address = mysqli_real_escape_string($con, $_POST['address']);
	$postalNumber = mysqli_real_escape_string($con, $_POST['postalNumber']);
	$town = mysqli_real_escape_string($con, $_POST['town']);
	$phone = mysqli_real_escape_string($con, $_POST['phonenumber']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);
	$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateofbirth']);
	
	//formerMember='".$formerMember."', 
	$query = "update ".$dbprefix."Person set firstName='".$firstName."', surname='".$surname."', address='" . $address . "', eMail='" . $eMail . "', phone='" . $phone . "', gender='" . $gender . "', dateOfBirth='" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "', postalNumber='" . $postalNumber . "', town='" . $town . "'
				where id=".$personId;
	if(mysqli_query($con,$query)){
		echo "Personalia updated.<br />";
	}else exit("Problem updating personalia.<br />".mysqli_error($con)."<br />".$query);
	
	if($_POST['submit']=="Oppdater personopplysninger og kurs"){
		$courses = $_POST['course'];
		$priorities = $_POST['priority'];
		$partners = $_POST['partner'];
		$registrationTimes = $_POST['registrationTime'];
		$registrationIds = $_POST['registrationId'];
		$roles = $_POST['role'];
		for($ii=0; $ii<$maxNumberOfCourses; $ii++){
			if($courses[$ii]!=0){
				if($registrationIds[$ii]==-1){
					$query = "INSERT INTO ".$dbprefix."Registration (personId, courseId, registrationTime, priority, role, partnerName, accepted)
		VALUES (" . $personId . ", " . $courses[$ii] . ", '" . $registrationTimes[$ii] . "', " . $priorities[$ii] . ", '" . $roles[$ii] . "', '" . $partners[$ii] . "', 0)";
					echo "<br>Påmelding legges til<br>";
				}else{
					$query = "update ".$dbprefix."Registration set personId = " . $personId . ", courseId = " . $courses[$ii] . ", registrationTime = '" . $registrationTimes[$ii] . "', priority = " . $priorities[$ii] . ", role = '" . $roles[$ii] . "', partnerName = '" . $partners[$ii] . "'".
					" where id =" . $registrationIds[$ii];
					echo "<br>Påmelding oppdateres<br>";
				}
				if(!mysqli_query($con, $query)) exit("Error with course registration. ".mysqli_error($con)."<br />".$query."<br />");
			}
		}
	}
}
mysqli_close($con);
?>
</body>
</html>