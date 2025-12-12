<?php
session_start();
require_once("./php/tools.php");

// إذا كان المستخدم مسجل دخول
if (isset($_SESSION['User_ID'])) {

    $user_id = $_SESSION['User_ID'];
    $conn = Database::connect();

    // حذف السلة من قاعدة البيانات
    $stmt = $conn->prepare("DELETE FROM cart WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// مسح جميع بيانات الجلسة
session_unset();
session_destroy();

// إعادة التوجيه لصفحة login
header("Location: login.php");
exit();
?>
