<?php
session_start();
require_once("tools.php");

// Cast values
$product_id    = (int)$_POST['product_id'];
$product_price = (float)$_POST['product_price'];
$product_qty   = (int)$_POST['product_qty'];

if (!isset($_SESSION['add-to-cart'])) {
    $_SESSION['add-to-cart'] = [];
}


//Update SESSION cart (IMPORTANT: BY REFERENCE)

$exists = false;

foreach ($_SESSION['add-to-cart'] as &$item) {
    if ($item['id'] == $product_id) {

        // Increase quantity
        $item['qty'] += $product_qty;

        // Update sub_total
        $item['sub_total'] = $item['qty'] * $item['price'];

        $exists = true;
        break;
    }
}
unset($item); // IMPORTANT when using reference

// If product does not exist in session -> add it
if (!$exists) {
    $_SESSION['add-to-cart'][] = [
        'id'        => $product_id,
        'price'     => $product_price,
        'qty'       => $product_qty,
        'sub_total' => $product_price * $product_qty
    ];
}

//calculate the total
$total = 0;
foreach ($_SESSION['add-to-cart'] as $item) {
    $total += $item['sub_total'];
}

// ---------------------------------------------------
// 3) Extract UPDATED qty + subtotal for THIS product
// ---------------------------------------------------
$updated_qty = 0;
$updated_sub_total = 0;

foreach ($_SESSION['add-to-cart'] as $item) {
    if ($item['id'] == $product_id) {
        $updated_qty = $item['qty'];
        $updated_sub_total = $item['sub_total'];
        break;
    }
}

// UPDATE or INSERT into DATABASE

$conn = Database::connect();

// Check if exists in DB
$check = $conn->prepare("SELECT Product_Id FROM cart WHERE Product_Id = ?");
$check->bind_param("i", $product_id);
$check->execute();
$result = $check->get_result();
$exists_in_db = $result->num_rows > 0;

if ($exists_in_db) {

    // UPDATE existing row
    $stmt = $conn->prepare("UPDATE cart SET Product_Quantity = ?, Product_Price = ?, Sub_Total =?, Total = ? WHERE Product_Id = ?");
    $stmt->bind_param("idddi",$updated_qty, $product_price, $updated_sub_total, $total, $product_id);
    $stmt->execute();
} else {

    // INSERT new row
    $stmt = $conn->prepare("INSERT INTO cart (Product_Id, Product_Quantity, Product_Price, Total, Sub_Total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiddd", $product_id, $updated_qty, $product_price, $total, $updated_sub_total);
    $stmt->execute();
}
$_SESSION['add-to-cart']['total'] = $total;
$updateTotalForAll = $conn->prepare("UPDATE cart SET Total = ?");
$updateTotalForAll->bind_param("d", $total);
$updateTotalForAll->execute();
header("Location: ../cart.php");
exit();
