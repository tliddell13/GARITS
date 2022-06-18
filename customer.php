<!DOCTYPE html>
<?php
/*
 * On this page the user can view customers and edit them, as well as edit there discount plan and whether they can pay
 * late

 */

error_reporting(E_ERROR | E_PARSE);

include_once "connection.php";
include "header.php";
include 'htmlHeader.php';

if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

$conn = openCon();
$username = $_SESSION['name'];
$getUserQuery = "SELECT type FROM Staff WHERE username = '$username'";
$typeResults = mysqli_query($conn, $getUserQuery);
$type = $typeResults->fetch_assoc();

?>
<html lang="en">
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <style>
        <?php include 'customer.css'?>
    </style>
    <title>Customers</title>
</head>
<body>
<main>
    <?php

    echo
    '
<table id="customer_table"class="display">
        <caption>Customers</caption>
        <thead>
        <tr class="bg-dark text-white">
            <th>Edit</th>';
             if ($type["type"] == "Franchisee") {
                //this part of the form is only for franchisee
            echo'<th>Edit Discount</th>';}
            echo'
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Phone</th>
            <th>Secondary Phone</th>
            <th>Address</th>
            <th>Discount Plan</th>
            <th>Pay Late</th>
        </tr>
        </thead>
        <tbody>';

    $customersQuery = "SELECT * FROM Customer";

    $customerQueryResults = mysqli_query($conn, $customersQuery);

    if($customerQueryResults->num_rows > 0) {
        while ($row = $customerQueryResults->fetch_assoc()) {
            $email = $row['email'];
            $customerDiscountQuery = "SELECT * FROM Discount WHERE customer_email = '$email'";
            $discountResults = mysqli_query($conn, $customerDiscountQuery);
            $discount = $discountResults->fetch_assoc();
            if (is_null($discount)) {
                $discount['type'] = 'none';
            }
            echo
                "<tr>
            <td><button class='btn btn-primary' onclick=openForm(this)>Edit</button></td>";
             if ($type["type"] == "Franchisee") {
                //this part of the form is only for franchisee
            echo"
            <td><button class='btn btn-primary' onclick=openDiscountForm(this)>Discount</button></td> ";}
             echo"
            <td class='id'>" . $row['id'] . "</td>
            <td class='name'>" . $row['customer_name'] . "</td>
            <td class='email'>" . $row['email'] . "</td>
            <td class='dob'>" . $row['dob'] . "</td>
            <td class='phone'>" . $row['phone_no'] . "</td>
            <td class='phone'>" . $row['homePhone'] . "</td>
            <td class='address'>" . $row['address'] . "</td>
            <td class='discount'>" . $discount['type'] . "</td>
            <td class='late'>" . $row['pay_late'] . "</td>";
        }
    }
    else {
        echo '0 results';
    }
    echo
    '</tbody>
             </table>
             <script type="text/javascript">
            $(document).ready(
                function() {
                    $("#customer_table").DataTable();
                });
            
            
            </script>
            <button class="btn btn-primary" onclick="addCustomerForm()">Add Customer</button>
    
    <div class="form-popup" id="editForm" role="document">
        <!---This form edits a customer --->
        <form onsubmit="return confirm(\'Are you sure?\')" method="post" action="editCustomerSql.php" class="form-container row">
            <h1>Edit Customer</h1>

            <label class="form-label mt-2" for="id"><b>ID</b></label>
            <input class="form-control mt-2" type="text" name="id" id="id" readonly>

            <label class="form-label mt-2" for="name"><b>Name</b></label>
            <input class="form-control mt-2" type="text" name="name" id="name">

            <label class="form-label mt-2" for="email"><b>Email</b></label>
            <input class="form-control mt-2" type="text" name="email" id="email">

            <label class="form-label mt-2" for="dob"><b>Date of Birth</b></label>
            <input class="form-control mt-2" type="text" name="dob" id="dob">

            <label class="form-label mt-2" for="phone"><b>Phone Number</b></label>
            <input class="form-control mt-2" type="text" name="phone" id="phone">
            
            <label class="form-label mt-2" for="2phone"><b>Secondary Phone</b></label>
            <input class="form-control mt-2" type="text" name="2phone" id="2phone">

            <label class="form-label mt-2" for="address"><b>Address</b></label>
            <input class="form-control mt-2" type="text" name="address" id="address">

            <br>';
            if ($type['type'] == 'Franchisee') {
                //this part of the form is only for the franchisee
            echo '
            <br>

            <label class="form-label mt-2" for="payLate"><b>Pay late</b></label>
            <select name="payLate" id="payLate">
                <option>Yes</option>
                <option selected="selected">No</option>
            </select>';}

            echo '

            <button type="submit" class="btn">Save</button>
            <button type="submit" class="btn" formaction="deleteCustomerSql.php">Delete</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
        </form>
    </div>
    <!---This form adds a new customer --->
    <div class="form-popup" id="addForm" role="document">
        <form onsubmit="return confirm(\'Are you sure?\')" method="post" action="addCustomerSql.php" class="form-container">
            <h1>Add Customer</h1>

            <label class="form-label mt-2" for="name"><b>Name</b></label>
            <input class="form-control mt-2" type="text" name="name" id="name">

            <label class="form-label mt-2" for="email"><b>Email</b></label>
            <input class="form-control mt-2" type="text" name="email" id="email">

            <label class="form-label mt-2" for="dob"><b>Date of Birth</b></label>
            <input class="form-control mt-2" type="text" name="dob" id="dob">

            <label class="form-label mt-2" for="phone"><b>Phone Number</b></label>
            <input class="form-control mt-2" type="text" name="phone" id="phone">
            
            <label class="form-label mt-2" for="2phone"><b>Secondary Phone</b></label>
            <input class="form-control mt-2" type="text" name="2phone" id="2phone">

            <label class="form-label mt-2" for="address"><b>Address</b></label>
            <input class="form-control mt-2" type="text" name="address" id="address">

            <br>';

            if ($type['type'] == 'Franchisee') {
                //this part of the form is only for franchisee
            echo '
            <br>

            <label class="form-label mt-2" for="payLate"><b>Pay late</b></label>
            <select name="payLate" id="payLate">
                <option>Yes</option>
                <option selected="selected">No</option>
            </select>';}

            echo '

            <button type="submit" class="btn">Save</button>
            <button type="button" class="btn cancel" onclick="closeAddForm()">Cancel</button>
        </form>
    </div>
    <div class="form-popup" id="discountForm" role="document">
        <!---This form edits a customer --->
        <form method="post" action="addDiscount.php" class="form-container row">
            <h1>Edit Discount</h1>

            <input name="customer" id="customer" hidden>
            <label class="form-label mt-2" for="type"><b>Type</b></label>
            <select name="type" id="type">
                <option>None</option>
                <option>Fixed</option>
                <option>Variable</option>
                <option>Flexible</option>
            </select>

        <div class="Fixed" id="Fixed">
            <label for="percent"><b>Percent</b></label>
            <input class="form-control mt-2" type="number" name="percent" id="percent">
   
        </div>
        <div class="Variable" id="Variable">
            <label for="mot"><b>Mot %</b></label>
            <input class="form-control mt-2" id="mot" type="number" name="mot">
            <label for="repair"><b>Repair %</b></label>
            <input class="form-control mt-2" id="repair" type="number" name="repair">
            <label for="annual"><b>Annual %</b></label>
            <input class="form-control mt-2" id="annual" type="number" name="annual">
        </div>
        <div class="Flexible" id="Flexible">
            <label for="range1"><b>£0 - £1000 spent</b></label>
            <input class="form-control mt-2" id="range1" type="number" name="range1" placeholder="%">
            <label for="range2"><b>£1001 - £5000 spent</b></label>
            <input class="form-control mt-2" id="range2" type="number" name="range2" placeholder="%">
            <label for="range3"><b>£5001 - £10000</b></label>
            <input class="form-control mt-2" id="range3" type="number" name="range3" placeholder="%">
        </div>

            <button type="submit" class="btn">Save</button>
            <button type="button" class="btn cancel" onclick="closeDiscountForm()">Cancel</button>
        </form>
    </div>';

     $conn->close();
    ?>
</main>
<footer>
</footer>
<script>

    $('#Flexible').hide()
    $('#Fixed').hide()
    $('#Variable').hide()

    $('#type').change(function () {
        var value = this.value;
        $('#Flexible').hide()
        $('#Fixed').hide()
        $('#Variable').hide()
        $('#' + this.value).show();
    });

    let tableRowElement;

    function toggleModal(element) {

        tableRowElement = element.parentElement.parentElement;

        //get the fields for the popup form
        const id = tableRowElement.getElementsByClassName('id')[0].innerHTML;
        const name = tableRowElement.getElementsByClassName('name')[0].innerHTML;
        const email = tableRowElement.getElementsByClassName('email')[0].innerHTML;
        const dob = tableRowElement.getElementsByClassName('dob')[0].innerHTML;
        const phone = tableRowElement.getElementsByClassName('phone')[0].innerHTML;
        const address = tableRowElement.getElementsByClassName('address')[0].innerHTML;
        const discount = tableRowElement.getElementsByClassName('discount')[0].innerHTML;
        const late = tableRowElement.getElementsByClassName('late')[0].innerHTML;

        //add the fields to the popup form
        document.getElementById('id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('dob').value = dob;
        document.getElementById('phone').value = phone;
        document.getElementById('address').value = address;
        document.getElementById('discount').value = discount;
        document.getElementById('late').value = late;

    }

    function customerDiscount(element) {
        tableRowElement = element.parentElement.parentElement;
        const email = tableRowElement.getElementsByClassName('email')[0].innerHTML;

        document.getElementById('customer').value = email;
    }

    function openForm(element) {
        document.getElementById("editForm").style.display = "block";
        toggleModal(element);
    }

    function closeForm() {
        document.getElementById("editForm").style.display = "none";
    }

    function addCustomerForm() {
        document.getElementById("addForm").style.display = "block";
    }
    function closeAddForm() {
        document.getElementById("addForm").style.display = "none";
    }

    function openDiscountForm(element) {
        document.getElementById("discountForm").style.display = "block";
        customerDiscount(element);
    }
    function closeDiscountForm() {
        document.getElementById("discountForm").style.display = "none";
    }
</script>
</body>
</html>