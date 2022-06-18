<?php
/*
 * This page updates the customer into the database
 */
include 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['id'];

$deleteCustomerQuery = "DELETE FROM Customer WHERE id = '$id'";
mysqli_query($conn, $deleteCustomerQuery) or die(mysqli_error($conn));

echo "<script>
           alert('Deleted successfully!')
           window.location.href = 'home.php';
      </script>";

closeCon($conn);

?>