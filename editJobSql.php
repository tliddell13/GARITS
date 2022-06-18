<?php
/*
 *
 * This page updates a job in the database
 * should make it check that the data is safe
 */
include_once 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['jobId'];
$name = $_POST['name'];
$email = $_POST['email'];
$make = $_POST['make'];
$model = $_POST['model'];
$color = $_POST['color'];
$registration = $_POST['registration'];
$reqWork = $_POST['reqWork'];
$compWork = $_POST['compWork'];
$jobStatus = $_POST['jobStatus'];
$type = $_POST['type'];
$bay = $_POST['bay'];
$mechanic = $_POST['mechanic'];

$editJobQuery = "UPDATE Job SET customer_name = '$name',customer_email = '$email',vehicle_make = '$make',vehicle_model = '$model',vehicle_color = '$color',required_work = '$reqWork',completed_work = '$compWork', jobStatus = '$jobStatus',vehicle_registration = '$registration',jobType = '$type', BayNo = '$bay', Mechanic = '$mechanic' WHERE Job_id = '$id'";

mysqli_query($conn, $editJobQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'home.php';
      </script>";

closeCon($conn);
?>