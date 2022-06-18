<?php
/*
 * This page deletes a vehicle record from a database
 */
include 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$registration = $_POST['registration'];

$deleteCustomerQuery = "DELETE FROM Vehicle_record WHERE reg_number = '$registration'";
mysqli_query($conn, $deleteCustomerQuery) or die(mysqli_error($conn));

echo "<script>
           alert('Deleted successfully!')
           window.location.href = 'vehicleRecords.php';
      </script>";

closeCon($conn);
?>