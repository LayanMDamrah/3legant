<?php
session_start();
require_once("./php/tools.php");

// If he was logedin
if (isset($_SESSION['User_ID'])) {

    $user_id = $_SESSION['User_ID'];
    $conn = Database::connect();

    // Delete the cart from the DB
    $stmt = $conn->prepare("DELETE FROM cart WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Delete the session
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
