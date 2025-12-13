<?php
session_start();
require_once("./php/tools.php");

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit();
}

$conn = Database::connect();
$user_id = (int)$_SESSION['User_ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['qty'])) {
    $product_id = (int)$_POST['product_id'];
    $newQty = max(1, (int)$_POST['qty']);

    // Update session
    if (isset($_SESSION['add-to-cart']) && is_array($_SESSION['add-to-cart'])) {
        foreach ($_SESSION['add-to-cart'] as $i => $item) {
            if ((int)$item['id'] === $product_id) {
                $_SESSION['add-to-cart'][$i]['qty'] = $newQty;
                $_SESSION['add-to-cart'][$i]['sub_total'] = $newQty * $_SESSION['add-to-cart'][$i]['price'];
                break;
            }
        }
    }

    // Calculate total for all items
    $cartTotal = 0;
    foreach ($_SESSION['add-to-cart'] as $item) {
        $cartTotal += $item['sub_total'];
    }

    // Update database for this product
    $stmt = $conn->prepare("
        UPDATE cart 
        SET Product_Quantity = ?, Sub_Total = ?, Total = ?
        WHERE User_ID = ? AND Product_Id = ?
    ");
    $stmt->bind_param("iiiii", $newQty, $_SESSION['add-to-cart'][$i]['sub_total'], $cartTotal, $user_id, $product_id);
    $stmt->execute();

    //  update Total column for all rows 
    $stmt = $conn->prepare("UPDATE cart SET Total = ? WHERE User_ID = ?");
    $stmt->bind_param("ii", $cartTotal, $user_id);
    $stmt->execute();

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'total' => $cartTotal]);
    exit;
}

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
                        <button class="btn btn-dark" id="login-btn">
                            <a class="text-decoration-none text-white" href="./login.php">Login</a>
                        </button>
                        <button class="btn btn-dark" id="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
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
                            <?php
                            $total = 0; 
                            if (isset($_SESSION['add-to-cart']) && is_array($_SESSION['add-to-cart']) && !empty($_SESSION['add-to-cart'])):
                                foreach ($_SESSION['add-to-cart'] as $item):
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
                                            <div class="cart-qty d-flex justify-content-between align-items-center border rounded p-2"
                                                data-product-id="<?php echo $product_id; ?>">
                                                <button type="button" class="btn btn-sm fw-bold dec-btn">-</button>
                                                <span class="fw-bold qty-display"><?php echo $qty; ?></span>
                                                <button type="button" class="btn btn-sm fw-bold inc-btn">+</button>
                                            </div>

                                        </td>
                                        <td class="price Heading-4"><?php echo $price; ?></td>
                                        <td class="sub-price Heading-4"><?php echo $sub_total; ?></td>
                                    </tr>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <h4>Your cart is empty 🛒</h4>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <!-- Cart Summary -->
                    <form action="bill.php" method="POST">
                        <p class="Heading-3">Cart summary</p>
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment" value="Credit Card" id="pay1">
                            <label class="form-check-label w-100" for="pay1">
                                <div class="fw-bold">Credit Card</div>
                                <small class="text-muted">Visa, MasterCard, AMEX</small>
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment" value="Paypal" id="pay2">
                            <label class="form-check-label w-100" for="pay2">
                                <div class="fw-bold">PayPal</div>
                                <small class="text-muted">Fast & secure checkout</small>
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment" value="Cash On Delivery" id="pay3">
                            <label class="form-check-label w-100" for="pay3">
                                <div class="fw-bold">Cash on Delivery</div>
                                <small class="text-muted">+ 5$ service fee</small>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-light">
                            <div class="Heading-4">Total: $<span id="cart-total"><?php echo $total; ?></span></div>
                            <button type="submit" class="btn btn-dark btn-lg px-4">
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
    <script src="./assets/js/entery.js"></script>
    <script>
        const form = document.querySelector('form[action="bill.php"]');
        form.addEventListener('submit', function(event) {
            const payment = document.querySelector('input[name="payment"]:checked');
            if (!payment) {
                event.preventDefault();
                alert("Please choose a payment method!");
            }
        });
    </script>
 <script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.cart-qty').forEach(box => {

        let qty = parseInt(box.querySelector('.qty-display').textContent);
        const productId = box.dataset.productId;

        function saveQty(newQty) {
            fetch('cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `product_id=${productId}&qty=${newQty}`
            })
            .then(() => location.reload());
        }

        box.querySelector('.inc-btn').onclick = () => {
            qty++;
            saveQty(qty);
        };

        box.querySelector('.dec-btn').onclick = () => {
            if (qty > 1) {
                qty--;
                saveQty(qty);
            }
        };
    });

});
</script>




</body>

</html>