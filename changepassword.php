<?php
/*change the password in the database*/
include("connection.php");
session_start();


$username = $_POST['currentUser'];
$newPassword = $_POST['npass'];

$EncryptedPassword= md5($newPassword);

$conn = openCon();


$sql = "UPDATE Staff SET password = '$newPassword' WHERE Username ='$username'";
$result = mysqli_query($conn,$sql);
header ("location: system.php");

?>
