<?php
//create the job in the database
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

include "connection.php";

$conn = openCon();

$name = $_POST['name'];
$email = $_POST['email'];
$make = $_POST['make'];
$model = $_POST['model'];
$color = $_POST['color'];
$registration = $_POST['registration'];
$work = $_POST['work'];
$type = $_POST['type'];

$createJobQuery = "INSERT INTO Job(customer_name, customer_email, vehicle_make, vehicle_model, vehicle_color, required_work, vehicle_registration, jobType) VALUES ('$name','$email','$make','$model','$color','$registration','$work','$type')";

mysqli_query($conn, $createJobQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('Registered successfully!')
           window.location.href = 'home.php';
      </script>";
closeCon($conn);
?>
