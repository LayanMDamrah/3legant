<?php
session_start();

if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    // Remove from session cart
    if (isset($_SESSION['add-to-cart'])) {
        foreach ($_SESSION['add-to-cart'] as $index => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['add-to-cart'][$index]);
                break;
            }
        }

        // Re-index array
        $_SESSION['add-to-cart'] = array_values($_SESSION['add-to-cart']);
    }
}

// Redirect back to cart
header("Location: ../cart.php");
exit();
