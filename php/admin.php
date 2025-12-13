<?php
session_start();
require_once("tools.php");

// Check if the form is for deleting a user
if (isset($_POST['delete_user_id'])) {
    $user_id = (int)$_POST['delete_user_id'];

    // Call your Admin delete function
    Admin::deleteUsers($user_id);

    // Redirect to avoid form resubmission
    header("Location: ../admin_account.php?deleted=1");
    exit();
}
// CREATE NEW USER
if (isset($_POST['create_new_user'])) {

    $name   = $_POST['name'];
    $age    = $_POST['age'];
    $email  = $_POST['email'];
    $password  = $_POST['password'];
    $role   = "user";
    $photo  = $_POST['photo'] ?? '';
    $status = "approved";

    // Insert user
    Admin::addUsers($name, $email, $age, $role, $password, null, $photo, $status);

    header("Location: ../admin_account.php?added=1");
    exit();
}

// Check if UPDATE request
if (isset($_POST['update_user_id'])) {

    $user_id  = (int)$_POST['update_user_id'];
    $name     = $_POST['name'];
    $age      = $_POST['age'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $account_type = $_POST['account_type'] ?? 'user';

    if ($account_type === 'admin') {
        // ADMIN → edit text only
        $photo = $_POST['photo'] ?? null;

    } else {
    // USER → upload real photo
    if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === 0) {

        //Upload file
        $uploadDir = __DIR__ . '/../assets/imgs/Account/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        // cleaning the file name 
        $filename = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $_FILES['profile_image']['name']);

        //where to save the upload photo
        $photo = 'assets/imgs/Account/' . $filename;

        // Save the file on the server 
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $filename);

    } else {
        // if he didnt upload just use the old one
        $photo = $_POST['old_photo'] ?? null;
    }
}


    // Save to DB
    Admin::updateUserAcc($user_id, $name, $age, $email, $password, $photo);

    // Redirect to correct account page
    if ($account_type === 'admin') {
        header("Location: ../admin_account.php?updated=1");
    } else {
        header("Location: ../user_account.php?updated=1");
    }
    exit();
}


// Check if the form is for updating a user
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // create if missing
    }

    // Sanitize filename to remove special chars
    $filename = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $_FILES['profile_image']['name']);
    $photo = 'uploads/' . $filename;

    move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $filename);

    // Redirect to avoid form resubmission
    header("Location: ../user_account.php?updated=1");
    exit();
}
?>

<?php
require_once("tools.php");

class Admin
{
    static function addUsers($name, $email, $age, $role, $password, $user_id, $photo, $status)
    {
        $conn = Database::connect();


        // Insert into database
        $sql1 = "INSERT INTO account (Name, Email, Age, Role, Password, Photo, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("ssissss", $name, $email, $age, $role, $password, $photo, $status);
        $stmt1->execute();

        $user_id = $conn->insert_id;

        $sql2 = "INSERT INTO user (Name, Photo, Password) 
            VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("sss", $name, $photo, $password);
        $stmt2->execute();
        return true;

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

    static function updateUserAcc($user_id, $name, $age, $email, $password, $photo)
    {
        $conn = Database::connect();
        $conn->begin_transaction();
        if ($photo) {
            $sql1 = "UPDATE account SET Name = ?, Age = ?, Email = ?, Password = ?, Photo = ? WHERE User_Id = ?";
            $query1 = $conn->prepare($sql1);
            $query1->bind_param("sisssi", $name, $age, $email, $password, $photo, $user_id);

            $sql2 = "UPDATE user SET Name = ?, Password = ?, Photo = ? WHERE User_Id = ?";
            $query2 = $conn->prepare($sql2);
            $query2->bind_param("sssi", $name, $password, $photo, $user_id);
        } else {
            $sql1 = "UPDATE account SET Name = ?, Age = ?, Email = ?, Password = ? WHERE User_Id = ?";
            $query1 = $conn->prepare($sql1);
            $query1->bind_param("sissi", $name, $age, $email, $password, $user_id);

            $sql2 = "UPDATE user SET Name = ?, Password = ? WHERE User_Id = ?";
            $query2 = $conn->prepare($sql2);
            $query2->bind_param("ssi", $name, $password, $user_id);
        }

        $query1->execute();
        $query2->execute();
        $conn->commit();
        return true;
    }
}
