<html>
<body>

<?php
/// Admit selected persons to course, and send confirmation e-mails
/// Inputs: int courseId, int registrationId[]
/// 
require_once("../backend/config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();	
	
	$action =$_POST['submit'];
	
	$registrationIds = $_POST['registrationIds'];
	$id = $_POST['courseId'];
	if($action=="Delete and send e-mail"||$action=="Delete"){
		foreach($registrationIds as $registrationId){
			mysqli_query($con,"delete from ".$dbprefix."Registration where id=".$registrationId);
		}
		echo "Registrations deleted<br>";
		if($action=="Delete and send e-mail"){
			foreach($registrationIds as $registrationId){
				$row=mysqli_fetch_array(mysqli_query($con,"Select p.name personName, eMail, role, c.name courseName from ".$dbprefix."Registration r, person p, course c where personId=p.id and courseId=" . $id . " and c.id=courseId and r.id=".$registrationId));
				$receiver=$row['eMail'];
				$adaptedmessage = str_replace(array('*navn*','*rolle*'),array($row['personName'],$row['role']),$_POST['deletemessage']);
				email($receiver,"Påmelding slettet",$adaptedmessage);
				echo "E-mail sent to ".$receiver.':<br />'.$adaptedmessage.'<br />';
			}
		}
	}elseif($action=="Remove acceptance and send e-mail"||$action=="Remove acceptance"){
		foreach($registrationIds as $registrationId){
			mysqli_query($con,"update ".$dbprefix."Registration set accepted=FALSE, priority = priority+10 where id=".$registrationId." and accepted=TRUE");
		}
		echo "Status changed<br />";
		if($action=="Remove acceptance and send e-mail"){
			foreach($registrationIds as $registrationId){
				$row=mysqli_fetch_array(mysqli_query($con,"Select p.name personName, eMail, role, c.name courseName from ".$dbprefix."Registration r, ".$dbprefix."Person p, ".$dbprefix."Course c where personId=p.id and courseId=" . $id . " and c.id=courseId and r.id=".$registrationId));
				$receiver=$row['eMail'];
				$adaptedmessage = str_replace(array('*navn*','*rolle*'),array($row['personName'],$row['role']),$_POST['removemessage']);
				email($receiver,"Du har mistet plassen på et kurs",$adaptedmessage);
				echo "E-mail sent to ".$receiver.':<br />'.$adaptedmessage.'<br />';
			}
		}
	}else{	
		//Read POST data, either from js/json or from normal PHP
		$data=json_decode(file_get_contents('php://input'));
		if(isset($data->name)){
			$id=$data['id'];
			$message=$data['message'];
			$registrationIds=$_POST['registrationIds'];
		}else{
			$id = $_POST['courseId'];
			$message = $_POST['message'];
			$registrationIds=$_POST['registrationIds'];
		}
		foreach($registrationIds as $registrationId){
			mysqli_query($con,"update ".$dbprefix."Registration set accepted=TRUE where id=".$registrationId);
			$row=mysqli_fetch_array(mysqli_query($con,"Select p.name personName, eMail, role, c.name courseName from ".$dbprefix."Registration r, ".$dbprefix."Person p, ".$dbprefix."Course c where personId=p.id and courseId=" . $id . " and c.id=courseId and r.id=".$registrationId));
			$receiver=$row['eMail'];
			$adaptedmessage = str_replace(array('*navn*','*rolle*'),array($row['personName'],$row['role']),$message);
			email($receiver,"Du har fått plass på kurs!",$adaptedmessage);
			echo "E-mail sent to ".$receiver.':<br />'.$adaptedmessage.'<br />';
		}
	}
	echo '<form action ="manageregistrations.php" method="post"><input type="hidden" name="courseId" value ="'.$id.'"><input type="submit" value = "Back to registrations"></form>';
	mysqli_close($con);
}
?>
</body>
</html>