<?php
session_start();
require_once("./php/tools.php");

if (isset($_SESSION['User_ID'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? '');
    $age      = (int)($_POST["age"] ?? 0);
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    if ($name === '' || $email === '' || $password === '' || $age <= 0) {
        header("Location: sign-up.php?error=invalid");
        exit();
    }

    $conn = Database::connect();

    // Email check
    $check = $conn->prepare("SELECT 1 FROM account WHERE Email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: sign-up.php?error=alreadyused");
        exit();
    }

    // Role & status
    if (stripos($email, "admin") !== false) {
        $role   = "admin";
        $status = "approved";
    } else {
        $role   = "user";
        $status = "pending";
    }

    $photo = "default.jpg";

    // Insert into admin or user 
    if ($role === "admin") {

        $sql = "INSERT INTO admin (Name, Password)
                VALUES (?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();

        $user_id = $stmt->insert_id;

    } else {

        $sql = "INSERT INTO user (Name, Photo, Password)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $photo, $password);
        $stmt->execute();

        $user_id = $stmt->insert_id;
    }

    if (!$user_id) {
        header("Location: sign-up.php?error=db");
        exit();
    }

    //Insert into account
    $sqlAcc = "INSERT INTO account
        (User_ID, Name, Email, Age, Role, Password, Photo, Status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtAcc = $conn->prepare($sqlAcc);
    $stmtAcc->bind_param("ississss", $user_id, $name, $email, $age, $role, $password, $photo, $status
    );

    if (!$stmtAcc->execute()) {
        header("Location: sign-up.php?error=db");
        exit();
    }

    // Login
    $_SESSION["User_ID"] = $user_id;
    $_SESSION["username"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["role"] = $role;

    header("Location: index.php?success=1");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/main.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<section class="SignUp">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12 col-lg-6">
                <img src="./assets/imgs/public/login-img.webp" class="login-img" alt="">
            </div>

            <div class="col-lg-6 col-12 p-5">
                <div class="info-login d-flex flex-column">
                    <p class="Heading-1">Sign Up</p>
                    <p class="Heading-5 mb-4">
                        Already have an account?
                        <a href="login.php" class="green text-decoration-none">Login</a>
                    </p>
                </div>

                <form action="sign-up.php" method="POST" class="p-4 border-0 rounded">

                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>

                    <div class="mb-3">
                        <input type="number" name="age" class="form-control" placeholder="Enter your age" required>
                    </div>

                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        <span id="alreadyused" class="red" hidden>Email already used</span>
                    </div>

                    <div class="mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Sign Up
                    </button>

                </form>

            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/entery.js"></script>

</body>
</html>
