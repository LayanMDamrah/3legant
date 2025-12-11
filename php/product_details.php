<?php
session_start();
require_once("tools.php");

// Cast values properly
$product_id = (int)$_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = (float)$_POST['product_price'];
$product_qty = (int)$_POST['product_qty'];
$product_photo = $_POST['product_photo'];
$product_desc = $_POST['product_desc'];

// Optional: insert into database only if adding to cart
$conn = Database::connect();
$stmt = $conn->prepare("INSERT INTO product (Name, ID, Price, Description, Quantity, Photo) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$product_name, $product_id, $product_price, $product_desc, $product_qty, $product_photo]);
exit();
