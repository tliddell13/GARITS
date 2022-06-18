<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <style>
        <?php 
        include 'customer.css';
        ?>
    </style>
</head>
<body>

<?php

include_once "connection.php";
include "header.php";
include 'htmlHeader.php';

$conn = openCon();

$averageTimeAllQuery = "SELECT AVG(hours_worked) as avg FROM Job";
$averageTimeMOTQuery = "SELECT AVG(hours_worked) as avg FROM Job WHERE jobType = 'mot'";
$averageTimeRepairQuery = "SELECT AVG(hours_worked) as avg FROM Job WHERE jobType = 'repair'";
$averageTimeAnnualQuery = "SELECT AVG(hours_worked) as avg FROM Job WHERE jobType = 'annual'";

$averagePriceAllQuery = "SELECT CAST(AVG(hours_worked) AS DECIMAL(10,2)) as avg FROM Job";
$averagePriceMOTQuery = "SELECT CAST(AVG(hours_worked) AS DECIMAL(10,2)) as avg FROM Job WHERE jobType = 'mot'";
$averagePriceRepairQuery = "SELECT CAST(AVG(hours_worked) AS DECIMAL(10,2)) as avg FROM Job WHERE jobType = 'repair'";
$averagePriceAnnualQuery = "SELECT CAST(AVG(hours_worked) AS DECIMAL(10,2)) as avg FROM Job WHERE jobType = 'annual'";

$averageTimeAllResults = mysqli_query($conn,$averageTimeAllQuery);
$averageTimeMOTResults = mysqli_query($conn,$averageTimeMOTQuery);
$averageTimeRepairResults = mysqli_query($conn,$averageTimeRepairQuery);
$averageTimeAnnualResults = mysqli_query($conn,$averageTimeAnnualQuery);

$averagePriceAllResults = mysqli_query($conn,$averagePriceAllQuery);
$averagePriceMOTResults = mysqli_query($conn,$averagePriceMOTQuery);
$averagePriceRepairResults = mysqli_query($conn,$averagePriceRepairQuery);
$averagePriceAnnualResults = mysqli_query($conn,$averagePriceAnnualQuery);

$avgTimeAll = $averageTimeAllResults->fetch_assoc();
$avgTimeMOT = $averageTimeMOTResults->fetch_assoc();
$avgTimeRepair = $averageTimeRepairResults->fetch_assoc();
$avgTimeAnnual = $averageTimeAnnualResults->fetch_assoc();

$avgPriceAll = $averagePriceAllResults->fetch_assoc();
$avgPriceMOT = $averagePriceMOTResults->fetch_assoc();
$avgPriceRepair = $averagePriceRepairResults->fetch_assoc();
$avgPriceAnnual = $averagePriceAnnualResults->fetch_assoc();

echo'
<div class="container mt-5 mb-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
<h1>Average Price and Time Report</h1>
<table id="jobReport_table" class="display">
    <thead>
    <tr>
        <td></td>
        <th scope="col">Time</th>
        <th scope="col">Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">Mot</th>
        <td>'.$avgTimeMOT['avg'].'</td>
        <td>'.$avgPriceMOT['avg'].'</td>
    </tr>
    <tr>
        <th scope="row">Annual</th>
        <td>'.$avgTimeAnnual['avg'].'</td>
        <td>'.$avgPriceAnnual['avg'].'</td>
    </tr>
    <tr>
        <th scope="row">Repair</th>
        <td>'.$avgTimeRepair['avg'].'</td>
        <td>'.$avgPriceRepair['avg'].'</td>
    </tr>
    <tr>
        <th scope="row">Any</th>
        <td>'.$avgTimeAll['avg'].'</td>
        <td>'.$avgPriceAll['avg'].'</td>
    </tr>
    </tbody>
</table>
</div>
</div>
</div>
</div>
<script type="text/javascript">
            $(document).ready(
                function() {
                    $("#jobReport_table").DataTable( {
                        searching: false,
                        dom: "Bftrip",
                        buttons: [
                            "print"
                        ],
                        aaSorting: []
                    });
                });
            </script>
</body>';
closeCon($conn);
    ?>
</html>
