<html>
<body>

<?php
require_once("../backend/config.php");
checkLogin();
$con=connectToDb();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	$result=mysqli_query($con, 'SELECT id, name, description, capacity, maxUnbalance, status from '.$dbprefix.'Course order by name');
	if($result){
		echo '<h1>Manage courses</h1>';
		echo '<table>';
		while($row = mysqli_fetch_array($result)) {
			echo '<tr>';
			echo '<td>'.$row['name'].'</td>';
			echo '<td><form action="manageregistrations.php" method="post"><input type="hidden" name="courseId" value="'.$row['id'].'" /><input type="submit" value="Manage registrations" /></form></td>';
			echo '<td>'.'<form action="managecourses.php" method="post"><input type="hidden" name="courseId" value="'.$row['id'].'" /><input type="submit" value="Edit course information" /></form></td>';
			echo '<td>'.'<form action="deletecourse.php" method="post"><input type="hidden" name="courseId" value="'.$row['id'].'" /><input type="submit" value="Delete" /></form></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<a href="addcourse_input.php">Legg til et nytt kurs</a>';
	}else exit("Could not find courses. ".mysqli_error($con)."<br />");
}else{
	$courseId = mysqli_real_escape_string($con, $_POST['courseId']);
	
	$courseInfo=mysqli_fetch_array(mysqli_query($con, 'SELECT name, description, capacity, maxUnbalance, status, solo from '.$dbprefix.'Course where id =' . $courseId));

	$name = $courseInfo['name'];
	$description = $courseInfo['description'];
	$capacity = $courseInfo['capacity'];
	$maxUnbalance = $courseInfo['maxUnbalance'];
	$status = $courseInfo['status'];
	$selected = array_fill_keys(array('Open', 'Closed', 'Waiting list'), '');
	$selected[$status] = ' selected';
	$solo = $courseInfo['solo'];
	if($solo==1) $checked=' checked="checked"';else $checked="";
	
	echo '<h1>Edit course information</h1>';
	echo '<form action="..\backend\modify\addcourse.php" method="post">';
	echo 'Name:<br><input type="text" name="name" value ="' .$name.'"><br>';
	echo 'Description:<br><textarea name="description">'.$description.'</textarea><br>';
	echo 'Capacity:<br><input type="text" name="capacity" value ="' .$capacity.'"><br>';
	echo 'MaxUnbalance:<br><input type="text" name="maxUnbalance" value ="' .$maxUnbalance.'"><br>';
	echo 'Status:<br><select name="status"><option value="Open"'.$selected['Open'].'>Open</option><option value="Closed"'.$selected['Closed'].'>Closed</option><option value="Waiting list"'.$selected['Waiting list'].'>Waiting list</option></select><br>';
	echo 'Solo <input type="checkbox" name="solo" value="TRUE"'.$checked.'><br>';
	echo '<input type="hidden" name="courseId" value="'.$courseId.'">';
	echo '<input type="submit">';
	echo '</form>';
}
mysqli_close($con);
?>
</body>
</html>