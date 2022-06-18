<?php
/*
 * This page updates the vehicle into the database
 * I should make it check that the data is safe and not malicious
 */
include 'connection.php';
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

$editCustomerQuery = "UPDATE Vehicle_record SET  reg_number = '$registration',email = '$email',make = '$make',model = '$model',colour = '$color',mot_reminder = '$mot',last_service = '$service' WHERE reg_number = '$registration'";

mysqli_query($conn, $editCustomerQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'vehicleRecords.php';
      </script>";

closeCon($conn);
?>