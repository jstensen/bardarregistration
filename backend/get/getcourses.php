<?php
/// Get information about all courses
/// Inputs: no
/// Results: int id, string name, string description, int capacity, int maxUnbalance, status
require_once("../config.php");
//checkLogin();

//Connect to database
$con=connectToDb();

//Get course information
$result=mysqli_query($con, 'SELECT id, name, capacity, description, maxUnbalance, status, solo from Course order by name');
if($result){
	$courses=array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$course=array();
		foreach($row as $key=>$element){
			$course[$key]=mb_convert_encoding($element, 'UTF-8');
		}
		$courses[]=$course;
		//echo $course;
	}
	//echo $courses;
	//Echo as json
	//var_dump($courses);
	echo(json_encode($courses));
}else exit("Could not find courses. ".mysqli_error($con)."<br />");
mysqli_close($con);
?>