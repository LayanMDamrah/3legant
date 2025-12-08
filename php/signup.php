<?php
require_once("tools.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $age = intval($_POST["age"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // validation
    if (empty($name) || empty($email) || empty($password) || $age <= 0) {
        die("Invalid input!");
    }

    $conn = Database::connect();

    // Hashing password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Default values
    $role = "user";
    $photo = "default.jpg";
    $status = "pending";

    $sql = "INSERT INTO account (Name, Email, Age, Role, Password, Photo) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $name, $email, $age, $role, $hashed, $photo);

    if ($stmt->execute()) {
        echo "<h2>Your account has been created and is awaiting admin approval.</h2>";
        echo "<a href='../login.html'>Go to Login</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
