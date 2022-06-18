<?php
/*
 * This page edits a part in the database
 */
include 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$man = $_POST['man'];
$quantity = $_POST['quantity'];
$threshold = $_POST['threshold'];
$type = $_POST['type'];
$year = $_POST['year'];

$editCustomerQuery = "UPDATE Stock_Ledger SET part_name = '$name', part_price = '$price',part_manufacturer = '$man',stock_level = '$quantity',vehicle_type = '$type',year = '$year',threshold = '$threshold' WHERE part_ID = '$id'";

mysqli_query($conn, $editCustomerQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'stock.php';
      </script>";
closeCon($conn);
?>
