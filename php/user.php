<?php
require_once("tools.php");
require_once("admin.php");

class User{
    static function viewAccount($user_id){
        $conn = Database::connect();

        $sql = "SELECT * FROM user WHERE User_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $user_id);
        $query->execute();

        return $query->get_queryult()->fetch_assoc();
    }

    static function provideInfo($user_id, $name, $email, $age, $photo){
    $conn = Database::connect();

    $sql = "UPDATE user 
            SET Name = ?, Email = ?, Age = ?, Photo = ? 
            WHERE User_Id = ?";

    $query = $conn->prepare($sql);
    $query->bind_param("ssisi", $name, $email, $age, $photo, $user_id);

    return $query->execute();
}

    static function viewProduct($id){
        $conn = Database::connect();

        $sql = "SELECT * FROM product 
                WHERE ID = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("i", $id);
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

    static function login($email, $password){
        $conn = Database::connect();

        $sql = "SELECT * FROM user WHERE Email = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("s", $email);
        $query->execute();

        $queryult = $query->get_queryult();
        $user = $queryult->fetch_assoc();

        if($user && password_verify($password, $user["Password"]))
            return $user;

        return false;
    }
}
?>