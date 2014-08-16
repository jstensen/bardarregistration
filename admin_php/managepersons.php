<html>
<body>

<?php
require_once("../backend/config.php");
checkLogin();
if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	$con=connectToDb();	
	$result=mysqli_query($con, 'SELECT id, name, eMail, (select count(*) from '.$dbprefix.'Registration where personId=p.id and accepted=TRUE) as numberOfCourses from '.$dbprefix.'Person p where id >0 order by id');
	if($result){
		echo '<h1>Manage persons<h2>';
		echo '<table>';
		echo '<tr><th>Name</th><th>Courses</th><th>E-mail</th></tr>';
		while($row = mysqli_fetch_array($result)) {
			echo '<tr>';
			echo '<td>'.$row['name']. '</td><td>'. $row['numberOfCourses']."</td><td>".$row['eMail']."</td><td>".'<form action="editpersonalia.php" method="post"><input type="hidden" name="personId" value="'.$row['id'].'" /><input type="submit" value="Edit personalia" /></form></td>';
			echo '<td><form action="deleteperson.php" method="post"><input type="hidden" name="personId" value="'.$row['id'].'" /><input type="submit" value="Delete" /></form></td>';
			echo'</tr>';
		}
		echo '</table>';
	}else exit("Could not find persons. ".mysqli_error($con)."<br />");
}else{
	echo "This page cannot not receive data";
}
?>
</body>
</html>