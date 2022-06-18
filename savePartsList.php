<?php
/*
 * This page saves a parts list for a job
 */
include 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

$id = $_POST['id'];
$partID = $_POST['input'];
$partQty = $_POST['inputQty'];

//delete the existing parts
$deletePartsListQuery = "DELETE FROM Parts_List WHERE Job_id = '$id'";
mysqli_query($conn, $deletePartsListQuery);

foreach ($partID as $partID) {
    foreach ($partQty as $partQty) {
        //save the new parts list
        mysqli_query($conn, "INSERT INTO Parts_List(Job_id, Part_id, quantity) VALUES ('$id', '$partID', '$partQty' )") or die(mysqli_error($conn));
        //update the parts level
        mysqli_query($conn, "UPDATE Stock_Ledger SET stock_level = stock_level - '$partQty' WHERE part_ID = '$partID'");
    }
}

echo "<script>
           alert('updated successfully!')
           window.location.href = 'home.php';
      </script>";