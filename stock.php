<!DOCTYPE html>
<?php
/*This is the stock report page where
the user of the system can view the current stock
The user can easily print it off as a report
*/
error_reporting(E_ERROR | E_PARSE);
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

include_once "connection.php";
include 'htmlHeader.php';
$conn = openCon();
$username = $_SESSION['name'];
$getUserQuery = "SELECT type FROM Staff WHERE username = '$username'";
$typeResults = mysqli_query($conn, $getUserQuery);
$type = $typeResults->fetch_assoc();

//the mechanic is the only user that needs a different header
if ($type['type'] == 'Mechanic') {
    include 'mechanicHeader.php';
}
else
    include 'header.php';
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
        <?php include 'admin.css' ?>
    </style>
    <title>Parts</title>
</head>
<body>
<main>

    <?php

//popup form to add a supplier
    echo
    '

<table id="stock_table" class="display" >
        <caption>Parts List</caption>
        <thead>
        <tr class="bg-dark text-white">
            <th>Edit</th>
            <th>Part Id</th>
            <th>Name</th>
            <th>Price</th>
            <th>Brand</th>
            <th>Quantity</th>
            <th>Threshold</th>
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
                "
            <tr>
            <td><button class='btn btn-primary' onclick=editPartForm(this)>Edit</button></td>
            <td class='id'>" . $row['part_ID'] . "</td>
            <td class='name'>" . $row['part_name'] . "</td>
            <td class='price'>" . $row['part_price'] . "</td>
            <td class='man'>" . $row['part_manufacturer'] . "</td>
            <td class='quantity'>" . $row['stock_level'] . "</td>
            <td class='threshold'>" . $row['threshold'] . "</td>
            <td class='type'>" . $row['vehicle_type'] . "</td>
            <td class='year'>" . $row['year'] . "</td></tr>";
        }
    }
    else {
        echo '0 results';
    }

    echo
    '</tbody>
             </table>
             <button onclick="addPartForm()">Add New Part</button>
             <script type="text/javascript">
            $(document).ready(
                function() {
                    $("#stock_table").DataTable( {
                        dom: "Bftrip",
                        buttons: [
                            "print"
                        ]
                    });
                });
            </script>
       <div class="form-popup" id="editForm" role="document">
        <form onsubmit="return confirm(\'Are you sure?\')" method="post" action="editPartSql.php" class="form-container row">
            <h1>Edit Part</h1>

            <label class="form-label mt-2" for="id" readonly><b>Part_ID</b></label>
            <input class="form-control mt-2" type="text" name="id" id="id" readonly>

            <label class="form-label mt-2" for="name"><b>Name</b></label>
            <input class="form-control mt-2" type="text" name="name" id="name" readonly>

            <label class="form-label mt-2" for="price"><b>Price</b></label>
            <input class="form-control mt-2" type="text" name="price" id="price" readonly>

            <label class="form-label mt-2" for="man"><b>Manufacturer</b></label>
            <input class="form-control mt-2" type="text" name="man" id="man" readonly>

            <label class="form-label mt-2" for="quantity"><b>Quantity</b></label>
            <input class="form-control mt-2" type="number" name="quantity" id="quantity">
            
            <label class="form-label mt-2" for="type"><b>Vehicle Type</b></label>
            <input class="form-control mt-2" type="text" name="type" id="type">
            
            <label class="form-label mt-2" for="threshold"><b>Order Threshold</b></label>
            <input class="form-control mt-2" type="number" name="threshold" id="threshold">
            
            <label class="form-label mt-2" for="year"><b>Year</b></label>
            <input class="form-control mt-2" type="text" name="year" id="year">

            <button class="btn btn-secondary" type="submit" class="btn">Save</button>
            <button type="submit" class="btn" formaction="deletePartSql.php">Delete</button>
            <button type="button" class="btn cancel" onclick="closePartEditForm()">Cancel</button>
        </form> 
        </div>        
            
            
      <div class="form-popup" id="addForm" role="document">
        <form onsubmit="return confirm(\'Are you sure?\')" method="post" action="addPartSql.php" class="form-container">
            <h1>Add New Part</h1>

            <label class="form-label mt-2" for="id"><b>Part_ID</b></label>
            <input class="form-control mt-2" type="text" name="id" id="id">

            <label class="form-label mt-2" for="name"><b>Name</b></label>
            <input class="form-control mt-2" type="text" name="name" id="name">

            <label class="form-label mt-2" for="price"><b>Price</b></label>
            <input class="form-control mt-2" type="text" name="price" id="price">

            <label class="form-label mt-2" for="man"><b>Manufacturer</b></label>
            <input class="form-control mt-2" type="text" name="man" id="man">

            <label class="form-label mt-2" for="quantity"><b>Quantity</b></label>
            <input class="form-control mt-2" type="text" name="quantity" id="quantity">
            
            <label class="form-label mt-2" for="type"><b>Vehicle Type</b></label>
            <input class="form-control mt-2" type="text" name="type" id="type">
            
            <label class="form-label mt-2" for="year"><b>Year</b></label>
            <input class="form-control mt-2" type="text" name="year" id="year">
            
            <label class="form-label mt-2" for="threshold"><b>Order Threshold</b></label>
            <input class="form-control mt-2" type="text" name="threshold" id="threshold">


            <button class="btn btn-secondary" type="submit" class="btn">Save</button>
            <button type="button" class="btn cancel" onclick="closePartForm()">Cancel</button>
        </form>  
        </div> 
        
            ';

    $conn->close();


    ?>
</main>
<footer>
</footer>
<script>
    let tableRowElement;

    //this function fills out the popout form with the values that already exist
    function toggleModal(element) {

        tableRowElement = element.parentElement.parentElement;

        const id = tableRowElement.getElementsByClassName('id')[0].innerHTML;
        const name = tableRowElement.getElementsByClassName('name')[0].innerHTML;
        const price = tableRowElement.getElementsByClassName('price')[0].innerHTML;
        const man = tableRowElement.getElementsByClassName('man')[0].innerHTML;
        const quantity = tableRowElement.getElementsByClassName('quantity')[0].innerHTML;
        const threshold = tableRowElement.getElementsByClassName('threshold')[0].innerHTML;
        const type = tableRowElement.getElementsByClassName('type')[0].innerHTML;
        const year = tableRowElement.getElementsByClassName('year')[0].innerHTML;

        document.getElementById('id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('price').value = price;
        document.getElementById('man').value = man;
        document.getElementById('quantity').value = quantity;
        document.getElementById('threshold').value = threshold;
        document.getElementById('type').value = type;
        document.getElementById('year').value = year;

    }
    function addPartForm() {
        document.getElementById("addForm").style.display = "block";
    }

    function closePartForm() {
        document.getElementById("addForm").style.display = "none";
    }

    function editPartForm(element) {
        document.getElementById("editForm").style.display = "block";
        toggleModal(element)
    }
    function closePartEditForm() {
        document.getElementById("editForm").style.display = "none";
    }
</script>
</body>
</html>