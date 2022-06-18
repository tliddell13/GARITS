<!DOCTYPE html>
<?php
/*This is the stock report page where
the user of the system can view the current stock
To do...
-implement the order parts code into it
*/

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
    include 'htmlHeader.php';
    if ($_SESSION['type'] = 'storekeeper') {
        include "storekeeperHeader.php";
    }
    else {
        include "header.php";
    }
}
?>
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
    <title>Parts Sale</title>
</head>
<body>
<main>

    <?php
    include_once "connection.php";
    $conn = openCon();


    echo
    '<table id="stock_table" class="display" >
        <caption>Find Parts</caption>
        <thead>
        <tr>
            <th>Part Id</th>
            <th>Name</th>
            <th>Price</th>
            <th>Brand</th>
            <th>Quantity</th>
            <th>Vehicle Type</th>
            <th>Year</th>
        </tr>
        </thead>
        <tbody>';
    $stockQuery = "SELECT * FROM Stock_Ledger";

    $stockQueryResults = mysqli_query($conn, $stockQuery);

    if($stockQueryResults->num_rows > 0) {
        while ($row = $stockQueryResults->fetch_assoc()) {
            echo
                "<tr><td>" . $row['part_ID'] . "</td>
            <td>" . $row['part_name'] . "</td>
            <td>" . $row['part_price'] . "</td>
            <td>" . $row['part_manufacturer'] . "</td>
            <td>" . $row['stock_level'] . "</td>
            <td>" . $row['vehicle_type'] . "</td>
            <td>" . $row['year'] . "</td></tr>";
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
                    $('#stock_table').DataTable();
                });
            </script>";



    $counter = 0;
    echo "<h1>Sell Parts</h1>
          <form method='post' id='form' action='partSaleInvoice.php'>
                 <label class='form-label mt-2' for='input[]'><b>ID</b></label>
                 <label for='inputQty[]'><b>Quantity</b></label>
                 
                 <button class='btn btn-primary' type='button' onclick=addPart()>Pick part</button>
                 <button class='btn btn-primary' type='button' onclick=removePart()>Remove part</button>
                 <button class='btn btn-primary' type='submit'>Sell</button>
             </form>";

    $conn->close();
    ?>
</main>
<footer>
</footer>
<script>
    var counter = 0;
    //function to add a part field to the form
    function addPart() {
        counter++;
        let form = document.getElementById("form");
        let input = document.createElement("input");
        let inputQty = document.createElement("input");
        input.id = 'input' + counter;
        input.type = 'text';
        input.name = 'input[]';
        input.placeholder = 'Enter the part ID';
        inputQty.id = 'inputQty' + counter;
        inputQty.type = 'text';
        inputQty.name = 'inputQty[]';
        inputQty.placeholder = 'Enter the part qty';
        form.appendChild(input);
        form.appendChild(inputQty);

    }
    //function to remove a part field from the form
    function removePart() {
        counter--;
        let idNode = document.getElementById('input' + counter);
        let idQtyNode = document.getElementById('inputQty' + counter);
        idNode.parentNode.removeChild(idNode);
        idQtyNode.parentNode.removeChild(idQtyNode);
    }
</script>
</body>
</html>

