<?php
/*
 * the connection protocol used throughout the system
 */
function openCon()
{
    $user = 'root';
    $pass = 'root';
    $db = 'GARITS';


    $conn = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

    return $conn;
}
function closeCon($conn) {
    $conn -> close();
}



?>