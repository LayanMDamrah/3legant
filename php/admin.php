<?php
require_once("tools.php");

class Admin{
    static function addUsers($name, $user_id, $photo){
        $conn = Database::connect();

        $plain_pass = bin2hex(random_bytes(4));
        $hash = password_hash($plain_pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (Name, User_Id, Photo, Password) VALUES (?, ?, ?, ?)";
        $query = $conn->prepare($sql); 
        $query->bind_param("siss", $name, $user_id, $photo, $hash);
        
        if($query->execute()){
            return $plain_pass;
        }
        return false;
    }

    // Get all pending users
    static function getPendingUsers(){
        $conn = Database::connect();
        $sql = "SELECT * FROM user WHERE Status='pending'";
        $result = $conn->query($sql);

        $users = [];
        while($row = $result->fetch_assoc()){
            $users[] = $row;
        }
        return $users;
    }

    // Approve a specific user
    static function approveUser($user_id){
        $conn = Database::connect();
        $sql = "UPDATE user SET Status='approved' WHERE User_Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    static function deleteUsers($user_id){
        $conn = Database::connect();
    
        $sql = "DELETE FROM user WHERE User_Id = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("i", $user_id);

        return $query->execute();  
    }

    static function viewUsers($user_id){
        $conn = Database::connect();

        $sql = "SELECT * FROM user WHERE User_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $user_id);
        $query->execute();

        return $query->get_queryult()->fetch_assoc();
    }

    static function updateUserInfo($user_id, $name, $photo){
        $conn = Database::connect();

        $sql = "UPDATE user SET Name = ?, User_Id = ?, Photo = ? WHERE User_Id = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("sis", $name, $user_id, $photo);

        return $query->execute();
    }

    static function setUserPassword($user_id, $new_pass){
        $conn = Database::connect();

        $hash = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql="UPDATE user SET Password = ? WHERE User_ID = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("si", $hash, $user_id);

        return $query->execute();
    }

    static function confirmBill($order_code){
        $conn = Database::connect();

        $sql = "UPDATE bill SET Payment_Method = 'confirmed' WHERE Order_Code = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("s", $order_code);

        return $query->execute(); 
    }
// for now it is not neccesary
/*
    static function signUp($name, $email, $age, $role, $password, $photo){
        $conn = Database::connect();

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user(Name, Email, Age, Role, Password, Photo) Values(?, ? ,? ,? ,? ,? ,?)"; 
        $query = $conn->prepare($sql); 
        $query->bind_param("ssisss", $name, $email, $age, $role, $hash, $photo);

        return $query->execute();
    }

    static function login($admin_id, $password){
        $conn = Database::connect();

        $sql = "SELECT * FROM admin WHERE Admin_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $admin_id);
        $query->execute();

        return $query->get_queryult()->fetch_assoc();
    }
*/
}
?>