<?php
/*
 * This page adds a customer into the database
 *
 */
include_once "connection.php";

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$phone2 = $_POST['2phone'];
$address = $_POST['address'];
$payLate = $_POST['payLate'];

$newCustomerQuery = "INSERT INTO Customer(email, customer_name, dob, phone_no, homePhone, address, pay_late) VALUES ('$email','$name','$dob', '$phone', '$phone2', '$address','$payLate')";

mysqli_query($conn, $newCustomerQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'customer.php';
      </script>";
closeCon($conn);
?>