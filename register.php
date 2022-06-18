<?php
/*add a new user to the system*/
include("connection.php");

$Username = $_POST['un'];
$Password = $_POST['pw'];
$Role = $_POST['role'];

$conn = openCon();

//encrypt the password
$EncryptedPassword= md5($Password);

$sql = "INSERT INTO Staff VALUES ('$Username','$EncryptedPassword','$Role')";

$result = mysqli_query($conn,$sql);

header ("location: adminHome.php");

?>