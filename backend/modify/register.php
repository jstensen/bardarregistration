<?php
/// Get information about all courses
/// Inputs: no
/// Results: int id, string firstName, surname, string description, int capacity, int maxUnbalance, status
require_once("../config.php");
//checkLogin();

//Connect to database
$con=connectToDb();
$maxNumberOfCourses=3;
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo "Missing data";
}else{
	$data=json_decode(file_get_contents('php://input'), true);
	if(true){//isset($data['firstname'])){
		$firstName = mysqli_real_escape_string($con, $data['surname']);
		$surname = mysqli_real_escape_string($con, $data['firstname']);
		$eMail = mysqli_real_escape_string($con, $data['email']);
		$formerMember = mysqli_real_escape_string($con, $data['isFormerMember']);
		if($formerMember==1){
			$address = "";
			$postalNumber = "";
			$town = "";
			$phone = "";
			$gender = "";
			$dateOfBirth = "";
		}else{
			$address = mysqli_real_escape_string($con, $data['address']);
			$postalNumber = mysqli_real_escape_string($con, $data['postalNumber']);
			$town = mysqli_real_escape_string($con, $data['town']);
			$phone = mysqli_real_escape_string($con, $data['phonenumber']);
			$gender = mysqli_real_escape_string($con, $data['gender']);
			$dateOfBirth = mysqli_real_escape_string($con, $data['dateofbirth']);
		}
		$courses = $data['courses'];
		
	}else{
		$firstName = mysqli_real_escape_string($con, $_POST['firstname']);
		$surname = mysqli_real_escape_string($con, $_POST['surname']);
		$eMail = mysqli_real_escape_string($con, $_POST['email']);
		$formerMember = mysqli_real_escape_string($con, $_POST['isFormerMember']);
		if($formerMember==1){
			$address = "";
			$postalNumber = "";
			$town = "";
			$phone = "";
			$gender = "";
			$dateOfBirth = "";
		}else{
			$address = mysqli_real_escape_string($con, $_POST['address']);
			$postalNumber = mysqli_real_escape_string($con, $_POST['postalNumber']);
			$town = mysqli_real_escape_string($con, $_POST['town']);
			$phone = mysqli_real_escape_string($con, $_POST['phonenumber']);
			$gender = mysqli_real_escape_string($con, $_POST['gender']);
			$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateofbirth']);
		}
		$courses = $_POST['courses'];
	}
	$existingPerson = mysqli_query($con,'SELECT id FROM '.$dbprefix.'Person where eMail="'.$eMail.'"' );
	if(mysqli_num_rows($existingPerson)>0){
		http_response_code(400);
		exit("Vi har allerede mottatt en påmelding med den e-post-adressa. Ta kontakt på bardarswingclub alfakrøll gmail dått com om du trenger hjelp.");
	}else{
		$query = "INSERT INTO ".$dbprefix."Person (firstName, surname, address, eMail, phone, gender, dateOfBirth, formerMember, postalNumber, town)
VALUES ('" . $firstName . "', '" . $surname . "', '" . $address . "', '" . $eMail . "', '" . $phone . "', '" . $gender . "', '" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "', ".$formerMember.", '".$postalNumber."', '".$town."')";
		if(mysqli_query($con,$query)){
			//echo "Person registered <br />";
		}else exit("Problem adding person.<br />".mysqli_error($con)."<br />".$query);
	}
	
	$person = mysqli_fetch_array(mysqli_query($con,'SELECT id FROM '.$dbprefix.'Person where eMail like "'.$eMail.'"' ));
	$personId = $person['id'];
	
	foreach($courses as $course){
		$priority = mysqli_real_escape_string($con,$course['priority']);
		$courseId = mysqli_real_escape_string($con,$course['courseId']);
		$role = mysqli_real_escape_string($con,$course['role']);
		if(mysqli_real_escape_string($con,$course['hasPartner'])){
			$partnerName = mysqli_real_escape_string($con,$course['partnerName']);
		}else{
			$partnerName = "";
		}
		if($courseId<=0){
			break;
		}
		$course = mysqli_fetch_array(mysqli_query($con,'SELECT name from '.$dbprefix.'Course where id='.$courseId));
		
		$query = "INSERT INTO ".$dbprefix."Registration (personId, courseId, registrationTime, priority, role, partnerName, accepted)
	VALUES (" . $personId . ", " . $courseId . ", '" . date('Y-m-d H:i:s') . "', " . $priority . ", '" . $role . "', '" . $partnerName . "', " . 'FALSE' . ")";
		if(!mysqli_query($con, $query)) exit("Error with course registration. ".mysqli_error($con)."<br />".$query."<br />");
		
	}
	$result=mysqli_query($con, 'SELECT c.name courseName, r.role, partnerName, c.solo from '.$dbprefix.'Course c, '.$dbprefix.'Registration r where c.id=r.courseId and r.personId=' . $personId);
	if($result){
		$message = "Påmelding mottatt for\r\n\t";
		while($row = mysqli_fetch_array($result)) {
			$message = $message. '- ' . $row['courseName'];
			if($row['solo']<>1){
				if(strlen($row['partnerName'])>0){
					$partnerMessage= " med ". $row['partnerName'] . ' som partner';
				}else{
					$partnerMessage = " uten partner";
				}
				$message = $message . " som " . $row['role'].$partnerMessage;
			}
			$message=$message."\r\n\t";
		}
		echo "Takk for påmeldingen!\r\n";
		if(email($eMail, "Kurspåmelding mottatt", $message)) echo "Vi har sendt deg bekrefteses-e-post på ".$eMail.":\r\n";
		echo $message;
		
	}else exit("Could not find course. ".mysqli_error($con)."<br />");
}
mysqli_close($con);
?>