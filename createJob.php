<!DOCTYPE html>
<?php

/*This is the page where you can create a new job*/
include_once "connection.php";
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}
include "header.php";
include 'htmlHeader.php';
?>
<html lang="en">
<head>
    <style>
        <?php include 'customer.css'?>
    </style>
    <title>Add Job</title>
</head>
<body>
<main>
    <div class="container bg-light text-dark">
    <form method="post" action="createJobSql.php">
        <h1 style="text-align: center;">New Job</h1>
        <div class="row">

            <label class="form-label mt-2" for="name">Full name</label><br>
            <input class="form-control mt-2" id="name" type="text" placeholder="Enter full name" name="name" maxlength="20"><br>

            <label class="form-label mt-2" class="form-control mt-2" for="email">Email</label><br>
            <input class="form-control mt-2" id="email" type=email size="40" name="email" placeholder="Enter an email" maxlength="40"><br>


            <label class="form-label mt-2" class="form-control mt-2" for="make">Vehicle Make</label><br>
            <input class="form-control mt-2" id="make" type="text" placeholder="Enter Vehicle Make" name="make" maxlength="20"><br>

            <label class="form-label mt-2" class="form-control mt-2" for="model">Vehicle Model</label><br>
            <input class="form-control mt-2" id="model" type="text" placeholder="Enter Vehicle Model" name="model" maxlength="20"><br>

            <label class="form-label mt-2" class="form-control mt-2" for="color">Vehicle Color</label><br>
            <input class="form-control mt-2" id="color" type="text" placeholder="Enter Vehicle Color" name="color" maxlength="20"><br>

            <label class="form-label mt-2" class="form-control mt-2" for="registration">Vehicle Registration</label><br>
            <input class="form-control mt-2" id="registration" type="text" placeholder="Enter Vehicle Color" name="registration" maxlength="20"><br>

            <label class="form-label mt-2" class="form-control mt-2" for="work">Required Work</label><br>
            <textarea class="form-control" id="work" name="work" maxlength="600" rows="6" cols="30">Enter the required work</textarea><br>
            <label class="form-label mt-2" class="form-control mt-2" for="type">Job Type</label><br>
            <select id="type" name="type">
                <option value="">Select One</option>
                <option value="repair">Repair</option>
                <option value="annual">Annual</option>
                <option value="mot">MOT</option>
            </select><br><br>

            <button class="btn btn-primary"type="submit" value="submit">Create</button>
        </div>
    </form>
    </div>
</main>
<footer>
</footer>
<script></script>
</body>
</html>