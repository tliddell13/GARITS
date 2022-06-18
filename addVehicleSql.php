<?php
/*
 * This page updates adds a vehicle into the database
 */
include_once "connection.php";
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$registration = $_POST['registration'];
$email = $_POST['email'];
$make = $_POST['make'];
$model = $_POST['model'];
$color = $_POST['color'];
$mot = $_POST['mot'];
$service = $_POST['service'];

$newCustomerQuery = "INSERT INTO Vehicle_record(reg_number, colour, last_service, model, make, mot_reminder, email) VALUES ('$registration','$color','$service', '$model','$make','$mot','$email')";

mysqli_query($conn, $newCustomerQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'index.php';
      </script>";

closeCon($conn);
?>