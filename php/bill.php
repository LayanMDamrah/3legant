<?php
require_once("tools.php");

class Bill{
    static function checkBill($order_code){
        $conn = Database::connect();

        $sql = "SELECT * FROM bill WHERE Order_Code = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("i", $order_code);
        $query->execute();

        return $query->get_queryult()->fetch_assoc(); 
    }

    static function payTheBill($order_code, $payment_method, $total = null){
        $conn = Database::connect();

        if($total != null){
            $sql = "UPDATE bill SET Payment_Method = ?, Total = ? WHERE Order_Code = ?";
            $query = $conn->prepare($sql); 
            $query->bind_param("sdi", $payment_method, $total, $order_code);
        }
        else{
            $sql = "UPDATE bill SET Payment_Method = ? WHERE Order_Code = ?";
            $query = $conn->prepare($sql); 
            $query->bind_param("si", $payment_method, $order_code);
        }

        return $query->execute(); 
    }
    
    static function confirmBill($order_code){
        $conn = Database::connect();

        $sql = "UPDATE bill SET Payment_Method = 'confirmed' WHERE Order_Code = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("i", $order_code);

        return $query->execute(); 
    }
}
?>