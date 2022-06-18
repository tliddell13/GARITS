<?php
/*
 * this page is activated by when the franchisee logs in and
 */
include_once 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$payQuery = "SELECT customer_name FROM Job WHERE( (payment_due < CURDATE()) AND paid = 0)";

$payQueryResults = mysqli_query($conn, $payQuery);

if($payQueryResults->num_rows > 0) {
    while ($row = $payQueryResults->fetch_assoc()) {
        $customer = "This customer is late for payment: ".$row['customer_name'];
        echo "<script>alert('$customer');</script>";
    }
}

?>