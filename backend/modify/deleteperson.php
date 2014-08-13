<?php
/// Deleting person specified by id
/// Inputs: int personId
/// 
require_once("config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();
	
	//Read POST data (either from js/jason, or from normal php)
	$data=json_decode(file_get_contents('php://input'));
	if(isset($data->personId)){
		$id = $data['personId'];
	}else{
		$id = $_POST['personId'];
	}
	if(mysqli_query($con,"Delete from Person where id=" . $id)){
		echo "Person deleted<br />";
	}else exit("Error deleting person: " . mysqli_error($con));
	
	mysqli_close($con);
}

echo '<a href="managepersons.php">Manage persons</a>';
?>