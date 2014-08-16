<?php
/// Adding new or editing existing course
/// Inputs: string name, string description, int capacity, int maxUnbalance, int id
/// To add new course, id must be 0. To edit existing course, id = id of course to edit
require_once("../config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data';
}else{
	//$filename="test.txt";
	//$stringenmin="";
	//foreach (json_decode(file_get_contents('php://input')) as $key => $value){
	//	$stringenmin=stringenmin.$key;
	//}
	//$data=json_decode(file_get_contents('php://input'));
	//$stringenmin=$data->name.$data->address.$data->data.date('Y-m-d H:i:s').$POST['data'];
	//file_put_contents($filename , $stringenmin);
	
	//Connect to database
	$con=connectToDb();
	
	//Read POST data (either from js/jason, or from normal php)
	$data=json_decode(file_get_contents('php://input'));
	if(isset($data->name)){
		$name = mysqli_real_escape_string($con, $data['name']);
		$description = mysqli_real_escape_string($con, $data['description']);
		$capacity = $data['capacity'];
		$maxUnbalance = $data['maxUnbalance'];
		$status = mysqli_real_escape_string($con, $data['status']);
		if(mysqli_real_escape_string($con, $data['solo'])=="TRUE") $solo = "TRUE"; else $solo="FALSE";
		$id = $data['courseId'];
	}else{
		$name = mysqli_real_escape_string($con, $_POST['name']);
		$description = mysqli_real_escape_string($con, $_POST['description']);
		$capacity = $_POST['capacity'];
		$maxUnbalance = $_POST['maxUnbalance'];
		$status = mysqli_real_escape_string($con, $_POST['status']);
		//$solo = mysqli_real_escape_string($con, $_POST['solo']);
		if(isset($_POST['solo']) && mysqli_real_escape_string($con, $_POST['solo'])==1) $solo = 1; else $solo=0;
		$id = $_POST['courseId'];
	}
	
	if($id==0){//Add new course
		if(mysqli_query($con,"INSERT INTO ".$dbprefix."Course (name, description, capacity, maxUnbalance, status, solo)
		VALUES ('" . $name . "', '" . $description . "', " . $capacity . ", " . $maxUnbalance . ", '" . $status . "', " . $solo . ")")){
			echo $name . " added to course database.";
		}else{
			echo "Error adding " . $name . " to database: " . mysqli_error($con);
		}
	}else{ //Edit existing course
		if(mysqli_query($con,"Update ".$dbprefix."Course
		set name='" . $name . "', description='" . $description . "', capacity =" . $capacity . ", maxUnbalance= " . $maxUnbalance . ", status='" . $status . "', solo=" . $solo . "
		where id=" . $id)){
			echo $name . " updated.";
		}else exit("Error updating " . $name . ": " . mysqli_error($con));
	}
	//echo '<a href="managecourses.php">Manage courses</a>';
	
	mysqli_close($con);
}
?>