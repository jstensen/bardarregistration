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
		$firstName = mysqli_real_escape_string($con, $data['firstname']);
		$surname = mysqli_real_escape_string($con, $data['surname']);
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
			$formerMember=0;
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
			$formerMember=0;
		}
		$courses = $_POST['courses'];
	}
	$existingPerson = mysqli_query($con,'SELECT id FROM '.$dbprefix.'Person where eMail="'.$eMail.'"' );
	$alreadyRegisteredCourses = array();
	if(mysqli_num_rows($existingPerson)>0){
		$query = "select courseId from ".$dbprefix."Registration r, ".$dbprefix."Person p where r.personId=p.id and p.eMail='".$eMail."'";
		if($res=mysqli_query($con,$query)){
			while($row=mysqli_fetch_array($res)){
				$alreadyRegisteredCourses[] = $row['courseId'];
			}
		}else exit("Problem with sql:<br />".mysqli_error($con)."<br />".$query);
		//http_response_code(400);
		//exit("Vi har allerede mottatt en påmelding med den e-post-adressa.");
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
		$courseId = mysqli_real_escape_string($con,$course['courseId']);
		if(in_array($courseId, $alreadyRegisteredCourses)){
			http_response_code(400);
			exit("Vi har allerede mottatt en påmelding med den e-post-adressa til minst ett av de samme kursene.");
		}
		$priority = mysqli_real_escape_string($con,$course['priority'])+count($alreadyRegisteredCourses);
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
	$result=mysqli_query($con, 'SELECT c.name courseName, r.role, partnerName, c.solo, accepted from '.$dbprefix.'Course c, '.$dbprefix.'Registration r where c.id=r.courseId and r.personId=' . $personId);
	if($result){
		$message = "Hei, ".$firstName." ".$surname."\r\n\r\nVi har registrert påmeldingen din for\r\n\t";
		while($row = mysqli_fetch_array($result)) {
			$message = $message. '- ' . $row['courseName'];
			if($row['solo']<>1){
				if(strlen($row['partnerName'])>0){
					$partnerMessage= " med ". $row['partnerName'] . ' som partner';
				}else{
					$partnerMessage = " uten partner";
				}
				if($row['accepted']==1){
					$message = $message . " (fått plass)";
				}else $message = $message . " (ikke fått plass ennå)";
				$message = $message . " som " . $row['role'].$partnerMessage;
			}
			$message=$message."\r\n\t";
		}
		$message=$message."\r\nDu har ikke fått plass på kurs ennå. Du får snart (senest om ei uke) beskjed om du får plass eller blir satt på venteliste.\r\n\r\nMed vennlig hilsen\r\nBårdar swing club";
		echo "Takk for påmeldingen!\r\n";
		if(email($eMail, "Kurspåmelding mottatt", $message)) echo "Vi har sendt deg bekrefteses-e-post på ".$eMail.":\r\n";
		echo $message;
		
	}else exit("Could not find course. ".mysqli_error($con)."<br />");
}
mysqli_close($con);
?>