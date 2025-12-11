<?php
session_start();
require_once("tools.php");

$product_id    = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$product_price = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
$product_qty   = filter_input(INPUT_POST, 'product_qty', FILTER_VALIDATE_INT);

// Ensure $_SESSION['add-to-cart'] exists
if (!isset($_SESSION['add-to-cart']) || !is_array($_SESSION['add-to-cart'])) {
    $_SESSION['add-to-cart'] = [];
} else {
    foreach ($_SESSION['add-to-cart'] as &$entry) {

        $entry[] = [
            'id'        => $product_id,
            'price'     => $product_price,
            'qty'       => $product_qty,
            'sub_total' => $product_price * $product_qty
        ];
    }
}


//  Add or update product in session by reference
$exists = false;
foreach ($_SESSION['add-to-cart'] as &$item) {

    if ((int)$item['id'] === $product_id) {
        // update qty and sub_total
        $item['qty'] = (int)$item['qty'] + $product_qty;

        $item['sub_total'] = $item['qty'] * $item['price'];
        $exists = true;
        break;
    }
}
unset($item); // important to avoid reference leaks

if (!$exists) {
    // add as new indexed product entry
    $_SESSION['add-to-cart'][] = [
        'id' => (int)$product_id,
        'price' => (float)$product_price,
        'qty' => (int)$product_qty,
        'sub_total' => ((float)$product_price) * ((int)$product_qty)
    ];
}

// Recalculate cart total (sum of sub_totals) and save separately
$total = 0.0;
foreach ($_SESSION['add-to-cart'] as $p) {
    if (is_array($p) && isset($p['sub_total'])) {
        $total += (float)$p['sub_total'];
    }
}
$_SESSION['cart_total'] = $total;


//Determine updated qty/sub_total for current product 
$updated_qty = 0;
$updated_sub_total = 0.0;
foreach ($_SESSION['add-to-cart'] as &$item) {
    if (is_array($item) && isset($item['id']) && (int)$item['id'] === $product_id) {
        $updated_qty = (int)$item['qty'];
        $updated_sub_total = (float)$item['sub_total'];
        break;
    }
}

// DB sync: update or insert single product row
$conn = Database::connect();



// Check if this product exists in cart table
$check = $conn->prepare("SELECT Product_Id FROM cart WHERE Product_Id = ?");
$check->bind_param("i", $product_id);
$check->execute();
$res = $check->get_result();
$exists_in_db = ($res && $res->num_rows > 0);

if ($exists_in_db) {
    $stmt = $conn->prepare("UPDATE cart SET Product_Quantity = ?, Product_Price = ?, Sub_Total = ?, Total = ? WHERE Product_Id = ?");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("idddi", $updated_qty, $product_price, $updated_sub_total, $total, $product_id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO cart (Product_Id, Product_Quantity, Product_Price, Total, Sub_Total) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("iiddd", $product_id, $updated_qty, $product_price, $total, $updated_sub_total);
    $stmt->execute();
}


//Redirect back to cart 

header("Location: ../cart.php");
exit;
