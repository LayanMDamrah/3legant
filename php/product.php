<?php
require_once("tools.php");

class Product{
    static function viewProduct($id){
        $conn = Database::connect();

        $sql = "SELECT * FROM product WHERE ID = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("i", $id);
        $query->execute();

        return $query->get_result()->fetch_assoc();
    }

    static function addToCart($product_id, $product_quantity, $product_price){
        $conn = Database::connect();

        $total = $product_quantity * $product_price;
        $sub_total = $total;
        
        $sql = "INSERT INTO cart (Product_Id, Product_Quantity, Product_Price, Total, Sub_Total) 
                Values (?, ?, ?, ?, ?)";
        $query = $conn->prepare($sql); 
        $query->bind_param("iiddd", $product_id, $product_quantity, $product_price,$total, $sub_total);
        
        return $query->execute();
    }

    static function increaseQuantity($product_id, $amount = 1){
        $conn = Database::connect();

        $sql = "UPDATE cart SET Product_Quantity = Product_Quantity + ?,
                                Total = (Product_Quantity + ?) * Product_Price,
                                Sub_Total = (Product_Quantity + ?) * Product_Price
                WHERE Product_Id  = ?";

        $query = $conn->prepare($sql); 
        $query->bind_param("iiii", $amount, $amount, $amount, $product_id);
        
        return $query->execute();
    }

    static function decreaseQuantity($product_id, $amount = 1){
        $conn = Database::connect();

        $sql = "UPDATE cart SET Product_Quantity = Product_Quantity - ?,
                                Total = (Product_Quantity - ?) * Product_Price,
                                Sub_Total = (Product_Quantity - ?) * Product_Price
                WHERE Product_Id  >= ?";

        $query = $conn->prepare($sql); 
        $query->bind_param("iiiii", $amount, $amount, $amount, $product_id, $amount);
        
        return $query->execute();
    }

    static function searchProduct($keyword){
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