<?php
include_once("connection.php");
session_start();
$conn = openCon();

if(empty($_SESSION))
{
    header("Location: index.php");
}



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="admin.css">

</head>




<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <h6 class="navbar-brand ">Welcome:</h6>
    <?php

    echo "<h6 class='navbar-brand'>", $_SESSION['name'];
    echo "<h6>";

    ?>


    <div id="navbarSuapportedContent-4">
        <ul class="navbar-nav ml-auto ">
            <li>

                <a href="logout.php"  class="btn btn-danger">Logout</a>

            </li>


        </ul>
    </div>


</nav>

<body>

<h3 class="font-weight-bold shadow-sm p-3 mb-5 bg-body rounded nav justify-content-center bg-primary text-white">Admin Page</h3>

<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-5">
                        <h2 class="text-white"><b>Staff</b></h2>
                    </div>
                    <div class="col-sm-7">
                        <a type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"><span>➕ Add New User</span></a>

                        <a type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModa2"><span>➖ Delete User</span></a>

                        <a type="button" class="btn btn-secondary" data-toggle="modal" data-target=".bd-example-modal-lg"><span>⚠️ Edit User</span></a>


                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>

                    <th>Username</th>
                    <th>type</th>

                </tr>
                </thead>
                <tbody>







                <?php

                $AllStaff = "SELECT Username,type FROM Staff";

                $result = mysqli_query($conn,$AllStaff);
                $num = mysqli_num_rows($result);



                if($result != false)
                {
                    while($rows = mysqli_fetch_array($result))
                    {
                        echo "
        <tr>
            <td>".$rows["Username"]."</td>
            <td>".$rows["type"]."</td>
			
            
        </tr>
        ";
                    }
                }
                else
                {
                    echo "
        <tr>
        <td colspan='3'>Something went wrong with the query</td>
        </tr>
    ";
                }

                ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
</table>







<div class="modal fade" id="exampleModal" tabindex="-1" type="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" type="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="register.php" method="POST">
                    Username: <input type="text" name="un"><br>
                    Password: <input type="password" name="pw"><br>

                    <label for="type">type:</label>
                    <br>
                    <select  class="w-75 p-2 form-select" id="type" name="type">
                        <option value="Receptionist">Receptionist</option>
                        <option value="Mechanic">Mechanic</option>
                        <option value="Foreperson">Foreperson</option>
                        <option value="StoreKeeper">StoreKeeper</option>
                        <option value="Franchisee">Franchisee</option>
                        <option value="Admin">Admin</option>
                    </select>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="exampleModa2" tabindex="-1" type="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" type="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="deleteUser.php" method="POST">

                    Select User: <br><select class="w-75 p-2 form-select" name="deleteUser">

                        <?php

                        $result = mysqli_query($conn, "SELECT Username FROM Staff");


                        while ($row = $result->fetch_assoc()){
                            echo '<option value="'.$row['Username'].'">'.$row['Username'].'</option>';

                        }
                        ?>
                    </select>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Remove User</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade bd-example-modal-lg" tabindex="-1" type="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <h6>Change Username:</h6>


                            <form action="changeusername.php" method="POST">

                                <select class="w-75 p-2 form-select" name="changeUsername">

                                    <?php

                                    $result = mysqli_query($conn, "SELECT Username FROM Staff");


                                    while ($row = $result->fetch_assoc()){
                                        echo '<option value="'.$row['Username'].'">'.$row['Username'].'</option>';

                                    }
                                    ?>
                                </select><br>
                                <b>Change to:</b> <input type="text" name="nun"><br>

                                <input class="p-3" value="Change UserName" type="submit">
                            </form>
                        </div>
                        <div class="col-sm">

                            <h6>Change Password:</h6>
                            <form action="changepassword.php" method="POST">

                                <select class="w-75 p-2 form-select" name="currentUser">

                                    <?php

                                    $result = mysqli_query($conn, "SELECT Username FROM Staff");


                                    while ($row = $result->fetch_assoc()){
                                        echo '<option value="'.$row['Username'].'">'.$row['Username'].'</option>';

                                    }
                                    ?>
                                </select><br>
                                <b>New Password:</b> <input type="password" name="npass"><br>

                                <input class="p-3" value="Change Password" type="submit">
                            </form>
                        </div>
                        <div class="col-sm">

                            <h6>Update type</h6>

                            <form action="changetype.php" method="POST">

                                <select class="w-75 p-2 form-select" name="currentUser">

                                    <?php

                                    $result = mysqli_query($conn, "SELECT Username FROM Staff");


                                    while ($row = $result->fetch_assoc()){
                                        echo '<option value="'.$row['Username'].'">'.$row['Username'].'</option>';

                                    }
                                    ?>
                                </select ><br>
                                <label for="newtype"><b>Update type to:</b></label>
                                <select class="w-75 p-3 form-select" id="newtype" name="newtype">
                                    <option value="Receptionist">Receptionist</option>
                                    <option value="Mechanic">Mechanic</option>
                                    <option value="Foreperson">Foreperson</option>
                                    <option value="StoreKeeper">StoreKeeper</option>
                                    <option value="Franchisee">Franchisee</option>
                                    <option value="Admin">Admin</option>
                                </select>


                                <input class="p-3 m-2" value="Update type" type="submit">
                            </form>
                        </div>
                    </div>
                </div>




                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>



</html>