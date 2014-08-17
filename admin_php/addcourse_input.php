<html>
<body>

<?php
require_once("../backend/config.php");
checkLogin();
echo '<h1>Add course</h1>';
echo '<form action="../backend/modify/addcourse.php" method="post">';
echo 'Name<br><input type="text" name="name"><br><br>';
echo 'Description<br><textarea name="description" rows="4" cols="50"></textarea><br><br>';
echo 'Capacity<br><input type="text" name="capacity"><br><br>';
echo 'MaxUnbalance<br><input type="text" name="maxUnbalance"><br><br>';
echo 'Status<br><select name="status"><option value="Open">Open</option><option value="Closed">Closed</option><option value="Waiting list">Waiting list</option></select><br><br>';
echo 'Solo<input type="checkbox" name="solo" value="1"><br><br>';
echo '<input type="hidden" name="courseId" value="0">';
echo '<input type="submit">';
echo '</form>';

?>
</body>
</html>