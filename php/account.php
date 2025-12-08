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

        $hash = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql="UPDATE user SET Password = ? WHERE User_ID = ?";
        $query = $conn->prepare($sql); 
        $query->bind_param("si", $hash, $user_id);

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
*/        
}
?>