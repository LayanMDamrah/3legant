<?php
session_start();
require_once("tools.php");

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {

    $user_id = (int)$_POST['user_id'];

    // Call your Admin class function
    Admin::deleteUsers($user_id);

    // Redirect to avoid form resubmission
    header("Location: ../admin_account.php?deleted=1");
    exit();
}
?>
<?php
require_once("tools.php");

class Admin
{
    static function addUsers($name, $email, $age, $role, $user_id, $photo, $status)
    {
        $conn = Database::connect();

        // Random password
        $plain_pass = bin2hex(random_bytes(4));
        //$hash = password_hash($plain_pass, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO account (Name, Email, Age, Role, Photo, Password, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", $name, $email, $age, $role, $photo, $plain_pass, $status);

        if ($stmt->execute()) {
            // Save Info in the session
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
            header("Location: ../sign-up.php?error=alreadyused");
            exit();
        } else {
            // Email not found → approve
            Admin::approveUser($email);
            header("Location: ../index.php");
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

        // 1) Delete from user table
        $sql1 = "DELETE FROM user WHERE User_ID = ?";
        $query1 = $conn->prepare($sql1);
        $query1->bind_param("i", $user_id);
        $query1->execute();

        // 2) Delete from account table
        $sql2 = "DELETE FROM account WHERE User_ID = ?";
        $query2 = $conn->prepare($sql2);
        $query2->bind_param("i", $user_id);
        $query2->execute();

        // Return true only if both succeeded
        return ($query1->affected_rows > 0 || $query2->affected_rows > 0);
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

        //$hash = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql = "UPDATE user SET Password = ? WHERE User_ID = ?";
        $query = $conn->prepare($sql);
        $query->bind_param("si", $new_pass, $user_id);

        return $query->execute();
    }
}
