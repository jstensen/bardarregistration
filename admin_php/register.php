<html>
<body>
<?php
$con=mysqli_connect("localhost","root","","Registrations");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$maxNumberOfCourses=3;
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	$courses = mysqli_query($con,"SELECT id, name, description FROM Course where status not like 'Closed' order by name");
	
	echo '<form action="register.php" method="post">';
	echo 'Name: <input type="text" name="name" value="Erik Lien Johnsen" required><br>';
	echo 'Address: <input type="text" name="address" value="Platous gate 11" required><br>';
	echo 'E-mail address: <input type="text" name="eMail" value="erik.lien.johnsen@gmail.com" required><br>';
	echo 'Phone number: <input type="text" name="phone" value="48164681"><br>';
	echo 'Gender: <input type="radio" name="gender" value="male" checked="checked">Male | <input type="radio" name="gender" value="female">Female<br>';
	echo 'Date of birth: <input type="date" name="dateOfBirth" value="1986-07-07" required><br>';
	echo "<br />";
	$ii=0;
	while($row = mysqli_fetch_array($courses)){
		$courseList[$ii]=$row;
		$ii++;
	}
	for($courseNumber=1; $courseNumber <= $maxNumberOfCourses; $courseNumber++){
		echo 'Course: <select name="course['.$courseNumber.']">';
		if($courseNumber>1) echo '<option value="-1">No course number '.$courseNumber.'</option>';
		foreach($courseList as $row) {
			echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		}
		echo '</select><br>';
		echo 'Role: <input type="radio" name="role['.$courseNumber.']" value="lead" checked="checked">Lead | <input type="radio" name="role['.$courseNumber.']" value="follow">Follow<br>';
		echo 'Partner ID: <input type="text" name="partnerId['.$courseNumber.']"><br>';
	}
	echo '<input type="submit">';
	echo '</form>';
}else{
	$name = mysqli_real_escape_string($con, $_POST['name']);
	$address = mysqli_real_escape_string($con, $_POST['address']);
	$eMail = mysqli_real_escape_string($con, $_POST['eMail']);
	$phone = mysqli_real_escape_string($con, $_POST['phone']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);
	$dateOfBirth = mysqli_real_escape_string($con, $_POST['dateOfBirth']);
	
	$existingPerson = mysqli_query($con,'SELECT id FROM Person where eMail="'.$eMail.'"' );
	if(mysqli_num_rows($existingPerson)>0){
		echo exit("Vi har allerede mottatt en påmelding med den e-postadressen. Ta kontakt med oss på bardarswingclub alfakrøll gmail dått com om du trenger hjelp.");
	}else{
		$query = "INSERT INTO Person (name, address, eMail, phone, gender, dateOfBirth)
VALUES ('" . $name . "', '" . $address . "', '" . $eMail . "', '" . $phone . "', '" . $gender . "', '" . date("Y-m-d H:i:s",strtotime($dateOfBirth)) . "')";
		if(mysqli_query($con,$query)){
			echo "Person registered.<br />";
		}else exit("Problem adding person.<br />".mysqli_error($con)."<br />".$query);
	}
	
	
	$person = mysqli_fetch_array(mysqli_query($con,'SELECT id FROM Person where eMail like "'.$eMail.'"' ));
	$personId = $person['id'];
	
	$courseIdArray = $_POST['course'];
	$roleArray = $_POST['role'];

	$partnerIdArray = $_POST['partnerId'];

	for($courseNumber=1; $courseNumber <= $maxNumberOfCourses; $courseNumber++){
		$partnerId=mysqli_real_escape_string($con,$partnerIdArray[$courseNumber]);
		$priority = $courseNumber;
		$courseId = mysqli_real_escape_string($con,$courseIdArray[$courseNumber]);
		$role=mysqli_real_escape_string($con,$roleArray[$courseNumber]);
		if($courseId<=0){
			break;
		}
		$course = mysqli_fetch_array(mysqli_query($con,'SELECT name from Course where id='.$courseId));
		
		$partner = mysqli_query($con,'SELECT name, eMail from Person where id='.$partnerId);
		$registeredWithPartner=false;
		if($partner){
			if(mysqli_num_rows($partner)==0) $partnerId = 0;
			else{
				$partnersRegistration = mysqli_fetch_array(mysqli_query($con,'SELECT role, partnerId, priority, id from Registration where personId='.$partnerId.' and courseId='.$courseId));
				if(count($partnersRegistration)==0){
					echo 'Partner not signed up for '.$course['name'].' (yet)';
					$partnerId = 0;
				}elseif($partnersRegistration['role']==$role){
					echo 'Partner signed up as '.$role.' too - you cannot sign up together with the same role';
					$partnerId = 0;
				}elseif($partnersRegistration['partnerId']>0){
					echo 'Partner already signed up with another partner';
					$partnerId = 0;
				}else{
					$partnersPriority=$partnersRegistration['priority'];
					if($partnersPriority<>$priority) echo "Your partner has signed up for ".$course[name].' as course number '.$partnerPriority.', while it is your course number  '.$priority.'.';
					$partnerInfo = mysqli_fetch_array($partner);
					ini_set("SMTP", "aspmx.l.google.com");
					ini_set("sendmail_from", "bardarswingclub@gmail.com");
					$message = $name ." har meldt seg på kurs med deg som partner";
					$headers = "From: bardarswingclub@gmail.com";
					mail($partnerInfo['eMail'], "Testing", $message, $headers);
					$partnerName = $partnerInfo['name'];
					echo "E-mail sent to " . $partnerName . ".<br>";
					$registeredWithPartner = true;
				}
			}
		}else $partnerId=-1;
		
		$query = "INSERT INTO Registration (personId, courseId, registrationTime, priority, role, partnerId, accepted)
	VALUES (" . $personId . ", " . $courseId . ", '" . date('Y-m-d H:i:s') . "', " . $priority . ", '" . $role . "', " . $partnerId . ", " . 'FALSE' . ")";
		if(mysqli_query($con, $query)){
			if($registeredWithPartner) mysqli_query($con,'update Registration(partnerId) set partnerId='.$personId.' where id='.$partnersRegistration['id']);
		}else exit("Error with course registration. ".mysqli_error($con)."<br />".$query."<br />");
		
	}
	$result=mysqli_query($con, 'SELECT c.name courseName, r.role, p.name personName from Course c, Registration r, Person p where c.id=r.courseId and r.personId=' . $personId . ' and p.id=r.partnerId');
	if($result){
		while($row = mysqli_fetch_array($result)) {
			if(strlen($row['personName'])>0){
				$partnerName = " ".$row['personName'];
			}else{
				$partnerName = "out partner";
			}
			echo "Registration received for " . $row['courseName'] . " as " . $row['role'] . " with" . $partnerName . " as partner.";
			echo "<br />";
		}
	}else exit("Could not find course. ".mysqli_error($con)."<br />");
}
mysqli_close($con);
?>
</body>
</html>