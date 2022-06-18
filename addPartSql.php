<?php
/*
 * This page adds a part into the database
 */
include_once 'connection.php';
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

$newPartQuery = "INSERT INTO Stock_Ledger(part_ID, part_name, part_price, part_manufacturer, stock_level, vehicle_type, year, threshold) VALUES ('$id','$name','$price', '$man','$quantity','$type','$year','$threshold')";

mysqli_query($conn, $newPartQuery)
or die(mysqli_error($conn));

echo "<script>
           alert('updated successfully!')
           window.location.href = 'stock.php';
      </script>";

closeCon($conn);
?>