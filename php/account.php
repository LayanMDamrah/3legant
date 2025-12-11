<?php
require_once("tools.php");

class Account{
    static function viewAccount($user_id){
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

       // $hash = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql="UPDATE user SET Password = ? WHERE User_ID = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("si", $new_pass, $user_id);

        return $query->execute();
    }

    static function viewUsers($email){
        $conn = Database::connect();

        $sql = "SELECT * FROM user WHERE Email = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("s", $email);
        $query->execute();

        return $query->get_queryult()->fetch_assoc();
    }
      
}
?>