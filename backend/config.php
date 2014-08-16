<?php
$root = "http://localhost/hello_world/";

echo '<a href="'.$root.'admin_php/managecourses.php">Administrer kurs</a>&nbsp&nbsp';
echo '<a href="'.$root.'admin_php/addcourse_input.php">Legg til nytt kurs</a>&nbsp&nbsp';
echo '<a href="'.$root.'admin_php/managepersons.php">Administrer personer</a>&nbsp&nbsp';
echo '<a href="'.$root.'angular-seed/app/#/registrer">Påmeldingsskjema</a>&nbsp&nbsp';
echo '<a href="'.$root.'admin_php/logout.php">Logg ut</a>';
echo '<br />';



function checkLogin(){
	session_start();
	if(!isset($_SESSION['username'])){
		//header("location:login.php");
		http_response_code(402);
		exit("Not logged in.<br /><a href=../admin_php/login.php>Log in</a>");
	}
	return true;
}

function connectToDb(){
    $con=mysqli_connect("localhost","root","","Registrations");
	mysqli_set_charset($con, "utf8");
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