<!DOCTYPE html>
<!---
This is the home page for the franchisee.

I used the DataTables and Jquery libraries to make the tables much easier to implement, datatables uses a stylesheet
with a default class of display, you can also use a print button they made to easily print a table.
-->
<?php
error_reporting(E_ERROR | E_PARSE);
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}

include_once "connection.php";
include 'htmlHeader.php';

$username = $_SESSION['name'];
$conn = openCon();
$getUserQuery = "SELECT type FROM Staff WHERE username = '$username'";
$typeResults = mysqli_query($conn, $getUserQuery);
$type = $typeResults->fetch_assoc();

//the mechanic is the only user that needs a different header
if ($type['type'] == 'Mechanic') {
    include 'mechanicHeader.php';
}
elseif($type['type'] == 'Franchisee') {
    include 'header.php';
    //if it is the franchisee that is logged in he sees all the important alerts
    include 'lateAlert.php';
    include 'partsAlert.php';
}
else
    include 'header.php';
?>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>

    <style>
        <?php include 'customer.css'?>
    </style>
    <title>Home</title>
</head>
<body>
<main>
    <?php
    //The query created to find which jobs have been accepted and are incomplete
    //Completed jobs is on a separate page
    $JobsQuery = "SELECT * FROM Job WHERE jobStatus != 'complete'";
    //Make the query to acquire the data
    $JobsQueryResults = mysqli_query($conn, $JobsQuery);

    //Fills out the table with all the relevant data of active jobs
    echo
    '
    <table id="job_table" class="display">
        <caption>Active Jobs</caption>
        <thead>
        <tr class="bg-dark text-white">
            <th>Edit</th>
            <th>Job Id</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Vehicle Make</th>
            <th>Vehicle Model</th>
            <th>Vehicle Color</th>
            <th>Vehicle Registration</th>
            <th>Required Work</th>
            <th>Completed Work</th>
            <th>jobStatus</th>
            <th>Job Type</th>
            <th>Bay No.</th>
            <th>Mechanic</th>
            <th>Parts</th>
        </tr>
    </thead>
    <tbody>';
    if($JobsQueryResults->num_rows > 0) {
        while ($row = $JobsQueryResults->fetch_assoc()) {
            echo
                "<tr><td><button class='btn btn-primary' onclick=openJobForm(this)>Edit</button></td>  
            <td class='jobId'>" . $row['Job_id'] . "</td>
            <td class='name'>" . $row['customer_name'] . "</td>
            <td class='email'>" . $row['customer_email'] . "</td>
            <td class='make'>" . $row['vehicle_make'] . " </td> 
            <td class='model'>" . $row['vehicle_model'] . "</td>
            <td class='color'>" . $row['vehicle_color'] . " </td> 
            <td class='registration'>" . $row['vehicle_registration'] . "</td>
            <td class='reqWork'>" . $row['required_work'] . "</td>
            <td class='compWork'>" . $row['completed_work'] . "</td>
            <td class='jobStatus'>" . $row['jobStatus'] . "</td>
            <td class='type'>" . $row['jobType'] . "</td>
            <td class='bayNo'>" . $row['BayNo'] . "</td>
            <td class='mechanic'>" . $row['Mechanic'] . "</td>
            <td><form method='post' action='partsList.php'>
            <input id = 'id' type='hidden' name='id' value=" . $row['Job_id'] . ">
            <button class='btn btn-info' type='submit'>Parts List</button> </form></td>";
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
            <div class="col-md-6">
                <label class="form-label mt-2" for="id"><b>ID</b></label>
                <input class="form-control mt-2" type="text" name="id" id="jobId" readonly>

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

                <label class="form-label" for="reqWork"><b>Required Work</b></label>
                <textarea class="form-control" maxlength="600" rows="6" cols="30" name="reqWork" id="reqWork"></textarea>

                <label class="form-label" for="compWork"><b>Completed Work</b></label>
                <textarea class="form-control"maxlength="600" rows="6" cols="30" name="compWork" id="compWork"></textarea>
            </div>

            <div class="col-md-6">


                <label class="form-label mt-2" for="jobStatus"><b>Status</b></label>
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
                <input class="form-control mt-2" type="text" name="bay" id="bay">

                <label class="form-label mt-2" for="mechanic"><b>Mechanic</b></label>
                <input class="form-control mt-2" type="text" name="mechanic" id="mechanic">

                <button type="submit" class="btn">Save</button>
                <button type="button" class="btn cancel" onclick="closeJobForm()">Cancel</button>
            </div>
        </form>
    </div>

    <div class="form-popup" id="report" role="document">
        <form method="post" action="editJobSql.php" class="form-container">
            <h1>Edit Job</h1>

            <label class="form-label mt-2" for="type"><b>Which type of report would you like to view?</b></label>
            <select id="type" name="type">
                <option value="monthly">Monthly Bookings</option>
                <option value="overall">Time & Price</option>
            </select>

            <input class="form-control mt-2" type="month" id="month" name="month" min="2000-01" max="2050-01">

            <button type="submit" class="btn">Ok</button>
            <button type="button" class="btn cancel" onclick="closeJobForm()">Cancel</button>
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
        const id = tableRowElement.getElementsByClassName('jobId')[0].innerHTML;
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
        document.getElementById('jobId').value = id;
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

</script>
</body>
</html>