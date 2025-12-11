<?php
require_once("tools.php");

class Product
{
    static function getQuantity($product_id)
    {
        $conn = Database::connect();
        $sql = "SELECT Product_Quantity FROM cart WHERE Product_Id = ?";
        $q = $conn->prepare($sql);
        $q->bind_param("i", $product_id);
        $q->execute();
        return $q->get_result()->fetch_assoc()["Product_Quantity"];
    }

    static function increaseQuantity($product_id, $amount = 1)
    {
        $conn = Database::connect();

        $sql = "UPDATE cart SET Product_Quantity = Product_Quantity + ?,
                                Total = (Product_Quantity + ?) * Product_Price,
                                Sub_Total = (Product_Quantity + ?) * Product_Price
                WHERE Product_Id  = ?";

        $query = $conn->prepare($sql);
        $query->bind_param("iiii", $amount, $amount, $amount, $product_id);

        return $query->execute();
    }

    static function decreaseQuantity($product_id, $amount = 1)
    {
        $conn = Database::connect();

        $sql = "UPDATE cart SET Product_Quantity = Product_Quantity - ?,
                                Total = (Product_Quantity - ?) * Product_Price,
                                Sub_Total = (Product_Quantity - ?) * Product_Price
                WHERE Product_Id = ?";

        $query = $conn->prepare($sql);
        $query->bind_param("iiii", $amount, $amount, $amount, $product_id);

        return $query->execute();
    }

    static function searchProduct($keyword)
    {
        $conn = Database::connect();

        $keyword = "%$keyword%";

        $sql = "SELECT * FROM product WHERE Name LIKE ? OR Description LIKE ?";
        $query = $conn->prepare($sql);

        $query->bind_param("ss", $keyword, $keyword);
        $query->execute();

        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<?php
require_once "tools.php";
session_start();

header("Content-Type: application/json");

$product_id = $_POST["product_id"] ?? null;
$action = $_POST["action"] ?? null;

$newQty = null; 

if ($product_id && $action) {
    if ($action === "increase") {
        Product::increaseQuantity($product_id);
    } elseif ($action === "decrease") {
        Product::decreaseQuantity($product_id);
    }

    $newQty = Product::getQuantity($product_id);
}

echo json_encode(["quantity" => $newQty]);

exit();

?>
