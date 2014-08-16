<?php
/// Get information about all courses
/// Inputs: no
/// Results: int id, string name, string description, int capacity, int maxUnbalance, status
require_once("../config.php");
//checkLogin();

//Connect to database
$con=connectToDb();
$maxNumberOfCourses=3;
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo "Missing data";
}else{
	$data=json_decode(file_get_contents('php://input'), true);
	if(true){//isset($data['name'])){
		$name = mysqli_real_escape_string($con, $data['name']);
		$address = mysqli_real_escape_string($con, $data['address']);
		$eMail = mysqli_real_escape_string($con, $data['email']);
		$phone = mysqli_real_escape_string($con, $data['phonenumber']);
		$gender = mysqli_real_escape_string($con, $data['gender']);
		$dateOfBirth = mysqli_real_escape_string($con, $data['dateofbirth']);
		$courses = $data['courses'];
	}else{
		$name = mysqli_real_escape_string($con, $_POST['name']);
		$address = mysqli_real_escape_string($con, $_POST['address']);
		$eMail = mysqli_real_escape_string($con, $_POST['eMail']);
		$phone = mysqli_real_escape_string($con, $_POST['phone']);
		$gender = gender(mysqli_real_escape_string($con, $_POST['gender']));
		$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateOfBirth']);
		$courseIdArray = $_POST['course'];
		$roleArray = $_POST['role'];
		$partnerIdArray = $_POST['partnerId'];
	}
	$existingPerson = mysqli_query($con,'SELECT id FROM Person where eMail="'.$eMail.'"' );
	if(mysqli_num_rows($existingPerson)>0){
		echo exit("Error: Person with that e-mail address already registered. Contact us to change your registration.<br />");
	}else{
		$query = "INSERT INTO Person (name, address, eMail, phone, gender, dateOfBirth)
VALUES ('" . $name . "', '" . $address . "', '" . $eMail . "', '" . $phone . "', '" . $gender . "', '" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "')";
		if(mysqli_query($con,$query)){
			echo "Person registered.<br />";
		}else exit("Problem adding person.<br />".mysqli_error($con)."<br />".$query);
	}
	
	$person = mysqli_fetch_array(mysqli_query($con,'SELECT id FROM Person where eMail like "'.$eMail.'"' ));
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
		$course = mysqli_fetch_array(mysqli_query($con,'SELECT name from Course where id='.$courseId));
		
		$query = "INSERT INTO Registration (personId, courseId, registrationTime, priority, role, partnerName, accepted)
	VALUES (" . $personId . ", " . $courseId . ", '" . date('Y-m-d H:i:s') . "', " . $priority . ", '" . $role . "', '" . $partnerName . "', " . 'FALSE' . ")";
		if(!mysqli_query($con, $query)) exit("Error with course registration. ".mysqli_error($con)."<br />".$query."<br />");
		
	}
	$result=mysqli_query($con, 'SELECT c.name courseName, r.role, partnerName from Course c, Registration r where c.id=r.courseId and r.personId=' . $personId);
	if($result){
		$message = 'Registration received for
	';
		while($row = mysqli_fetch_array($result)) {
			if(strlen($row['partnerName'])>0){
				$partnerMessage= " ". $row['partnerName'] . ' as partner';
			}else{
				$partnerMessage = "out partner";
			}
			$message = $message. '- ' . $row['courseName'] . " as " . $row['role']. " with" .$partnerMessage.'
	';
		}
		echo email($eMail, "Course registration received", $message);
	}else exit("Could not find course. ".mysqli_error($con)."<br />");
}
mysqli_close($con);
?>