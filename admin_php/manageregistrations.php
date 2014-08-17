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
	$row=mysqli_fetch_array(mysqli_query($con,"Select name, capacity, solo from ".$dbprefix."Course where id=" . $id));
	$courseName=$row['name'];
	$capacity=$row['capacity'];
	echo '<h1>'.$courseName.' - påmeldinger</h1>';
	echo 'Gi plass til de som meldte seg på tidlig, ikke har fått plass på andre kurs (0 i kolonne "Andre kurs"), har '.$courseName.' som førstevalg (prioritet 1) og/eller har meldt seg på med partner.<br /><br />';
	
	
	echo '<form action="acceptandsendemail.php" method="post">';
	echo "<table><tr>";
	if($row['solo']!=1){
		foreach(array('lead','follow') as $role){
			echo '<td valign="top">';
			$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where role='".$role."' and courseId=" . $id . " and accepted=TRUE");
			if($result){
				$row = mysqli_fetch_row($result);
				echo $row[0] ." ". $role . 's har fått plass (kapasitet: '.$capacity.')<br />';
			}else exit("Error finding registrations: " . mysqli_error($con));
			$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where role='".$role."' and courseId=" . $id . " and accepted=FALSE");
			if($result){
				$row = mysqli_fetch_row($result);
				echo $row[0] ." ". $role . 's på venteliste<br />';
			}else exit("Error finding registrations: " . mysqli_error($con));
			
			$result=mysqli_query($con,"Select p.name personName, registrationTime, priority, accepted, partnerName, r.id registrationId, c.name courseName from ".$dbprefix."Registration r, ".$dbprefix."Person p, ".$dbprefix."Course c where personId=p.id and role='".$role."' and courseId=" . $id . " and c.id=courseId order by accepted desc, priority, registrationTime");
			echo "<table>";
			echo '<tr><th></th><th>Navn</th><th>Prioritet</th><th>Påmeldingstidspunkt</th><th>Andre kurs</th><th>Partner</th>';
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
	}else{
		echo '<td valign="top">';
		$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where courseId=" . $id . " and accepted=TRUE");
		if($result){
			$row = mysqli_fetch_row($result);
			echo $row[0] .' har fått plass (capacity: '.$capacity.')<br />';
		}else exit("Error finding registrations: " . mysqli_error($con));
		$result=mysqli_query($con,"Select count(*) from ".$dbprefix."Registration where courseId=" . $id . " and accepted=FALSE");
		if($result){
			$row = mysqli_fetch_row($result);
			echo $row[0] .' on waiting list<br />';
		}else exit("Error finding registrations: " . mysqli_error($con));
		
		$result=mysqli_query($con,"Select p.name personName, registrationTime, priority, accepted, partnerName, r.id registrationId, c.name courseName from ".$dbprefix."Registration r, ".$dbprefix."Person p, ".$dbprefix."Course c where personId=p.id and courseId=" . $id . " and c.id=courseId order by accepted desc, priority, registrationTime");
		echo "<table>";
		echo '<tr><th></th><th>Navn</th><th>Prioritet</th><th>Påmeldingstidspunkt</th><th>Andre kurs</th><th>Partner</th>';
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
	
	echo '<input type="submit" name="submit" value ="Gi plass og send e-post"><br />';
	echo 'E-post-melding (Gi plass):<br />';
	echo '<textarea name="message" cols=50 rows=3>Hei *navn*, du har fått plass på '.$courseName.' som *rolle*.</textarea><br />';
	echo '*navn* and *rolle* will be replaced with the actual name and roles<br /><br />';
	
	echo '<input type="submit" name="submit" value ="Slett og send e-post"><input type="submit" name="submit" value ="Slett"><br />';
	echo 'E-post-melding (Slett):<br />';
	echo '<textarea name="deletemessage" cols=50 rows=3>Hei *navn*, din påmelding til '.$courseName.' som *rolle* er slettet.</textarea><br /><br />';
	
	echo '<input type="submit" name="submit" value ="Frata plass og send e-post"><input type="submit" name="submit" value ="Frata plass"><br />';
	echo 'E-post-melding (Frata):<br />';
	echo '<textarea name="removemessage" cols=50 rows=3>Hei *navn*, du har ikke lenger plass på '.$courseName.' som *rolle*.</textarea><br /><br />';
	
	echo '<input type="submit" name="submit" value ="Send e-post"><br />';
	echo 'Tittel: <input type="text" name="title" value ="Du er på venteliste for '.$courseName.'">&nbsp&nbspE-post-melding:<br />';
	echo '<textarea name="removemessage" cols=50 rows=3>Hei *navn*, du er fortsatt på venteliste til '.$courseName.' som *rolle*.</textarea><br /><br />';
	mysqli_close($con);
}

echo '<a href="managecourses.php">Manage courses</a>';
?>
</body>
</html>