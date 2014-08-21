<?php
if(time()>strtotime("2014-08-24 20:00 GMT+0200"))
	echo json_encode(true);
else echo json_encode(false);
?>