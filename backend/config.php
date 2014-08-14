<?php

function checkLogin(){
	session_start();
	if(!isset($_SESSION['username'])){
		//header("location:login.php");
		http_response_code(402);
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

require 'PHPMailer-master/PHPMailerAutoload.php';
     

function email($receiveraddress, $subject, $message){
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPAuth   = true; 
	$mail->SMTPSecure = "ssl";
	$mail->Host       = "smtp.gmail.com"; 
	$mail->Port       = 465;
	//$mail->Username = 'bardarswingclub@gmail.com';                 // SMTP username
	$mail->Username = 'erik.lien.johnsen@gmail.com';
	$mail->Password = 'aSdFgHjK';  
	
	$mail->From = 'bardarswingclub@gmail.com';
	
	$mail->addAddress('erik.lien.johnsen@gmail.com');
	//$mail->addBcc($receiveraddress);
	$mail->Subject = $subject;
	$mail->Body    = $message;
	if($mail->send()) return true; else return "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
}
?>