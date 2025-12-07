<?php
session_start();
require_once("../tools.php");

$conn = Database::connect();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password= filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)){
        echo"Please enter a username";
    }
    elseif (empty($password)){
        echo"Please enter a password";
    }
}
?>
/*if (isset($_POST["login"])) {

    if (!empty($_POST["username"] && $_POST["password"])) {
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["password"] = $_POST["password"];

        header("Location: index.html");
    }
    else{
        echo"Missing usename/password ";
    }
}*/
?>