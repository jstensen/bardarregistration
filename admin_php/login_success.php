<?php
require_once("../backend/config.php");
session_start();
if(!isset($_SESSION['username'])){
	header("location:login.php");
}
?>

<html>
<body>
Login Successful
</body>
</html>