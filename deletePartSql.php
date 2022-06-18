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

$deletePartQuery = "DELETE FROM Stock_Ledger WHERE part_ID = '$id'";
mysqli_query($conn, $deletePartQuery) or die(mysqli_error($conn));

echo "<script>
           alert('Deleted successfully!')
           window.location.href = 'stock.php';
      </script>";
 ?>