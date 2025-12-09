<?php
require_once("tools.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password) || $age <= 0) {
        header("Location: ../sign-up.html?error=invalid");
        exit();
    }

    $conn = Database::connect();

    // Check if email exists
    $check = $conn->prepare("SELECT Email FROM account WHERE Email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Email already used
        header("Location: ../sign-up.html?error=alreadyused");
        exit();
    }

    // Insert new user (pending approval)
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $role = "user";
    $photo = "default.jpg";
    $status = "pending";

    $sql = "INSERT INTO account (Name, Email, Age, Role, Password, Photo, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissss", $name, $email, $age, $role, $hashed, $photo, $status);

    if ($stmt->execute()) {
        header("Location: ../index.html");
        exit();
    } else {
        header("Location: ../sign-up.html");
        exit();
    }
}
