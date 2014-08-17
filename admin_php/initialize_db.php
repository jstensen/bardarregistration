<html>
<body>

<?php
require_once('../backend/config.php');
// Check connection
$con=connectToSQL();
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$databaseName="bardarswingclub2";
$sql[0]="create database if not exists ".$databaseName;

$sql[1]="use ".$databaseName;

$sql[2]="create table if not exists ".$dbprefix."Course(
	id int not null auto_increment primary key,
	name varchar(255),
	capacity int,
	maxUnbalance int,
	status varchar(20),
	solo boolean,
	description varchar(999)
)";

$sql[3]="create table if not exists ".$dbprefix."Person(
	id int not null auto_increment primary key,
	name varchar(255),
	address varchar(255),
	postalNumber varchar(10),
	town varchar(65),
	formerMember boolean,
	eMail varchar(255) not null,
	phone varchar(20),
	gender varchar(6),
	dateOfBirth date
)";

//$sql[4]='insert into ".$dbprefix."Person(id, name, eMail) values(-1, "Nobody", "")';

//$sql[5]='insert into ".$dbprefix."Person(id, name, eMail) values(0, "Partner not signed up yet", "")';

$sql[6]="create table if not exists ".$dbprefix."Registration(
	id int not null auto_increment primary key,
	personId int not null,
	courseId int not null,
	partnerName varchar(255),
	registrationTime dateTime,
	priority int,
	role varchar(7),
	accepted boolean,
  	FOREIGN KEY (personId) 
  	REFERENCES ".$dbprefix."Person (id) 
  	ON DELETE CASCADE,
	FOREIGN KEY (courseId) 
  	REFERENCES ".$dbprefix."Course (id) 
  	ON DELETE CASCADE
)";

$sql[7]="create table if not exists ".$dbprefix."Member(
	id int(4) NOT NULL auto_increment primary key,
	username varchar(65) NOT NULL default '',
	password varchar(65) NOT NULL default ''
)";

$sql[8]="INSERT INTO ".$dbprefix."Member VALUES (1, 'sjef1', 'sj3fsp4ss0rd')";


foreach($sql as $query){
	if(mysqli_query($con,$query)){
		echo $query . ": OK" . "<br />";
	}else{
		echo $query . ": NOT OK! " . mysqli_error($con) . "<br />";
	}
}

mysqli_close($con);
?>
</body>
</html>