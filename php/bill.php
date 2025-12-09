<?php
require_once("tools.php");

if (!isset($_GET['order_code'])) {
    echo "Order code is missing.";
    exit;
}

$order_code = $_GET['order_code'];

$conn = Database::connect();

$sql = "SELECT * FROM bill WHERE Order_Code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_code);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "Bill not found.";
    exit;
}

// Redirect to bill.html with GET parameters
$url = "bill.html"
     . "?code=" . urlencode($result['Order_Code'])
     . "&date=" . urlencode($result['Date'])
     . "&total=" . urlencode($result['Total'])
     . "&payment=" . urlencode($result['Payment_Method']);

header("Location: $url");
exit;
