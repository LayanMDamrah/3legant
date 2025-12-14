<?php
session_start();
require_once("tools.php");

// Get POST values
$product_id    = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$product_price = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
$product_qty   = filter_input(INPUT_POST, 'product_qty', FILTER_VALIDATE_INT);

$user_id = $_SESSION["User_ID"];


// Ensure session cart exists
if (!isset($_SESSION['add-to-cart']) || !is_array($_SESSION['add-to-cart'])) {
    $_SESSION['add-to-cart'] = [];
}


// Update SESSION
$exists = false;

foreach ($_SESSION['add-to-cart'] as &$item) {
    if ($item['id'] == $product_id) {
        $item['qty'] += $product_qty;
        $item['sub_total'] = $item['qty'] * $item['price'];
        $exists = true;
        break;
    }
}
unset($item);

if (!$exists) {
    $_SESSION['add-to-cart'][] = [
        'id'        => $product_id,
        'price'     => $product_price,
        'qty'       => $product_qty,
        'sub_total' => $product_price * $product_qty
    ];
}


// Calculate cart total
$total = 0;
foreach ($_SESSION['add-to-cart'] as $p) {
    $total += $p['sub_total'];
}
$_SESSION['cart_total'] = $total;


// Get updated product entry
foreach ($_SESSION['add-to-cart'] as $item) {
    if ($item['id'] == $product_id) {
        $updated_qty = $item['qty'];
        $updated_sub_total = $item['sub_total'];
        break;
    }
}



// Update DATABASE cart table
$conn = Database::connect();

// Does this product already exist for THIS USER?
$check = $conn->prepare("SELECT Product_Id FROM cart WHERE Product_Id = ? AND User_ID = ?");
$check->bind_param("ii", $product_id, $user_id);
$check->execute();
$res = $check->get_result();
$exists_in_db = ($res && $res->num_rows > 0);


if ($exists_in_db) {
    // Update row for this user
    $stmt = $conn->prepare("
        UPDATE cart SET Product_Quantity = ?, Product_Price = ?, Sub_Total = ?, Total = ?
        WHERE Product_Id = ? AND User_ID = ?");
    $stmt->bind_param("idddii", $updated_qty, $product_price, $updated_sub_total, $total, $product_id, $user_id);
    $stmt->execute();

} else {
    // Insert new row for this user
    $stmt = $conn->prepare("INSERT INTO cart (User_ID, Product_Id, Product_Quantity, Product_Price, Total, Sub_Total)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiddd", $user_id, $product_id, $updated_qty, $product_price, $total, $updated_sub_total);
    $stmt->execute();
}

// Redirect back
header("Location: cart.php");
exit;
?>