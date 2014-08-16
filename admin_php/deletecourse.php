<html>
<body>

<?php
/// Deleting course specified by id
/// Inputs: int courseId
/// 
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
		$id = $data['courseId'];
	}else{
		$id = $_POST['courseId'];
	}
	
	//Deleting course
	if(mysqli_query($con,"Delete from ".$dbprefix."Course where id=" . $id)){
		echo "Course deleted<br />";
	}else exit("Error deleting course: " . mysqli_error($con));
	
	mysqli_close($con);
}

echo '<a href="managecourses.php">Manage courses</a>';
?>
</body>
</html>