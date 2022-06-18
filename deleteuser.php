<?php
/*delete the staff from the database if they get fired*/
include("connection.php");
session_start();
$conn = openCon();

$deleteUser = $_POST['deleteUser'];



$sql = "DELETE FROM Staff WHERE Username = '$deleteUser'";
$result = mysqli_query($conn,$sql);
header ("location: system.php");

closeCon($conn);
?>