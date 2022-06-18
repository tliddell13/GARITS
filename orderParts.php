<?php
include_once 'connection.php';
if(!isset($_SESSION)) {
    session_start(); // start the session if it still does not exist
}
$conn = openCon();
$page_title = 'Order Parts';
include 'htmlHeader.php';
include 'header.php';
?>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        <?php include 'admin.css'?>
    </style>
</head>
    <body>
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
        Add Supplier
    </button>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="addSupplier.php" method="POST">
                        Supplier: <input type="text" name="sN"><br>
                        Address: <input type="text" name="sAddress"><br>
                        Postcode: <input type="text" name="sPostcode"><br>
                        Phone Number: <input type="text" name="sPhoneNumber"><br>



                        <input type="submit">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1>Order Parts</h1>
                    </div>
                    <div class="bg-light p-3">
                        <form class="form-card" action="orderPartsSql.php" method="post">
                                <label for="supplier">Supplier</label>
                            <select id="supplier" name="inputSupplier">

                                <?php

                                $result = mysqli_query($conn, "SELECT Supplier FROM suppliers");


                                while ($row = $result->fetch_assoc()){
                                    echo '<option value="'.$row['Supplier'].'">'.$row['Supplier'].'</option>';

                                }
                                ?>
                            </select>
                            <h3 style="text-align: center;">Parts details</h3>

                            <div class="form-group">
                                <label for="partNumber">Number of the part </label>
                                <input class="form-control mt-2" type="text" class="form-control" id="partNumber" name="partNumber" />
                            </div>
                            <div class="form-group">
                                <label for="partDescription">Description of the part </label>
                                <input class="form-control mt-2" type="text" class="form-control" id="partDescription" name="partDescription" />
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity </label>
                                <input class="form-control mt-2" type="text" class="form-control" id="quantity" name="quantity" />
                            </div>
                            <h3 style="text-align: center;">Complete Order</h3>
                            <div class="form-group">
                                <label for="signature">Signature </label>
                                <input class="form-control mt-2" type="text" class="form-control" id="signature" name="signature" />
                            </div>
                            <div class="form-group">
                                <label class="form-label mt-2" for="urgency">Delivery: </label>
                                <div>
                                    <label class="form-label mt-2" for="urgent" class="radio-inline"><input type="radio" name="urgency" id="urgent" name="urgent" />Urgent</label>
                                    <label class="form-label mt-2" for="whenever" class="radio-inline"><input type="radio" name="urgency" id="urgent" name="whenever" />Whenever it's possible</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label mt-2" for="date">Date </label>
                                <input class="form-control mt-2" type="date" class="form-control" id="date" name="date" />
                            </div>
                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
