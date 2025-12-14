<?php
session_start();
require_once('tools.php');

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit();
}

$conn = Database::connect();

// Fetch the logged-in user's info
$userid = $_SESSION['User_ID'];
$result = $conn->prepare("SELECT * FROM account WHERE User_ID = ?");
$result->bind_param("i", $userid);
$result->execute();
$user = $result->get_result()->fetch_assoc();

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
    <!--account.css-->
    <link rel="stylesheet" href="./assets/css/user.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php
    if (isset($_SESSION['username']) && isset($_SESSION['password']))
    ?>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light px-4 ">
        <div class="container">
            <a class="navbar-brand me-5 ms-5 Heading-2" href="./index.php">3legant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto  mb-2 mb-lg-0 d-flex gap-4">
                    <li class="nav-item px-5">
                        <a class="nav-link active" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item px-5">
                        <a class="nav-link" href="./shop.php">Shop</a>
                    </li>


                </ul>

                <div class="d-flex align-items-center gap-3 ms-auto ">

                    <?php if ($_SESSION['role'] === 'admin') { ?>
                        <a href="./admin_account.php" class="btn btn-link nav-icon p-0">
                            <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                        </a>
                    <?php } else { ?>
                        <a href="./user_account.php" class="btn btn-link nav-icon p-0">
                            <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                        </a>
                    <?php } ?>
                    <a href="./cart.php" class="btn btn-link nav-icon p-0">
                        <img src="./assets/imgs/icons/Elements/Navigation/Cart Button.svg" alt="Cart">
                    </a>
                    <div id="auth-buttons" class="d-flex align-items-center gap-3">
                        <?php if (!isset($_SESSION['User_ID'])): ?>
                            <a href="./login.php" class="btn btn-dark">Login</a>
                        <?php else: ?>
                            <button class="btn btn-dark" onclick="window.location.href='logout.php'">Logout</button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </nav>
    <!--Account Body-->
    <section>
        <div class="container">
            <div class="row m-5">
                <div class="col-12 text-center">
                    <h2 class="Heading-2 fs-1">My Account</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-3 ">
                    <div class="profile-card">
                        <!--should receive from php-->
                        <div class="profile-image">
                            <img src="./assets/imgs/Account/<?php echo basename($user['Photo']); ?>" alt="Profile Image" style="width:150px; height:150px; border-radius:50%;">
                        </div>
                        <!--should receive from php-->
                        <h3 class="profile-name"><?= htmlspecialchars($user['Name']); ?></h3>

                        <div class="section-title">Account</div>
                        <hr class="divider">

                        <ul class="menu">
                            <li><a href="cart.php">Cart</a></li>
                            <li><a href="login.php">Log Out</a></li>
                        </ul>
                    </div>

                </div>
                <!--detalis section-->
                <div class="col-12 col-sm-9">
                    <div class="account-container">

                        <!-- CURRENT ACCOUNT INFO -->
                        <h2 class="mb-4">Account Information</h2>
                        <div class="info-box">
                            <!--should receive from php-->
                            <p><strong>Name:</strong> <span><?= htmlspecialchars($user['Name']); ?></span></p>
                            <!--should receive from php-->
                            <p><strong>age:</strong> <span><?= number_format($user['Age']); ?></span></p>
                            <!--should receive from php-->
                            <p><strong>Email:</strong> <span><?= htmlspecialchars($user['Email']); ?></span></p>

                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer mt-5">
        <div class="container">
            <div class="footer-row">

                <div class="footer-col ">
                    <span class="Heading-4 ">3legant</span>
                </div>
                <div class="footer-col">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 p-4"><a href="./index.php" class="Heading-6">Home</a></div>
                        <div class="col-lg-3 col-md-6 p-4"><a href="./shop.php" class="Heading-6 ">Shop</a></div>

                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS file here -->
    <script src="./assets/js/entery.js"></script>

</body>

</html>