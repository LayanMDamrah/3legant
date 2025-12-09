<?php
require_once("tools.php");

class Admin
{
    static function addUsers($name, $email, $age, $role, $user_id, $photo, $status)
    {
        $conn = Database::connect();

        // توليد كلمة مرور عشوائية
        $plain_pass = bin2hex(random_bytes(4));
        $hash = password_hash($plain_pass, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO account (Name, Email, Age, Role, Photo, Password, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", $name, $email, $age, $role, $photo, $hash, $status);

        if ($stmt->execute()) {
            // حفظ جميع البيانات في الجلسة
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['account'] = [
                'Name'     => $name,
                'Email'    => $email,
                'Age'      => $age,
                'Role'     => $role,
                'Photo'    => $photo,
                'Password' => $plain_pass,
                'Status'   => $status
            ];

            return true;
        }

        return false;
    }

    // Get all pending users
    static function getPendingUsers()
    {
        $conn = Database::connect();
        $sql = "SELECT * FROM account WHERE Status='pending'";
        $result = $conn->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    static function userFoundOrNot()
    {
        $conn = Database::connect();
        $email = $_POST['email'];

        $sql = "SELECT * FROM account WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email already used → reject
            Admin::rejectUser($email);
            header("Location: ../sign-up.html?error=alreadyused");
            exit();
        } else {
            // Email not found → approve
            Admin::approveUser($email);
            header("Location: ../index.html");
            exit();
        }
    }

    // Approve a specific user
    static function approveUser($email)
    {
        $conn = Database::connect();
        $sql = "UPDATE account SET Status='approved' WHERE Email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }


    static function rejectUser($email)
    {
        $conn = Database::connect();
        $sql = "UPDATE account SET Status='rejected' WHERE Email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        return $stmt->execute();
    }

    static function deleteUsers($user_id)
    {
        $conn = Database::connect();

        $sql = "DELETE FROM user WHERE User_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $user_id);

        return $query->execute();
    }

    static function viewUsers($user_id)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM user WHERE User_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("i", $user_id);
        $query->execute();

        return $query->get_queryult()->fetch_assoc();
    }

    static function updateUserInfo($user_id, $name, $photo)
    {
        $conn = Database::connect();

        $sql = "UPDATE user SET Name = ?, User_Id = ?, Photo = ? WHERE User_Id = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("sis", $name, $user_id, $photo);

        return $query->execute();
    }

    static function setUserPassword($user_id, $new_pass)
    {
        $conn = Database::connect();

        $hash = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql = "UPDATE user SET Password = ? WHERE User_ID = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("si", $hash, $user_id);

        return $query->execute();
    }

    static function confirmBill($order_code)
    {
        $conn = Database::connect();

        $sql = "UPDATE bill SET Payment_Method = 'confirmed' WHERE Order_Code = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("s", $order_code);

        return $query->execute();
    }
}
