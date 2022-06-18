<?php
/*
 * This page updates the customer into the database
 * I should make it check that the data is safe and not malicious
 */
include 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$phone2 = $_POST['2phone'];
$address = $_POST['address'];
$payLate = $_POST['payLate'];

$editCustomerQuery = "UPDATE Customer SET customer_name = '$name',email = '$email',dob = '$dob',phone_no = '$phone', homePhone = '$phone2', address = '$address',pay_late = '$payLate' WHERE id = '$id'";

mysqli_query($conn, $editCustomerQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'customer.php';
      </script>";

closeCon($conn);

?>
