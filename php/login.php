<?php
session_start();
require_once("tools.php");

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        exit("Please enter a username");
    } 
    if (empty($password)) {
        exit("Please enter a password");
    }

    // Correct MySQLi prepared statement
    $query = $conn->prepare("SELECT * FROM user WHERE Name = ?");
    $query->bind_param("s", $username);
    $query->execute();

    $result = $query->get_result();
    $user = $result->fetch_assoc();

        // Username wrong
    if (!$user) {
        $errors[] = "username";
    }

    // Password wrong if user exists
    if ($user && $password !== $user["Password"]) {
        $errors[] = "password";
    }

    // If username does not exist, password is automatically wrong
    if (!$user && !empty($password)) {
        $errors[] = "password";
    }

    if (!empty($errors)) {
        header("Location: ../login.html?error=" . implode(",", $errors));
        exit();
    }


    // Save session
    $_SESSION["user"] = $user["Name"];

    header("Location: ../index.html");
    exit();
}
?>
