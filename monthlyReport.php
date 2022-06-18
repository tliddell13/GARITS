<!--- The monthly report page after the franchisee selects a month --->

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <style>
        <?php include 'customer.css'?>
    </style>
</head>
<body>

<?php

include_once "connection.php";
include "header.php";
include 'htmlHeader.php';

$conn = openCon();
$month = $_POST['month'];
$month = date("Y-m-d");


//different queries for job type and one for the total combined with whether they are an account holder
$monthlyTotalQueryAccount = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND (EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";
$monthlyTotalQueryAll = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month')";
$monthlyTotalQueryCasual = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND (NOT EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";

$monthlyMotQueryAll = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'mot'";
$monthlyMotQueryAccount = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'mot' AND (EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";
$monthlyMotQueryCasual = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'mot' AND (NOT EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";

$monthlyRepairQueryAll = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'repair'";
$monthlyRepairQueryAccount = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'repair' AND (EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";
$monthlyRepairQueryCasual = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'repair' AND (NOT EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";

$monthlyAnnualQueryAll = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'annual'";
$monthlyAnnualQueryAccount = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'annual' AND (EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";
$monthlyAnnualQueryCasual = "SELECT COUNT(*) as total FROM Job WHERE MONTH(date_completed) = MONTH('$month') AND jobType = 'annual' AND (NOT EXISTS(SELECT 1 FROM Customer WHERE Job.customer_email = Customer.email))";


//make the queries.... so many
$monthlyTotalQueryAllResults = mysqli_query($conn, $monthlyTotalQueryAll);
$monthlyTotalQueryAccountResults = mysqli_query($conn, $monthlyTotalQueryAccount);
$monthlyTotalQueryCasualResults = mysqli_query($conn, $monthlyTotalQueryCasual);

$monthlyMotQueryAllResults = mysqli_query($conn,$monthlyMotQueryAll);
$monthlyMotQueryAccountResults = mysqli_query($conn,$monthlyMotQueryAccount);
$monthlyMotQueryCasualResults = mysqli_query($conn,$monthlyMotQueryCasual);

$monthlyRepairQueryAllResults = mysqli_query($conn,$monthlyRepairQueryAll);
$monthlyRepairQueryAccountResults = mysqli_query($conn,$monthlyRepairQueryAccount);
$monthlyRepairQueryCasualResults = mysqli_query($conn,$monthlyRepairQueryCasual);

$monthlyAnnualQueryAllResults = mysqli_query($conn,$monthlyAnnualQueryAll);
$monthlyAnnualQueryAccountResults = mysqli_query($conn,$monthlyAnnualQueryAccount);
$monthlyAnnualQueryCasualResults = mysqli_query($conn,$monthlyAnnualQueryCasual);

//get the results
$motAllCount = $monthlyMotQueryAllResults ->fetch_assoc();
$motAccountCount = $monthlyMotQueryAccountResults ->fetch_assoc();
$motCasualCount = $monthlyMotQueryCasualResults ->fetch_assoc();

$repairAllCount = $monthlyRepairQueryAllResults ->fetch_assoc();
$repairAccountCount = $monthlyRepairQueryAccountResults ->fetch_assoc();
$repairCasualCount = $monthlyRepairQueryCasualResults ->fetch_assoc();

$annualAllCount = $monthlyAnnualQueryAllResults ->fetch_assoc();
$annualAccountCount = $monthlyAnnualQueryAccountResults ->fetch_assoc();
$annualCasualCount = $monthlyAnnualQueryCasualResults ->fetch_assoc();

$totalAllCount = $monthlyTotalQueryAllResults ->fetch_assoc();
$totalAccountCount = $monthlyTotalQueryAccountResults ->fetch_assoc();
$totalCasualCount = $monthlyTotalQueryCasualResults ->fetch_assoc();

//close the connection
closeCon($conn);
echo
'
<div class="container mt-5 mb-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
<h1>Monthly Report</h1>
<table id="stock_table" class="display">
<thead>
    <tr>
        <td></td>
        <th scope="col">Casual Customer</th>
        <th scope="col">Account Holder</th>
        <th scope="col">Total</th>
    </tr>
</thead>
<tbody>
    <tr>
        <th scope="row">Mot</th>
        <td>'.$motCasualCount['total'].'</td>
        <td>'.$motAccountCount['total'].'</td>
        <td>'.$motAllCount['total'].'</td>
    </tr>
    <tr>
        <th scope="row">Annual</th>
        <td>'.$annualCasualCount['total'].'</td>
        <td>'.$annualAccountCount['total'].'</td>
        <td>'.$annualAllCount['total'].'</td>
    </tr>
    <tr>
        <th scope="row">Repair</th>
        <td>'.$repairCasualCount['total'].'</td>
        <td>'.$repairAccountCount['total'].'</td>
        <td>'.$repairAllCount['total'].'</td>
    </tr>
    <tr>
        <th scope="row">Any</th>
        <td>'.$totalCasualCount['total'].'</td>
        <td>'.$totalAccountCount['total'].'</td>
        <td>'.$totalAllCount['total'].'</td>
    </tr>

</tbody>
</table>
</div>
</div>
</div>
<script type="text/javascript">
            $(document).ready(
                function() {
                    $("#stock_table").DataTable( {
                        searching: false,
                        dom: "Bftrip",
                        buttons: [
                            "print"
                        ]
                    });
                });
            </script>'
?>
</html>

