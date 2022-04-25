<?php
    $servername = "localhost";
    $username = "id18837197_cra";
    $password = "Garvoberoi5@";
    $database = "id18837197_carrentalsystem";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn){
        die('Error: Cannot connect to database');
    }
?>