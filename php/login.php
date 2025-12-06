<?php
session_start();
require_once("../tools.php");

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    // Fetch user from database
    $sql = "SELECT * FROM users WHERE user = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // login success
            $_SESSION['user'] = $row['user'];
            header("Location: ../homepage.php");
            exit();
        } else {
            echo "Incorrect password!";
        }

    } else {
        echo "User not found!";
    }
}
?>
