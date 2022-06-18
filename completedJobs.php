<!DOCTYPE html>

<?php
error_reporting(E_ERROR | E_PARSE);
/*
 * Here a user can see which jobs are completed, get an invoice, and the franchisee can generate reports
 */

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

include_once "connection.php";
include 'htmlHeader.php';

$conn = openCon();

$username = $_SESSION['name'];
//check what kind of authorization they have
$getUserQuery = "SELECT type FROM Staff WHERE username = '$username'";
$typeResults = mysqli_query($conn, $getUserQuery);
$type = $typeResults->fetch_assoc();
//the mechanic is the only user that needs a different header
if ($type['type'] == 'Mechanic') {
    include 'mechanicHeader.php';
}
else include 'header.php';

//We used datatables to create the tables
?>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <style>
        <?php include 'customer.css'?>
    </style>
    <title>Completed Jobs</title>
</head>
<body>
<main>
    <?php

    //The query created to find which jobs have been accepted by a mechanic, but are still incomplete
    $JobsQuery = "SELECT * FROM Job WHERE jobStatus = 'complete'";
    //Make the query to acquire the data
    $JobsQueryResults = mysqli_query($conn, $JobsQuery);

    //This function calculates the job total
    function calculateTotal($id, $email, $conn): float|int
    {
        $partsQuery = "SELECT * FROM parts_list WHERE Job_id = '$id'";
        $partsQueryResults = mysqli_query($conn, $partsQuery);
        //some variables i need
        $partsUsed = array();
        $partsQuantity = array();
        $partsPrice = array();
        $partsName = array();
        //loop through and put the needed values in arrays
        if ($partsQueryResults->num_rows > 0) {
            while ($row = $partsQueryResults->fetch_assoc()) {
                $partsUsed[] = $row['Part_id'];
                $partsQuantity[] = $row['quantity'];
            }
        }

        $jobQuery = "SELECT * FROM Job WHERE Job_id = '$id'";
        $jobQueryResults = mysqli_query($conn, $jobQuery);

        $InfoRow = $jobQueryResults->fetch_assoc();

        //this loooooop gives me the price of the parts
        for ($count = 0; $count < sizeof($partsUsed); $count++) {
            $partPriceQuery = "SELECT * FROM Stock_Ledger WHERE part_ID = '$partsUsed[$count]'";
            $partPriceQueryResults = mysqli_query($conn, $partPriceQuery);
            if ($partPriceQueryResults->num_rows > 0) {
                while ($row = $partPriceQueryResults->fetch_assoc()) {
                    $partsName[$count] = $row['part_name'];
                    $partsPrice[$count] = $row['part_price'];
                }
            }
        }
        $mechanicName = $InfoRow['Mechanic'];


        $mechanicQuery = "SELECT * FROM Staff WHERE employee_name = '$mechanicName'";
        $mechanicQueryResults = mysqli_query($conn, $mechanicQuery);
        $mechanicInfo = $mechanicQueryResults->fetch_assoc();

        $discountQuery = "SELECT * FROM Discount WHERE customer_email = '$email'";
        $discountQueryResults = mysqli_query($conn, $discountQuery);
        $discountInfo = $discountQueryResults->fetch_assoc();


        //Alllll the variables i need
        $mechanicPay = $mechanicInfo['pay_rate'];
        $labor = $InfoRow['hours_worked'];
        //calculating the parts total with no discount
        $partsTotal = 0;
        if (is_null($discountInfo)) {
            for ($count = 0; $count < sizeof($partsUsed); $count++) {
                $partsTotal += $partsQuantity[$count] * $partsPrice[$count];
            }
            $laborTotal = $mechanicPay * $labor;
            $total = $laborTotal + $partsTotal;
            $vat = $total * .20;
            $grandTotal = $total + $vat;
            //update database on the total
            $insertTotalQuery = "UPDATE Job set total_cost = CAST('$grandTotal' AS DECIMAL(10,2))WHERE Job_id = '$id'";
            mysqli_query($conn, $insertTotalQuery);

            return number_format($grandTotal, 2, ".", ",");
        }
        //a fixed discount applied
        else if ($discountInfo['type'] = 'fixed') {
            for ($count = 0; $count < sizeof($partsUsed); $count++) {
                $partsTotal += $partsQuantity[$count] * $partsPrice[$count];
            }
            $laborTotal = $mechanicPay * $labor;
            $total = $laborTotal + $partsTotal;
            $total = $total - ($total * ($discountInfo['percent'] / 100));
            $vat = $total * .20;
            $grandTotal = $total + $vat;
            //update database on the total
            $insertTotalQuery = "UPDATE Job set total_cost = CAST('$grandTotal' AS DECIMAL(10,2))WHERE Job_id = '$id'";
            mysqli_query($conn, $insertTotalQuery);

            return number_format($grandTotal, 2, ".", ",");
        }
        //a variable discount applied
        else if ($discountInfo['type'] = 'variable') {
            for ($count = 0; $count < sizeof($partsUsed); $count++) {
                $partsTotal += $partsQuantity[$count] * $partsPrice[$count];
            }
            //change discount amount based on job type
            if ($InfoRow['type'] = 'mot') {
                $discount = $discountInfo['percent1'] / 100;
            }
            elseif ($InfoRow['type'] = 'annual') {
                $discount = $discountInfo['percent2'] / 100;
            }
            else {
                $discount = $discountInfo['percent3'] / 100;
            }
            $laborTotal = $mechanicPay * $labor;
            $total = $laborTotal + $partsTotal;
            $total = $total - ($total * $discount);
            $vat = $total * .20;
            $grandTotal = $total + $vat;
            //update database on the total
            $insertTotalQuery = "UPDATE Job set total_cost = CAST('$grandTotal' AS DECIMAL(10,2))WHERE Job_id = '$id'";
            mysqli_query($conn, $insertTotalQuery);

            return number_format($grandTotal, 2, ".", ",");
        }
        //a flexible discount applied
        else if ($discountInfo['type'] = 'flexible') {
            for ($count = 0; $count < sizeof($partsUsed); $count++) {
                $partsTotal += $partsQuantity[$count] * $partsPrice[$count];
            }
            //find how much the customer has spent
            $spentQuery = "SELECT COUNT(total_cost) FROM Job WHERE customer_email = '$email'";
            $spentQueryResults = mysqli_query($count, $spentQuery);
            $spent = $spentQueryResults ->fetch_assoc();
            if ($spent < 1000) {
                $discount = $discountInfo['percent1']/100;
            }
            else if ($spent < 5000) {
                $discount = $discountInfo['percent2']/100;
            }
            else if ($spent > 5000) {
                $discount = $discountInfo['percent3']/100;
            }
            $laborTotal = $mechanicPay * $labor;
            $total = ($laborTotal + $partsTotal);
            $total = $total * $discount;
            $vat = $total * .20;
            $grandTotal = $total + $vat;
            //update database on the total
            $insertTotalQuery = "UPDATE Job set total_cost = CAST('$grandTotal' AS DECIMAL(10,2))WHERE Job_id = '$id'";
            mysqli_query($conn, $insertTotalQuery);

            return number_format($grandTotal, 2, ".", ",");
        }
        else return 0;
    }

    if ($type['type'] == 'Franchisee') {
        echo '
            <button class="btn btn-primary completed-jobs-button" onclick=monthlyReportForm()>View Monthly Report</button>
            <form method="post" action="jobReport.php">
            <button class="btn btn-primary " type="submit">View Time and Price Report</button> </form>
        ';
    }

    //Fills out the table with all the relevant data of completed jobs that are not yet paid
    echo
    '
        <table id="job_table" class="display">
        <caption>Completed Jobs</caption>
        <thead>
        <tr class="bg-dark text-white">
            <th>Edit</th>
            <th>Invoice</th>
            <th>Job Id</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Vehicle Make</th>
            <th>Vehicle Model</th>
            <th>Vehicle Color</th>
            <th>Vehicle Registration</th>
            <th>Required Work</th>
            <th>Completed Work</th>
            <th>Job Type</th>
            <th>Bay No.</th>
            <th>Mechanic</th>
            <th>Parts</th>
            <th>Total</th>
            <th>Payment Due</th>
        </tr>
    </thead>
    <tbody>';
    if($JobsQueryResults->num_rows > 0) {
        while ($row = $JobsQueryResults->fetch_assoc()) {
            echo
                "<tr><td><button class='btn btn-primary' onclick=openJobForm(this)>Edit</button></td>  
            <td><form method='post' action='invoice.php'>
            <input id ='id' type='hidden' name='id'value=" . $row['Job_id'] . ">
            <button class='btn btn-secondary' type='submit'>Invoice</button></form></td>
            <td class='jobId'>" . $row['Job_id'] . "</td>
            <td class='name'>" . $row['customer_name'] . "</td>
            <td class='email'>" . $row['customer_email'] . "</td>
            <td class='make'>" . $row['vehicle_make'] . " </td> 
            <td class='model'>" . $row['vehicle_model'] . "</td>
            <td class='color'>" . $row['vehicle_color'] . " </td> 
            <td class='registration'>" . $row['vehicle_registration'] . "</td>
            <td class='reqWork'>" . $row['required_work'] . "</td>
            <td class='compWork'>" . $row['completed_work'] . "</td>
            <td class='type'>" . $row['jobType'] . "</td>
            <td class='bayNo'>" . $row['BayNo'] . "</td>
            <td class='mechanic'>" . $row['Mechanic'] . "</td>
            <td><form method='post' action='partsList.php'>
            <input id = 'id' type='hidden' name='id' value=" . $row['Job_id'] . ">
            <button class='btn btn-info' type='submit'>Parts List</button> </form></td>
            <td>".calculateTotal($row['Job_id'],$row['customer_email'],$conn)."</td>
            <td class='paymentDue'>" .$row['payment_due']. "</td></tr>";
            //This button brings you to a parts list page for whichever job row was selected
        }
    }
    else {
        echo '0 results';
    }

    echo
    "</tbody>
             </table>
             <script type='text/javascript'>
            $(document).ready(
                function() {
                    $('#job_table').DataTable();
                });
            </script>";

    closeCon($conn);
    ?>
    <!--
    This is the a pop up form to easily edit a job
    -->
    <div class="form-popup" id="editJob" role="document">
        <form method="post" action="editJobSql.php" class="form-container row">
            <h1>Edit Job</h1>

            <label class="form-label mt-2" for="jobId"><b>ID</b></label>
            <input id="jobId" class="form-control mt-2" type="text" name="jobId">

            <label class="form-label mt-2" for="name"><b>Customer</b></label>
            <input class="form-control mt-2" type="text" name="name" id="name">

            <label class="form-label mt-2" for="email"><b>Customer Email</b></label>
            <input class="form-control mt-2" type="text" name="email" id="email">

            <label class="form-label mt-2" for="make"><b>Vehicle Make</b></label>
            <input class="form-control mt-2" type="text" name="make" id="make">

            <label class="form-label mt-2" for="model"><b>Vehicle Model</b></label>
            <input class="form-control mt-2" type="text" name="model" id="model">

            <label class="form-label mt-2" for="color"><b>Vehicle Color</b></label>
            <input class="form-control mt-2" type="text" name="color" id="color">

            <label class="form-label mt-2" for="registration"><b>Vehicle Registration</b></label>
            <input class="form-control mt-2" type="text" name="registration" id="registration">

            <label class="form-label mt-2" for="reqWork"><b>Required Work</b></label>
            <textarea class="form-control mt-2" maxlength="6" rows="6" cols="30" name="reqWork" id="reqWork"></textarea>

            <label class="form-label mt-2" for="compWork"><b>Completed Work</b></label>
            <textarea class="form-control mt-2" maxlength="6" rows="6" cols="30" name="compWork" id="compWork"></textarea>

            <label class="form-label mt-2" for="jobStatus"><b>jobStatus</b></label>
            <select id="jobStatus" name="jobStatus">
                <option value="none">Select One</option>
                <option value="booked">Booked</option>
                <option value="unbooked">Unbooked</option>
                <option value="complete">Complete</option>
            </select><br>

            <label class="form-label mt-2" for="type"><b>Job Type</b></label>
            <select id="type" name="type">
                <option value="none">Select One</option>
                <option value="repair">Repair</option>
                <option value="annual">Annual</option>
                <option value="mot">MOT</option>
            </select><br>

            <label class="form-label mt-2" for="bay"><b>Bay No</b></label>
            <input type="text" name="bay" id="bay">

            <label class="form-label mt-2" for="mechanic"><b>Mechanic</b></label>
            <input type="text" name="mechanic" id="mechanic">

            <button type="submit" class="btn">Save</button>
            <button type="button" class="btn cancel" onclick="closeJobForm()">Cancel</button>
        </form>
    </div>

    <div class="form-popup" id="report" role="document">
        <form method="post" action="monthlyReport.php" class="form-container">

            <h2>What month would you like to view the report for?</h2>
            <input type="month" id="month" name="month" min="2000-01" max="2050-01">

            <button type="submit" class="btn">Ok</button>
            <button type="button" class="btn cancel" onclick="closeMonthlyReportForm()">Cancel</button>
        </form>
    </div>

</main>
<footer>

</footer>
<script>
    let tableRowElement;

    function toggleJobModal(element) {

        tableRowElement = element.parentElement.parentElement;

        //Get the data from the html table
        const jobId = tableRowElement.getElementsByClassName('jobId')[0].innerHTML;
        const name = tableRowElement.getElementsByClassName('name')[0].innerHTML;
        const email = tableRowElement.getElementsByClassName('email')[0].innerHTML;
        const make = tableRowElement.getElementsByClassName('make')[0].innerHTML;
        const model = tableRowElement.getElementsByClassName('model')[0].innerHTML;
        const color = tableRowElement.getElementsByClassName('color')[0].innerHTML;
        const registration = tableRowElement.getElementsByClassName('registration')[0].innerHTML;
        const reqWork = tableRowElement.getElementsByClassName('reqWork')[0].innerHTML;
        const compWork = tableRowElement.getElementsByClassName('compWork')[0].innerHTML;
        const jobStatus = tableRowElement.getElementsByClassName('jobStatus')[0].innerHTML;
        const type = tableRowElement.getElementsByClassName('type')[0].innerHTML;
        const bayNo = tableRowElement.getElementsByClassName('bayNo')[0].innerHTML;
        const mechanic = tableRowElement.getElementsByClassName('mechanic')[0].innerHTML;

        //Show the value that is currently saved in the popup form
        document.getElementById('jobId').value = jobId;
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('make').value = make;
        document.getElementById('model').value = model;
        document.getElementById('color').value = color;
        document.getElementById('registration').value = registration;
        document.getElementById('reqWork').value = reqWork;
        document.getElementById('compWork').value = compWork;
        document.getElementById('jobStatus').value = jobStatus;
        document.getElementById('type').value = type;
        document.getElementById('bay').value = bayNo;
        document.getElementById('mechanic').value = mechanic;

    }

    function openJobForm(element) {
        document.getElementById("editJob").style.display = "block";
        toggleJobModal(element);
    }

    function closeJobForm() {
        document.getElementById("editJob").style.display = "none";
    }

    function monthlyReportForm() {
        document.getElementById("report").style.display = "block";
    }

    function closeMonthlyReportForm() {
        document.getElementById("report").style.display = "none";
    }
</script>
</body>
</html>
