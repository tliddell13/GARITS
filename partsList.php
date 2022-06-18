<!DOCTYPE html>
<?php
/*
 * This is the parts list page where a user can edit which parts are used for a job
 * I also added the parts table on here so the user can easily search up the part ID and see more about the part
 */

include 'connection.php';

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}
?>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <style>
        <?php include 'customer.css'?>
    </style>
    <title>Job Parts List</title>
</head>
<body>
<main>

    <?php
    $conn = openCon();


    echo
    '<table id="stock_table" class="display" >
        <caption>Find Parts</caption>
        <thead>
        <tr class="bg-dark text-white">
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
    '</tbody>
             </table>
             
             
             
             '; echo "<script type='text/javascript'>
            $(document).ready(
                function() {
                    $('#stock_table').DataTable();
                });
            </script>";


$id = $_POST['id'];
$partsQuery = "SELECT * FROM Parts_List WHERE Job_id = '$id' ";
$partsQueryResults = mysqli_query($conn, $partsQuery);
echo "<h1>Parts List for Job '$id'</h1>
<form method='post' id='form' action='savePartsList.php'>";
    if($partsQueryResults->num_rows > 0) {
        $counter = $partsQueryResults->num_rows;
        while ($row = $partsQueryResults->fetch_assoc()) {
            $counter++;
            echo
            "
                      <label></label>
                      <label class='form-label mt-2' for='input$counter'><b>ID</b></label>
                      <input name='input[]' id = 'input$counter' type='text' value=" . $row['Part_id'] . ">
                      <label class='form-label mt-2' for='input$counter'><b>Quantity</b></label>
                      <input name='inputQty[]' id = 'inputQty$counter' type='text' value=" . $row['quantity'] . ">
                 ";
        }
    } else $counter = 0;
    echo "       <input name=count id='count' type='hidden' value='$counter'>
                 <input name='id' id='id' type='hidden' value='$id'>
                 <input  name='id' id='id' type='hidden' value='$id'>
                 <button class='btn btn-primary' type='button' onclick=addPart($counter)>add part</button>
                 <button class='btn btn-primary' type='button' onclick=removePart($counter)>remove part</button>
                 <button class='btn btn-primary' type='submit'>Save</button>
                 <button class='btn btn-primary' type='button' onclick='cancel()'>Cancel</button>
                 </form>";

    $conn->close();
    ?>
</main>
<footer>
</footer>
<script>
    var counter = 0;
    //function to add a part field to the form
    function addPart(count) {
        counter--;
        let form = document.getElementById("form");
        let input = document.createElement("input");
        let inputQty = document.createElement("input");
        input.id = 'input' + count;
        input.type = 'text';
        input.name = 'input[]';
        input.placeholder = 'Enter the part ID';
        inputQty.id = 'inputQty' + count;
        inputQty.type = 'text';
        inputQty.name = 'inputQty[]';
        inputQty.placeholder = 'Enter the part qty';
        form.appendChild(input);
        form.appendChild(inputQty);

    }
    //function to remove a part field from the form
    function removePart(count) {
        counter++;
        let idNode = document.getElementById('input' + (count-counter));
        let idQtyNode = document.getElementById('inputQty' + (count-counter));
        idNode.parentNode.removeChild(idNode);
        idQtyNode.parentNode.removeChild(idQtyNode);
    }
    function cancel() {
        window.location.href = "index.php";
    }
</script>
</body>
</html>



