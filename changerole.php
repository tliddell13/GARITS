<?php
/*change the role in the database*/
include("connection.php");
session_start();

$conn = openCon();

$currentUser = $_POST['currentUser'];
$newRole = $_POST['newRole'];



$sql = "UPDATE Staff SET Role = '$newRole' WHERE Username ='$currentUser'";
$result = mysqli_query($conn,$sql);
header ("location: system.php");

closeCon($conn);
?>