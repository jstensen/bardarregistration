<html>
<body>

<?php
$con=mysqli_connect("localhost","root");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql[0]="create database Registrations";

$sql[1]="use Registrations";

$sql[2]="create table Course(
	id int not null auto_increment primary key,
	name varchar(255),
	capacity int,
	maxUnbalance int,
	status varchar(20),
	solo boolean,
	description varchar(999)
)";

$sql[3]="create table Person(
	id int not null auto_increment primary key,
	name varchar(255),
	address varchar(255),
	eMail varchar(255) not null,
	phone varchar(20),
	gender varchar(6),
	dateOfBirth date
)";

//$sql[4]='insert into Person(id, name, eMail) values(-1, "Nobody", "")';

//$sql[5]='insert into Person(id, name, eMail) values(0, "Partner not signed up yet", "")';

$sql[6]="create table Registration(
	id int not null auto_increment primary key,
	personId int not null,
	courseId int not null,
	partnerName varchar(255),
	registrationTime dateTime,
	priority int,
	role varchar(7),
	accepted boolean,
  	FOREIGN KEY (personId) 
  	REFERENCES Person (id) 
  	ON DELETE CASCADE,
	FOREIGN KEY (courseId) 
  	REFERENCES Course (id) 
  	ON DELETE CASCADE
)";

$sql[7]="create table member(
	id int(4) NOT NULL auto_increment primary key,
	username varchar(65) NOT NULL default '',
	password varchar(65) NOT NULL default ''
)";

$sql[8]="INSERT INTO member VALUES (1, 'sjef1', 'sj3fsp4ss0rd')";



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