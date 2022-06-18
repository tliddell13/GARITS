<?php
//This alert is activated when the franchisee logs in, and tells him all the parts that are below threshold
include_once 'connection.php';

    if(!isset($_SESSION)) {
        session_start(); // start the session if it still does not exist
    }

    $conn = openCon();

$partsQuery = "SELECT part_ID FROM Stock_Ledger WHERE stock_level < threshold";

$partsQueryResults = mysqli_query($conn, $partsQuery);

if($partsQueryResults->num_rows > 0) {
    while ($row = $partsQueryResults->fetch_assoc()) {
        $part = "Low stock for part ".$row['part_ID'];
        echo "<script>alert('$part');</script>";
    }
}
?>
