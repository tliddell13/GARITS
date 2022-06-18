<!DOCTYPE html>
<html>
<head>
    <style>
        <?php include 'customer.css' ?>
    </style>
</head>
<?php

include 'connection.php';

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
    include 'htmlHeader.php';
    include 'header.php';
}

$conn = openCon();

$id = $_POST['id'];

//The queries needed to create the invoice
$partsQuery = "SELECT * FROM parts_list WHERE Job_id = '$id'";
$partsQueryResults = mysqli_query($conn, $partsQuery);
//some variables i need
$partsUsed = array();
$partsQuantity = array();
$partsPrice = array();
$partsName = array();
//loop through and put the needed values in arrays
if($partsQueryResults->num_rows > 0) {
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
    if($partPriceQueryResults->num_rows > 0) {
        while ($row = $partPriceQueryResults->fetch_assoc()) {
            $partsName[$count] = $row['part_name'];
            $partsPrice[$count] = $row['part_price'];
        }
    }
}

$mechanicName = $InfoRow['Mechanic'];

$email = $InfoRow['customer_email'];

$mechanicQuery = "SELECT * FROM Staff WHERE employee_name = '$mechanicName'";
$mechanicQueryResults = mysqli_query($conn, $mechanicQuery);
$mechanicInfo = $mechanicQueryResults->fetch_assoc();

$discountQuery = "SELECT * FROM Discount WHERE customer_email = '$email'";
$discountQueryResults = mysqli_query($conn, $discountQuery) or die(mysqli_error($conn));
$discountInfo = $discountQueryResults->fetch_assoc();


//Alllll the variables i need
$mechanicPay = $mechanicInfo['pay_rate'];
$customerName = $InfoRow['customer_name'];
$make = $InfoRow['vehicle_make'];
$model = $InfoRow['vehicle_model'];
$color = $InfoRow['vehicle_color'];
$vehicleRegistration = $InfoRow['vehicle_registration'];
$completedWork = $InfoRow['completed_work'];
$labor = $InfoRow['hours_worked'];
//calculating the parts total
$partsTotal = 0;
for ($count = 0; $count < sizeof($partsUsed); $count++) {
    $partsTotal += $partsQuantity[$count] * $partsPrice[$count];
}
if (is_null($discountInfo)) {
    $laborTotal = $mechanicPay * $labor;
    $total = $laborTotal + $partsTotal;
    $vat = $total * .20;
    $grandTotal = $total + $vat;
}

else if ($discountInfo['type'] = 'fixed') {
    $laborTotal = $mechanicPay * $labor;
    $total = $laborTotal + $partsTotal;
    $total = $total - ($total * ($discountInfo['percent'] / 100));
    $vat = $total * .20;
    $grandTotal = $total + $vat;
}

else if ($discountInfo['type'] = 'variable') {
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
}

else if ($discountInfo['type'] = 'flexible') {
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
}



echo
    '
<button class="btn btn-primary" onclick="window.print()">Print</button>
<div class="container mt-5 mb-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="d-flex flex-row p-2"> <p>GARITS..</p>
                    <div class="d-flex flex-column"> <span class="font-weight-bold">Invoice</span> <small>INVOICE NUMBER '.$id.'</small> </div>
                </div>
                <hr>
                <div class="table-responsive p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="add">
                                <td>Dear <strong>'.$customerName.'</strong></td>
                                
                            </tr>
                            <tr class="content">
                                <td class="font-weight-bold">Vehicle Registration: <br>Make: <br>Model: <br>Parts: </td>
                                <td class="font-weight-bold"> '.$vehicleRegistration.' <br>'.$make.' <br> '.$model.'<br> Parts </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Part No.</th>
                        <th>Unit Cost</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                    </tr>
                    </thead>
                        
                    </table>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                    <tbody>';
for ($count = 0; $count < sizeof($partsUsed); $count++) {
    echo                       '<tr><td>'.$partsName[$count].'</td>
                              <td>'.$partsUsed[$count].'</td>
                              <td>'.$partsPrice[$count].'</td>
                              <td>'.$partsQuantity[$count].'</td>
                              <td>'.$partsPrice[$count] * $partsQuantity[$count].'</td></tr>';}
echo
    "<tr><td>Labour</td>
                <td>          </td>
                <td>".$mechanicPay."</td>
                <td>".$labor."</td>
                <td>".$laborTotal."</td></tr>
                
                <tr><td>Total</td>
                <td>          </td>
                <td></td>
                <td></td>
                <td>".$total."</td></tr>
                
                <tr><td>VAT</td>
                <td>          </td>
                <td></td>
                <td></td>
                <td>".$vat."</td></tr>
                
                <tr><td>Grand Total</td>
                <td>          </td>
                <td></td>
                <td></td>
                <td>".$grandTotal."</td></tr>
                </tbody>
                </body>
                    </table>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
</html>
";


?>

