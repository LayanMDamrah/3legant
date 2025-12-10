<?php
session_start();
require_once("./php/tools.php");

// Check if cart is empty
if (!isset($_SESSION['add-to-cart']) || empty($_SESSION['add-to-cart'])) {
    echo "<tr><td colspan='4' class='text-center py-5'>
            <h4>Your cart is empty 🛒</h4>
          </td></tr>";
    exit;
}

$total = 0;
$conn = Database::connect(); // Connect once
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/main.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
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
                <li class="nav-item px-5"><a class="nav-link active" href="./index.php">Home</a></li>
                <li class="nav-item px-5"><a class="nav-link" href="./shop.php">Shop</a></li>
                <li class="nav-item px-5"><a class="nav-link" href="./products.php">Product</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 ms-auto ">
                <a href="./user.php" class="btn btn-link nav-icon p-0">
                    <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                </a>
                <a href="./cart.php" class="btn btn-link nav-icon p-0">
                    <img src="./assets/imgs/icons/Elements/Navigation/Cart Button.svg" alt="Cart">
                </a>
                <div id="auth-buttons" class="d-flex align-items-center gap-3">
                    <button class="btn btn-dark" id="login-btn">
                        <a class="text-decoration-none text-white" href="./login.php">Login</a>
                    </button>
                    <button class="btn btn-dark" id="logout-btn" hidden>Logout</button>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="cart">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <table class="table border-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start Heading-3">Product</th>
                            <th class="text-start Heading-3">Quantity</th>
                            <th class="text-start Heading-3">Unit Price</th>
                            <th class="text-start Heading-3">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['add-to-cart'] as $item):
                            if (!is_array($item)) continue;

                            $product_id = $item['id'] ?? 0;
                            $qty        = $item['qty'] ?? 0;
                            $price      = $item['price'] ?? 0;
                            $sub_total  = $item['sub_total'] ?? $qty * $price;

                            $query = $conn->prepare("SELECT Name, Photo FROM product WHERE ID = ?");
                            $query->bind_param("i", $product_id);
                            $query->execute();
                            $result = $query->get_result();
                            $product = $result->fetch_assoc();

                            if (!$product) continue;

                            $total += $sub_total;
                        ?>
                        <tr>
                            <td class="text-center">
                                <div class="d-flex align-items-center">
                                    <img src="./assets/imgs/productsPage/Productsiteam/<?php echo htmlspecialchars($product['Photo']); ?>"
                                         class="img-fluid rounded" style="width:80px; height:80px; object-fit:cover;">
                                    <div class="ms-3 d-flex flex-column justify-content-center">
                                        <span class="Heading-5"><?php echo htmlspecialchars($product['Name']); ?></span>
                                        <form action="php/remove.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                            <button type="submit" class="btn btn-light btn-sm mt-2">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center border rounded p-2" style="width:120px;">
                                    <button type="button" class="btn btn-sm fw-bold dec-btn">-</button>
                                    <span class="fw-bold qty-display"><?php echo $qty; ?></span>
                                    <button type="button" class="btn btn-sm fw-bold inc-btn">+</button>
                                </div>
                            </td>
                            <td class="price Heading-4"><?php echo $price; ?></td>
                            <td class="sub-price Heading-4"><?php echo $sub_total; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <!-- Cart Summary -->
                <form action="bill.php" method="POST">
                    <p class="Heading-3">Cart summary</p>
                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment" value="credit_card" id="pay1">
                        <label class="form-check-label w-100" for="pay1">
                            <div class="fw-bold">Credit Card</div>
                            <small class="text-muted">Visa, MasterCard, AMEX</small>
                        </label>
                    </div>

                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment" value="paypal" id="pay2">
                        <label class="form-check-label w-100" for="pay2">
                            <div class="fw-bold">PayPal</div>
                            <small class="text-muted">Fast & secure checkout</small>
                        </label>
                    </div>

                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment" value="cash_on_delivery" id="pay3">
                        <label class="form-check-label w-100" for="pay3">
                            <div class="fw-bold">Cash on Delivery</div>
                            <small class="text-muted">+ 5$ service fee</small>
                        </label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-light">
                        <div class="Heading-4">Total: $<span id="cart-total"><?php echo $total; ?></span></div>
                        <button type="button" class="btn btn-dark btn-lg px-4" onclick="window.location.href='bill.php'">
                            Checkout
                        </button>
                    </div>
                </form>
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
                    <div class="col-lg-3 col-md-6 p-4"><a href="./shop.php" class="Heading-6">Shop</a></div>
                    <div class="col-lg-3 col-md-6 p-4"><a href="./products.php" class="Heading-6">Product</a></div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/counter.js"></script>
<script src="./assets/js/entery.js"></script>

</body>
</html>
