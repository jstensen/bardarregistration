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
	
	//Read POST data (either from js/jason, or from normal php)
	$data=json_decode(file_get_contents('php://input'));
	if(isset($data->courseId)){
		$personId = $data['personId'];
	}else{
		$personId=$_POST['personId'];
	}
	
	$personalia=mysqli_fetch_array(mysqli_query($con,"select name, address, eMail, phone, gender, dateOfBirth from ".$dbprefix."Person where id=".$personId));
	
	if(isset($data->courseId)){
		echo json_encode($personalia);
	}else{
		$name = $personalia['name'];
		$address = $personalia['address'];
		$eMail = $personalia['eMail'];
		$phone = $personalia['phone'];
		$gender = 
		$gender=gender($personalia['gender']);
			
		$checked = array_fill_keys(array('male', 'female'), '');
		$checked[$gender] = ' checked="checked"';
		$dateOfBirth = $personalia['dateOfBirth'];
		
		
		echo '<form action="updatepersonalia.php" method="post">';
		echo 'Name: <input type="text" name="name" value="'.$name.'" required><br>';
		echo 'Address: <input type="text" name="address" value="'.$address.'" required><br>';
		echo 'E-mail address: <input type="text" name="eMail" value="'.$eMail.'" required><br>';
		echo 'Phone number: <input type="text" name="phone" value="'.$phone.'"><br>';
		echo 'Gender: <input type="radio" name="gender" value="male"'.$checked['male'].'>Male | <input type="radio" name="gender" value="female"'.$checked['female'].'>Female<br>';
		echo 'Date of birth: <input type="date" name="dateOfBirth" value="'.$dateOfBirth.'" required><br>';
		echo '<input type="hidden" name="id" value="'.$personId.'">';
		echo '<input type="submit" value="Update">';
		echo '</form>';
	}
	
	mysqli_close($con);
}
?>