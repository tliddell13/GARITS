<?php
/*
 * This page adds a part into the database
 */
include_once 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['partNumber'];
$supplier = $_POST['supplier'];
$quantity = $_POST['quantity'];

$newPartQuery = "INSERT INTO PartOrder(partNo, supplier, quantity) VALUES ('$id','$supplier',$quantity)";

mysqli_query($conn, $newPartQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'orderParts.php';
      </script>";

closeCon($conn);
?>
