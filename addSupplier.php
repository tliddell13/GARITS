<?php
/*add a new user to the system*/
include_once("connection.php");
$con = openCon();

$Supplier = $_POST['sN'];
$Address = $_POST['sAddress'];
$Postcode = $_POST['sPostcode'];
$PhoneNumber = $_POST['sPhoneNumber'];



$sql = "INSERT INTO suppliers VALUES ('$Supplier','$Address','$Postcode','$PhoneNumber')";

$result = mysqli_query($con,$sql);

echo "
 <script>
           alert('updated successfully!')
           window.location.href = 'index.php';
      </script>;"

?>