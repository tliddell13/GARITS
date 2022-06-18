<!DOCTYPE html>
<?php
/*
 *this page keeps a record of all the vehicles
 */

include_once "connection.php";
include "header.php";
include 'htmlHeader.php';

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
    <title>Franchisee Home Page</title>
</head>
<body>

<main>
    <?php
    $conn = openCon();

    echo
    '<table id="vehicle_table"class="display">
        <caption>Vehicles</caption>
        <thead>
        <tr class="bg-dark text-white">
            <th>Edit</th>
            <th>Registration</th>
            <th>Customer Email</th>
            <th>Make</th>
            <th>Model</th>
            <th>Color</th>
            <th>Last MOT</th>
            <th>Last Service</th>
        </tr>
        </thead>
        <tbody>';

    $vehiclesQuery = "SELECT * FROM Vehicle_record";

    $vehiclesQueryResults = mysqli_query($conn, $vehiclesQuery);

    if($vehiclesQueryResults->num_rows > 0) {
        while ($row = $vehiclesQueryResults->fetch_assoc()) {
            echo
                "<tr>
            <td><button class='btn btn-primary' onclick=openForm(this)>Edit</button></td>  
            <td class='registration'>" . $row['reg_number'] . "</td>
            <td class='email'>" . $row['email'] . "</td>
            <td class='make'>" . $row['make'] . "</td>
            <td class='model'>" . $row['model'] . "</td>
            <td class='color'>" . $row['colour'] . "</td>
            <td class='mot'>" . $row['mot_reminder'] . "</td>
            <td class='service'>" . $row['last_service'] . "</td>";
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
                    $('#vehicle_table').DataTable();
                });
            
            
            </script>
    <button class='btn btn-primary' onclick='addVehicleForm()'>Add Vehicle</button>";

    $conn->close();
    ?>
    <div class="form-popup" id="editForm" role="document">
        <form onsubmit="return confirm('Are you sure?')" method="post" action="editVehicleSql.php" class="form-container">
            <h1>Edit Vehicle</h1>

            <label class="form-label mt-2" class="form-control mt-2" for="registration"><b>Registration</b></label>
            <input class="form-control mt-2" type="text" name="registration" id="registration" readonly>

            <label class="form-label mt-2" class="form-control mt-2" for="email"><b>Customer Email</b></label>
            <input class="form-control mt-2" type="text" name="email" id="email">

            <label class="form-label mt-2" class="form-control mt-2" for="make"><b>Make</b></label>
            <input class="form-control mt-2" type="text" name="make" id="make">

            <label class="form-label mt-2" class="form-control mt-2" for="model"><b>Model</b></label>
            <input class="form-control mt-2" type="text" name="model" id="model">

            <label class="form-label mt-2" class="form-control mt-2" for="color"><b>Color</b></label>
            <input class="form-control mt-2" type="text" name="color" id="color">

            <label class="form-label mt-2" class="form-control mt-2" for="mot"><b>Last MOT</b></label>
            <input class="form-control mt-2" type="text" name="mot" id="mot">

            <label class="form-label mt-2" class="form-control mt-2" for="service"><b>Last Service</b></label>
            <input class="form-control mt-2" type="text" name="service" id="service">

            <button type="submit" class="btn">Save</button>
            <button type="submit" class="btn" formaction="deleteVehicleSql.php">Delete</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
        </form>
    </div>
    <div class="form-popup" id="addForm" role="document">
        <form onsubmit="return confirm('Are you sure?')" method="post" action="addVehicleSql.php" class="form-container">
            <h1>Add Vehicle</h1>

            <label class="form-label mt-2" for="registration"><b>Registration</b></label>
            <input class="form-control mt-2" type="text" name="registration" id="registration">

            <label class="form-label mt-2" for="email"><b>Customer Email</b></label>
            <input class="form-control mt-2" type="text" name="email" id="email">

            <label class="form-label mt-2" for="make"><b>Make</b></label>
            <input class="form-control mt-2" type="text" name="make" id="make">

            <label class="form-label mt-2" for="model"><b>Model</b></label>
            <input class="form-control mt-2" type="text" name="model" id="model">

            <label class="form-label mt-2" for="color"><b>Color</b></label>
            <input class="form-control mt-2" type="text" name="color" id="color">

            <label class="form-label mt-2" for="mot"><b>Last MOT</b></label>
            <input class="form-control mt-2" type="text" name="mot" id="mot">

            <label class="form-label mt-2" for="service"><b>Last Service</b></label>
            <input class="form-control mt-2" type="text" name="service" id="service">

            <button type="submit" class="btn">Save</button>
            <button type="submit" class="btn" formaction="deleteVehicleSql.php">Delete</button>
            <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
        </form>
    </div>
</main>
<footer>
</footer>
<script>

    let tableRowElement;

    //fill out the edit vehicle popout form
    function toggleModal(element) {

        tableRowElement = element.parentElement.parentElement;

        const registration = tableRowElement.getElementsByClassName('registration')[0].innerHTML;
        const email = tableRowElement.getElementsByClassName('email')[0].innerHTML;
        const make = tableRowElement.getElementsByClassName('make')[0].innerHTML;
        const model = tableRowElement.getElementsByClassName('model')[0].innerHTML;
        const color = tableRowElement.getElementsByClassName('color')[0].innerHTML;
        const mot = tableRowElement.getElementsByClassName('mot')[0].innerHTML;
        const service = tableRowElement.getElementsByClassName('service')[0].innerHTML;

        document.getElementById('registration').value = registration;
        document.getElementById('email').value = email;
        document.getElementById('make').value = make;
        document.getElementById('model').value = model;
        document.getElementById('color').value = color;
        document.getElementById('mot').value = mot;
        document.getElementById('service').value = service;

    }
    function openForm(element) {
        document.getElementById("editForm").style.display = "block";
        toggleModal(element);
    }

    function closeForm() {
        document.getElementById("editForm").style.display = "none";
    }

    function addVehicleForm() {
        document.getElementById("addForm").style.display = "block";
    }
    function closeAddForm() {
        document.getElementById("addForm").style.display = "none";
    }
</script>
</body>
</html>