<?php
session_start();
require_once("tools.php");

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        echo "Please enter a username";
    } elseif (empty($password)) {
        echo "Please enter a password";
    } 

    $query = $conn->prepare("SELECT * FROM user WHERE Name = ?");
    $query->execute([$username]);
    $user = $query->fetch();

    if (!$user) {
        exit("Username does not exist");
    }

    if ($password !== $user["password"]) {
        exit("Incorrect password");
    }

    $_SESSION["user"] = $user["username"];


    header("Location: ../index.html");
}
?>