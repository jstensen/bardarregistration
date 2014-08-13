<?php

function checkLogin(){
	session_start();
	if(!isset($_SESSION['username'])){
		//header("location:login.php");
		http_response_code(404);
	}
	return true;
}

function connectToDb(){
    $con=mysqli_connect("localhost","root","","Registrations");
	// Check connection
	if (mysqli_connect_errno()) {
	  exit("Failed to connect to MySQL: " . mysqli_connect_error() . "<br />");
	}
    return $con;
}

function email($receiveraddress, $subject, $message){
	return mail($receiveraddress, $subject, $message);
}
?>