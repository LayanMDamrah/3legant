<?php
session_start();
require_once("tools.php");  
$conn = Database::connect();  

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        exit("Please enter a username or email");
    }

    if (empty($password)) {
        exit("Please enter a password");
    }

    $errors = [];

    // Try searching if the name exist 
    $query = $conn->prepare("SELECT * FROM account WHERE Name = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    // If the name doesn't exits try search by the email  
    if (!$user) {
        $query = $conn->prepare("SELECT * FROM account WHERE Email = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        // If also the email doesn't exits
        if (!$user) {
            $errors[] = "username";
        }
    }

    // If the password wrong
    if ($user && !password_verify($password, $user["Password"])) {
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
