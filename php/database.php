<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "projectdb";
    $conn = "";

    //To check if there is an error when we connect
    try{
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }
    catch(mysqli_sql_exception){
        echo"Could not connect <br>";
    }

    //For test database connection
    if($conn){
        echo"You are connected";
    }
?>