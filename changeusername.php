<?php
/*change the username in the database*/
include("connection.php");
session_start();


$oldUsername = $_POST['changeUsername'];
$newUsername = $_POST['nun'];
$conn = openCon();



$sql = "UPDATE users SET Username = '$newUsername' WHERE Username ='$oldUsername'";
$result = mysqli_query($conn,$sql);
header ("location: adminHome.php");

?>