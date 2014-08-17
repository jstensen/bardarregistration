<?php
/// Get personalia for person specified by id
/// Inputs: int personId
/// Returns: string name, string address, string eMail, string phone, string gender, string dateOfBirth
require_once("../backend/config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();
	
	//Read POST data (either from js/json, or from normal php)
	$data=json_decode(file_get_contents('php://input'));
	if(isset($data->courseId)){
		$personId = $data['personId'];
	}else{
		$personId=$_POST['personId'];
	}
	
	$personalia=mysqli_fetch_array(mysqli_query($con,"select name, eMail, address, postalNumber, town, phone, gender, dateOfBirth from ".$dbprefix."Person where id=".$personId));
	echo '<h1>Edit personalia</h1>';
	
	if(isset($data->courseId)){
		echo json_encode($personalia);
	}else{
		$name = $personalia['name'];
		$eMail = $personalia['eMail'];
		$address = $personalia['address'];
		$postalNumber = $personalia['postalNumber'];
		$town = $personalia['town'];
		$phone = $personalia['phone'];
		$gender=gender($personalia['gender']);
			
		$checked = array_fill_keys(array('male', 'female'), '');
		$checked[$gender] = ' checked="checked"';
		$dateOfBirth = $personalia['dateOfBirth'];

		echo '<form action="updatepersonalia.php" method="post">';
		echo 'Navn:<br><input type="text" name="name" value="'.$name.'" required><br>';
		echo 'E-post-adresse:<br><input type="text" name="email" value="'.$eMail.'" required><br>';
		echo 'Addresse:<br><input type="text" name="address" value="'.$address.'"><br>';
		echo 'Postnummer:<br><input type="text" name="postalNumber" value="'.$postalNumber.'" ><br>';
		echo 'Sted:<br><input type="text" name="town" value="'.$town.'" ><br>';
		echo 'Telefonnummer:<br><input type="text" name="phonenumber" value="'.$phone.'"><br>';
		echo 'Kjønn:<br><input type="radio" name="gender" value="male"'.$checked['male'].'>Mann | <input type="radio" name="gender" value="female"'.$checked['female'].'>Kvinne<br>';
		echo 'Fødselsdato:<br><input type="date" name="dateofbirth" value="'.$dateOfBirth.'"><br>';
		echo '<input type="hidden" name="id" value="'.$personId.'">';
		echo '<input type="submit" name="submit" value="Oppdater personopplysninger">';
		
		$courses = mysqli_query($con,"SELECT id, name, status, description FROM ".$dbprefix."Course order by name");
		$ii=0;
		$courseList=array();
		while($course = mysqli_fetch_array($courses)){
			$courseList[$ii]=$course;
			$ii++;
		}
		$result = mysqli_query($con,"select r.id registrationId, priority, partnerName, courseId, accepted from ".$dbprefix."Registration r, ".$dbprefix."Course c where c.id=r.courseId and personId=".$personId);
		$ii=0;
		$registrations=array();
		while($row = mysqli_fetch_array($result)){
			$registrations[$ii]=$row;
			$ii++;
		}
		for(;$ii<$maxNumberOfCourses;$ii++){
			$registrations[$ii]=array("registrationId" => "", "priority" => $ii, "partnerName" => "", "courseId" => -1);
		}
		foreach($registrations as $ii => $row){
			//echo '<form action="updateregistration.php" method="post">';
			echo '<select name="course['.$ii.']">';
			foreach($courseList as $course){
				echo '<option value="0">Ikke noe kurs</option>';
				if($course['id']==$row['courseId']){
					echo '<option value="' . $course['id'] . '" selected>' . $course['name'] . '</option>';
				}elseif($course['status']<>'Closed'){
					echo '<option value="' . $course['id'] . '">' . $course['name'] . '</option>';
				}
			}
			echo '</select><br>';
			echo 'Prioritet:<br><input type="text" name="priority['.$ii.']" value="'.$row['priority'].'" required><br>';
			echo 'Partner:<br><input type="text" name="partner['.$ii.']" value="'.$row['partnerName'].'"><br>';
			echo '<input type="hidden" name="registrationId['.$ii.']" value="'.$row['registrationId'].'">';
			//echo '<input type="hidden" name="personId['.$ii.']" value="'.$personId.'">';
			//echo '<input type="submit" name="submit" value="Update">';
		}
		echo '<input type="submit" name="submit" value="Oppdater personopplysninger og kurs">';
		
		echo '</form>';
	}
	mysqli_close($con);
}
?>