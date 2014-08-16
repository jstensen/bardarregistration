<html>
<body>

<?php
/// Select persons to be admitted to or deleted from selected course
/// Inputs: int courseId
/// 
require_once("../backend/config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo 'Missing data<br />';
}else{
	//Connect to database
	$con=connectToDb();
	$id = $_POST['courseId'];
	$row=mysqli_fetch_array(mysqli_query($con,"Select name, capacity, solo from course where id=" . $id));
	$courseName=$row['name'];
	$capacity=$row['capacity'];
	echo '<h1>'.$courseName.' - registrations</h1>';
	echo 'Accept those who registered early, are not accepted to other courses (0 in column "Courses"), has '.$courseName.' as 1st priority and/or signed up with a partner.<br /><br />';
	
	if($row['solo']==1){
		$roles = array("NULL");
	}else{
		$roles=array('lead','follow');
	}
	echo '<form action="acceptandsendemail.php" method="post">';
	echo "<table><tr>";
	foreach($roles as $role){
		echo '<td valign="top">';
		$result=mysqli_query($con,"Select count(*) from registration where role='".$role."' and courseId=" . $id . " and accepted=TRUE");
		if($result){
			$row = mysqli_fetch_row($result);
			echo $row[0] ." ". $role . 's accepted (capacity: '.$capacity.')<br />';
		}else exit("Error finding registrations: " . mysqli_error($con));
		$result=mysqli_query($con,"Select count(*) from registration where role='".$role."' and courseId=" . $id . " and accepted=FALSE");
		if($result){
			$row = mysqli_fetch_row($result);
			echo $row[0] ." ". $role . 's on waiting list<br />';
		}else exit("Error finding registrations: " . mysqli_error($con));
		
		$result=mysqli_query($con,"Select p.name personName, registrationTime, priority, accepted, partnerName, r.id registrationId, c.name courseName from registration r, person p, course c where personId=p.id and role='".$role."' and courseId=" . $id . " and c.id=courseId order by accepted desc, priority, registrationTime");
		echo "<table>";
		echo '<tr><th></th><th>Name</th><th>Priority</th><th>RegistrationTime</th><th>Courses</th><th>Partner</th>';
		if($result){
			while($row = mysqli_fetch_array($result)) {
				if($row['accepted']) echo '<tr bgcolor="#99FF99">'; else echo '<tr bgcolor="#FFFFCC">';
				echo '<td>';
				echo '<input type="checkbox" name="registrationIds[]" value="' . $row['registrationId'] .'">';
				echo '</td><td>' . $row['personName'] . '</td><td>' . $row['priority'] . '</td><td>' . $row['registrationTime'] . '</td><td>' . $row['accepted'] . '</td><td>' . $row['partnerName'] . '</td>';
				echo '</tr>';
			}
		}else exit("Error reading registrations: " . mysqli_error($con));
		echo '</table>';
		echo "</td>";
	}
	echo "</tr><table>";
	echo '<input type="hidden" name="courseId" value ="'.$id.'">';
	echo '<input type="submit" name="submit" value ="Accept and send e-mail"><br />';
	echo 'E-mail message (Accept):<br />';
	echo '<textarea name="message" cols=50 rows=3>Hei *navn*, du har fått plass på '.$courseName.' som *rolle*.</textarea><br />';
	echo '*navn* and *rolle* will be replaced with the actual name and roles<br /><br />';
	echo '<input type="submit" name="submit" value ="Delete and send e-mail"><input type="submit" name="submit" value ="Delete"><br />';
	echo 'E-mail message (Delete):<br />';
	echo '<textarea name="deletemessage" cols=50 rows=3>Hei *navn*, din påmelding til '.$courseName.' som *rolle* er slettet.</textarea><br /><br />';
	echo '<input type="submit" name="submit" value ="Remove acceptance and send e-mail"><input type="submit" name="submit" value ="Remove acceptance"><br />';
	echo 'E-mail message (Remove):<br />';
	echo '<textarea name="removemessage" cols=50 rows=3>Hei *navn*, du har ikke lenger plass på '.$courseName.' som *rolle*.</textarea><br />';
	mysqli_close($con);
}

echo '<a href="managecourses.php">Manage courses</a>';
?>
</body>
</html>