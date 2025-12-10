<?php
session_start();
require_once("./php/tools.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password) || $age <= 0) {
        header("Location: sign-up.php?error=invalid");
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
        header("Location: sign-up.php?error=alreadyused");
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
        $_SESSION["username"] = $name; // log in server-side
        header("Location: index.php?success=1");
        exit();
    } else {
        header("Location: sign-up.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <!-- main css -->
    <link rel="stylesheet" href="./assets/css/main.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    if (isset($_SESSION['username']) && isset($_SESSION['password']))
    ?>

    <section class="SignUp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <img src="./assets/imgs/public/login-img.webp" class=" login-img " alt="">
                </div>

                <div class="col-lg-6  col-12 justify-content-start align-items-center p-5">
                    <div class="info-login d-flex flex-column">
                        <p class="Heading-1">
                            Sign Up
                        </p>
                        <p class="Heading-5 mb-4">Already have an account?<a href="login.php"
                                class="green text-decoration-none "> Login</a></p>
                    </div>

                    <!-- input form -->
                    <form action="sign-up.php" method="POST" class="p-4 border-0 rounded">

                        <!-- Name -->
                        <div class="mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name"
                                required>
                        </div>

                        <!-- Age -->
                        <div class="mb-3">
                            <input type="number" name="age" id="age" class="form-control" placeholder="Enter your age"
                                required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Enter your email" required>
                            <span id="alreadyused" class="red" hidden>This email is already used</span>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>

                        <!-- Button full width -->
                        <button type="submit" name="register" value="register" class="btn btn-dark w-100">
                            Sign Up
                        </button>

                    </form>


                </div>
            </div>
        </div>
    </section>



    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS file here -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/entery.js"></script>
</body>

</html>