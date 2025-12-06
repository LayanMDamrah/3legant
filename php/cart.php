<?php
require_once("tools.php");

class Cart{
    static function getCartItems(){
        $conn = Database::connect();

        $sql = "SELECT 
                    cart.*, 
                    product.Name, 
                    product.Description, 
                    product.Photo 
                FROM cart
                JOIN product ON cart.Product_Id = product.ID";

        $query = $conn->query($sql);
        return $query->fetch_all(MYSQLI_ASSOC);
    }
    
    static function getTotal(){
        $conn = Database::connect();
    }

    static function getSubTotal(){
        $conn = Database::connect();
        
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
}
?>