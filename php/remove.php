<?php
session_start();
require_once("tools.php"); // make sure Database::connect() is included

if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    // Remove from session cart
    if (isset($_SESSION['add-to-cart'])) {
        foreach ($_SESSION['add-to-cart'] as $index => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['add-to-cart'][$index]);
                break;
            }
        }
        // Re-index array
        $_SESSION['add-to-cart'] = array_values($_SESSION['add-to-cart']);
    }

    // Remove from database
    $conn = Database::connect();
    $sql = "DELETE FROM cart WHERE Product_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Redirect back to cart
header("Location: ../cart.php");
exit();
