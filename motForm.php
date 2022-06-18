<?php

//This form sends an motForm to customers who are overdue, I used cronjobs to run it on my computer
include_once 'connection.php';

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();

//Make a query to find all vehicles overdue for an MOT
$motQuery = "SELECT * FROM Vehicle_record WHERE (CURDATE() - mot_reminder) > 372";
$motQueryResults = mysqli_query($conn,$motQuery);


if($motQueryResults->num_rows > 0) {
    while ($row = $motQueryResults->fetch_assoc()) {
        $email = $row['email'];
        $make = $row['make'];
        $model = $row['model'];
        //Make a query to select customers with an MOT due

        $name = 'tyler';

        $msg = "Hello $name\n your $make $model is due for an MOT.\n Sincerely, GARITS";

        echo mail($email, "MOT reminder", $msg);


    }
}