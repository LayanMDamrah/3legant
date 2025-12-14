<?php
session_start();
require_once("tools.php");

$conn = Database::connect();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = [];

    if (empty($username)) {
        $errors[] = "username";
    }

    if (empty($password)) {
        $errors[] = "password";
    }

    // Check username
    if (empty($errors)) {
        $query = $conn->prepare("SELECT * FROM account WHERE Name = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        // Try email if username not found
        if (!$user) {
            $query = $conn->prepare("SELECT * FROM account WHERE Email = ?");
            $query->bind_param("s", $username);
            $query->execute();
            $result = $query->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                $errors[] = "username";
            }
        }

        // Check password
        if ($user && !password_verify($password, $user["Password"])) {
            $errors[] = "password";
        }
    }

    if (!empty($errors)) {
        // Redirect with error
        header("Location: login.php?error=" . implode(",", $errors));
        exit();
    }
    // SUCCESS LOGIN → STORE ALL NEEDED INFO
    $_SESSION["User_ID"] = $user["User_ID"];
    $_SESSION["username"] = $user["Name"];
    $_SESSION["email"] = $user["Email"];
    $_SESSION["photo"] = $user["Photo"];
    $_SESSION["status"] = $user["Status"];
    $_SESSION["age"] = $user["Age"];

    $email = $user["Email"];

    if (strpos(strtolower($email), "admin") !== false) {
        // email contains "admin"
        $role = "admin";
        $_SESSION["role"] = $role;
    } else {
        // normal user
        $role = "user";
        $_SESSION["role"] = $role;
    }

    $sql = "UPDATE account SET Role = ? WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $email);
    $stmt->execute();
    header("Location: index.php?success=1");
    exit();
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
    //if (isset($_SESSION['username']) && isset($_SESSION['password']))
    ?>

    <section class="login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <img src="./assets/imgs/public/login-img.webp" class=" login-img " alt="">
                </div>

                <div class="col-lg-6  col-12 justify-content-start align-items-center p-5">
                    <div class="info-login d-flex flex-column">
                        <p class="Heading-1">
                            Login
                        </p>
                        <p class="Heading-5 mb-4">Don’t have an accout yet?<a href="sign-up.php" id="signup-btn" class="green text-decoration-none "> Sign Up</a></p>
                    </div>

                    <!-- input form -->
                    <form action="login.php" method="POST" class="p-4 border-0 rounded">

                        <div class="mb-4">

                            <div id="error-message" class="text-danger mb-3" hidden></div>
                            <input type="text" name="username" id="username" class="form-control"
                                placeholder="Enter your username or email" required>
                        </div>

                        <div class="mb-4">

                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter your password" required>
                            <span id="incorrect" class="red" hidden>Incorrect Username or Password</span>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="login" value="login" class="btn btn-dark border-0 w-100">
                                Login
                            </button>
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS file here -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/entery.js"></script>
</body>

</html>