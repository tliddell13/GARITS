<?php
/*login here*/
include_once "connection.php";

$username = $_POST['un'];
$password = $_POST['pw'];

$EncryptedPassword=md5($password);

$sql = "SELECT * FROM Staff WHERE Username = '$username' && Password = '$EncryptedPassword'  ";
//connect to the database
$conn = openCon();

$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);



if($num==1)
{
    session_start();
    $_SESSION['name']= $_POST['un'];
    echo "You have logged in Successfuly!";
    //mysqli_close($db);
    $check = "SELECT type FROM Staff WHERE Username = '$username'";
    $result = mysqli_query($conn,$check);
    //echo $result;


    while($row = mysqli_fetch_array($result)) {
        //echo $row['type'];

        if ($row['type']=="Administrator")
            header ("location: adminHome.php");
        if ($row['type']=="StoreKeeper")
            header ("location: partsSale.php");
        if ($row['type']=="Receptionist")
            header ("location: home.php");
        if ($row['type']=="Mechanic")
            header ("location: home.php");
        if ($row['type']=="Foreperson")
            header ("location: home.php");
        if ($row['type']=="Franchisee")
            header ("location: home.php");

    }
    closeCon($conn);






    //header ("location: system.php");
}else{
    echo '
    <script>alert("unsuccessful login")
    window.location.href = "index.php"</script>';
    mysqli_close($conn);
}

?>
