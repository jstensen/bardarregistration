<?php
/// Get information about registrations to a course
/// Inputs: int course id
/// Results: for each role: number of accepted and on waiting list, and info about each person: 
require_once("../config.php");
//checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();
	
	$data=json_decode(file_get_contents('php://input'), true);
	if(isset($data['courseId'])){
		$id = $data['courseId'];
	}else{
		$id = $_POST['courseId'];
	}
	$roles=array('lead','follow');
	$registrationobjects=array();
	foreach($roles as $role){
		$registrationobject=array();
		$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where role='".$role."' and courseId=" . $id . " and accepted=TRUE");
		if($result){
			$row = mysqli_fetch_row($result);
			$registrationobject['n_accepted']=$row[0];
		}else exit("Error finding registrations: " . mysqli_error($con));
		
		$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where role='".$role."' and courseId=" . $id . " and accepted=FALSE");
		if($result){
			$row = mysqli_fetch_row($result);
			$registrationobject['n_waiting'] = $row[0];
		}else exit("Error finding registrations: " . mysqli_error($con));
		
		$result=mysqli_query($con,"Select p.name personName, registrationTime, priority, accepted, partnerName, r.id registrationId, c.name courseName from ".$dbprefix."Registration r, ".$dbprefix."Person p, ".$dbprefix."Course c where personId=p.id and role='".$role."' and courseId=" . $id . " and c.id=courseId order by accepted desc, priority, registrationTime");
		if($result){
			$registrations=array();
			while($row = mysqli_fetch_array($result)) {
				$registration=array();
				foreach($row as $key=>$element){
					$registration[$key]=mb_convert_encoding($element, 'UTF-8');
				}
				$registrations[]=$registration;
			}
			$registrationobject['registrations']=$registrations;
			$registrationobjects[$role]=$registrationobject;
		}else exit("Error reading registrations: " . mysqli_error($con));
	}
	echo json_encode($registrationobjects);
	mysqli_close($con);
}
?>