<?php
require_once("tools.php");

$conn = Database::connect();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($username)){
            echo "Please enter the UserName";
        }
        if(empty($password)){
            echo "Please enter the Password";
        }

        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user, password)
                    VALUES ('$username', '$hash')";
            try{
                mysqli_query($conn, $sql);
                echo "You are now registered!";
            }catch(mysqli_sql_exception){
                echo " This name already in the database";
            }
            
        }
    }
?>