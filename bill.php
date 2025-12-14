<?php
session_start();
require_once('tools.php');

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit();
}

// Get the selected payment method from POST
$payment_method = $_POST['payment'] ?? null;

if ($payment_method) {
    // Store it in session
    $_SESSION['payment_method'] = $payment_method;

    $total = $_SESSION['cart_total'] ?? 0;

    $conn = Database::connect();

    // Insert into your bill table
    $stmt = $conn->prepare("INSERT INTO bill (Payment_Method, Total, Date) VALUES (?, ?, NOW())");
    $stmt->bind_param("sd", $payment_method, $total);
    $stmt->execute();

    // Get the auto-generated Order_Code
    $order_code = $stmt->insert_id;

    $stmt->close();
    $conn->close();
} else {
    echo "Please select a payment method!";
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
    <!-- bill.css-->
    <link rel="stylesheet" href="./assets/css/bill.css">

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

                    <button class="btn btn-dark" id="login-btn">
                        <a class="text-decoration-none text-white" href="./login.php">Login</a>
                    </button>
                    <button class="btn btn-dark" id="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
        </div>
    </nav>
    <!--Bill body-->
    <section class="py-5">
        <div class="container">
            <!-- Heading -->
            <div class="row">
                <div class="col-12 text-center">
                    <h3>Complete!</h3>
                </div>
            </div>

            <!-- Thank you message -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 text-center p-4 border rounded shadow-sm">
                    <h5 class="text-muted Heading-4">Thank you! 🎉</h5>
                    <h4 class="fw-bold mb-4 Heading-4">Your order has been received</h4>

                    <!-- Order details -->
                    <form action="bill.php">
                        <div class="text-start margin">
                            <div class="d-flex mb-2">
                                <span class="fw-semibold paragraph me-5 conectword">Order code:</span>
                                <span id="orderCodeValue"><?php echo $order_code ?? 'N/A'; ?></span>
                            </div>

                            <div class="d-flex mb-2">
                                <span class="fw-semibold paragraph me-5 conectword">Date:</span>
                                <span id="orderDateValue"><?php echo date('Y-m-d'); ?></span>
                            </div>

                            <div class="d-flex mb-2">
                                <span class="fw-semibold paragraph me-5 conectword">Total:</span>
                                <span id="orderTotalValue">$<?php echo number_format($_SESSION['cart_total'] ?? 0, 2); ?></span>
                            </div>

                            <div class="d-flex mb-4">
                                <span class="fw-semibold paragraph me-5 conectword">Payment method:</span>
                                <span id="orderPaymentValue"><?php echo htmlspecialchars($_SESSION['payment_method'] ?? 'Not selected'); ?></span>
                            </div>

                        </div>
                    </form>

                    <!-- Button -->
                    <div class="text-center">
                        <button class="btn btn-dark px-4" onclick="window.location.href='index.php'">
                            Done
                        </button>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/entery.js"></script>
    <script>
        // Only update dynamically cart changes
        const cartTotalSpan = document.getElementById('orderTotalValue');
        const cartTotalInput = document.getElementById('cart-total-input');

        function updateCartTotal(newTotal) {
            cartTotalSpan.textContent = newTotal.toFixed(2);
            cartTotalInput.value = newTotal.toFixed(2);
        }

        // Initial value from PHP session
        let initialTotal = <?php echo $_SESSION['cart_total'] ?? 0; ?>;
        updateCartTotal(initialTotal);
    </script>
</body>

</html>