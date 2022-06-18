<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/b-2.2.2/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
</head>
<body>
<?php

include_once "connection.php";
include "header.php";
include 'htmlHeader.php';

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}
$conn = openCon();

echo '
<div class="container">
<table id="invoiceTable" class="display">
<thead>
<tr>
<td></td>
<th>PartID</th>
<th>Quantity</th>
<th>Cost</th>
</tr>
</thead>
<tbody>
</div>
';

$partID = $_POST['input'];
$partQty = $_POST['inputQty'];
$partsCost = array();
$partsTotal = 0;

for ($count = 0; $count < sizeof((array)$partID); $count++) {
    $partsCostQuery = "SELECT * FROM Stock_Ledger WHERE part_ID = '$partID[$count]'";
    $partsCostQueryResults = mysqli_query($conn,$partsCostQuery);
    while ($row = $partsCostQueryResults->fetch_assoc()) {
        $partsCost[$count] = $row['part_price'];
    }
    $partsTotal = $partsCost[$count] + $partsTotal;
    echo'
    <tr>
    <td></td>
    <td>'.$partID[$count].'</td>
    <td>'.$partQty[$count].'</td>
    <td>'.$partsCost[$count].'</td>
    </tr>
    ';
}

echo"
    <tr>
        <th colspan='row'>Total</th>
        <td></td>
        <td></td>
        <td>$partsTotal</td>
    </tr>
</tbody>
</table>
<script type='text/javascript'>
            $(document).ready(
                function() {
                    $('#invoiceTable').DataTable( {
                        dom: 'Bftrip',
                        buttons: [
                            'print'
                        ],
                        aaSorting: []
                    });
                });
</script>
</body>
</html>
";
//Delete the parts used from the database last, or else it gets in the way
foreach ($partID as $partID) {
    foreach ((array)$partQty as $partQty) {
        //update the parts level
        mysqli_query($conn, "UPDATE Stock_Ledger SET stock_level = stock_level - '$partQty' WHERE part_ID = '$partID'");
    }
}

closeCon($conn);
?>