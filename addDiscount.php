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

$type = $_POST['type'];
$email = $_POST['customer'];

if ($type = 'Fixed') {
    $percent = $_POST['percent'];
    $updateQuery = "INSERT INTO Discount (customer_email, type, percent) VALUES('$email', '$type', '$percent') ON DUPLICATE KEY UPDATE type='$type', percent='$percent'";

    mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));
}
else if ($type = 'Variable') {
    $percent1 = $_POST['mot'];
    $percent2 = $_POST['annual'];
    $percent3 = $_POST['repair'];

    $updateQuery = "INSERT INTO Discount (customer_email, type, percent1, percent2, percent3) VALUES('$email', '$type', '$percent1','$percent2','$percent3') ON DUPLICATE KEY UPDATE type='$type', percent1='$percent1', percent2='$percent2', percent3='$percent3'";

    mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));
}
else {
    $percent1 = $_POST['range1'];
    $percent2 = $_POST['range2'];
    $percent3 = $_POST['range3'];

    $updateQuery = "INSERT INTO Discount (customer_email, type, percent1, percent2, percent3) VALUES('$email', '$type', '$percent1','$percent2','$percent3') ON DUPLICATE KEY UPDATE type='$type', percent1='$percent1', percent2='$percent2', percent3='$percent3'";

    mysqli_query($conn, $updateQuery) or die(mysqli_error($conn));
}

?>