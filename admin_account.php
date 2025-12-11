<?php
session_start();
require_once('./php/tools.php');

$conn = Database::connect();
$result = $conn->query("SELECT * FROM account WHERE role = 'user'"); // lowercase 'role'

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <!-- main css -->
    <link rel="stylesheet" href="./assets/css/main.css">
    <!--account.css-->
    <link rel="stylesheet" href="./assets/css/account.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php
    if (isset($_SESSION['username']) && isset($_SESSION['password']))
    ?>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light px-4 ">
        <div class="container">
            <a class="navbar-brand me-5 ms-5 Heading-2" href="./index.php">3legant</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto  mb-2 mb-lg-0 d-flex gap-4">
                    <li class="nav-item px-5">
                        <a class="nav-link active" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item px-5">
                        <a class="nav-link" href="./shop.php">Shop</a>
                    </li>
                    <li class="nav-item px-5">
                        <a class="nav-link" href="./products.php">Product</a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3 ms-auto ">
                    <?php if ($_SESSION['role'] === 'admin') { ?>
                        <a href="./admin_account.php" class="btn btn-link nav-icon p-0">
                            <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                        </a>
                    <?php } else { ?>
                        <a href="./user_account.php" class="btn btn-link nav-icon p-0">
                            <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                        </a>
                    <?php } ?>
                    <a href="./cart.php" class="btn btn-link nav-icon p-0">
                        <img src="./assets/imgs/icons/Elements/Navigation/Cart Button.svg" alt="Cart">
                    </a>
                    <div id="auth-buttons" class="d-flex align-items-center gap-3">
                        <button class="btn btn-dark" id="login-btn">
                            <a class="text-decoration-none text-white" href="./login.php">Login</a>
                        </button>
                        <button class="btn btn-dark" id="logout-btn" hidden>Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!--Account Body-->
    <section>
        <div class="container">
            <div class="row m-5">
                <div class="col-12 text-center">
                    <h2 class="Heading-2 fs-1">My Account</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-3 ">
                    <form>
                        <div class="profile-card">
                            <!--should receive from php-->
                            <div class="profile-image">
                                <img src="./assets/imgs/Account/Protofile sample.png" alt="Profile Image">
                            </div>
                            <!--should receive from php-->
                            <h3 class="profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>

                            <div class="section-title">Account</div>
                            <hr class="divider">

                            <ul class="menu">
                                <li><a href="cart.php">Cart</a></li>
                                <li><a href="login.php">Log Out</a></li>
                            </ul>
                        </div>
                    </form>

                </div>
                <!--detalis section-->
                <div class="col-12 col-sm-9">
                    <div class="account-container">

                        <!-- CURRENT ACCOUNT INFO -->
                        <h2 class="mb-4">Account Information</h2>
                        <form>
                            <div class="info-box">
                                <!--should receive from php-->
                                <p><strong>Name:</strong> <span><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
                                <!--should receive from php-->
                                <p><strong>age:</strong> <span><?= isset($_SESSION['age']) ? (int)$_SESSION['age'] : 'N/A'; ?></span></p>
                                <!--should receive from php-->
                                <p><strong>Email:</strong> <span><?php echo htmlspecialchars($_SESSION['email']); ?></span></p>

                            </div>
                        </form>

                        <button id="editBtn" class="btn btn-dark my-3">View Users</button>


                        <!-- Users Table (Initially Hidden) -->
                        <div id="editForm" class="account-container hidden">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3>Users</h3>
                            </div>

                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead>
                                    <tr>
                                        <th width="15%">User Name</th>
                                        <th width="10%">Age</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Password</th>
                                        <th width="10%">Photo</th>
                                        <th width="10%">Update</th>
                                        <th width="10%">Delete</th>
                                    </tr>
                                </thead>

                                <tbody id="users_table_body">
                                    <?php foreach ($users as $user): ?>
                                        <!-- Read-only view -->
                                        <td class="view"><?= htmlspecialchars($user['Name']); ?></td>
                                        <td class="view"><?= (int)$user['Age']; ?></td>
                                        <td class="view"><?= htmlspecialchars($user['Email']); ?></td>
                                        <td class="view"><?= htmlspecialchars($user['Password']); ?></td>
                                        <td class="view"><?= htmlspecialchars($user['Photo']); ?></td>

                                        <!-- Actions -->
                                        <td class="view">
                                            <button type="button" class="btn btn-warning btn-sm edit-btn">Update</button>
                                        </td>

                                        <!-- Hidden editable form (initially hidden) -->
                                        <td class="edit" style="display:none;" colspan="5">
                                            <form method="POST" action="./php/admin.php" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                                                <input type="hidden" name="account_type" value="admin">
                                                <input type="hidden" name="update_user_id" value="<?= $user['User_ID']; ?>">

                                                <input type="text" name="name" value="<?= htmlspecialchars($user['Name']); ?>" class="form-control" placeholder="Name">
                                                <input type="number" name="age" value="<?= (int)$user['Age']; ?>" class="form-control" placeholder="Age">
                                                <input type="email" name="email" value="<?= htmlspecialchars($user['Email']); ?>" class="form-control" placeholder="Email">
                                                <input type="text" name="password" value="<?= htmlspecialchars($user['Password']); ?>" class="form-control" placeholder="Password">
                                                <input type="text" name="photo" value="<?= htmlspecialchars($user['Photo']); ?>" class="form-control" placeholder="Photo Name">
                                                <button type="submit" class="btn btn-success btn-sm">Save</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" action="./php/admin.php">
                                                <input type="hidden" name="delete_user_id" value="<?= htmlspecialchars($user['User_ID']); ?>">
                                                <input type="hidden" name="user_id" value="<?= $user['User_ID']; ?>">
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                        

                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <button id="show-form-btn" class="btn btn-primary" >Add New User</button>
                                            <div id="user-form" class="d-none mt-3">
                                                <form method="POST" action="./php/admin.php" class="d-flex gap-3 align-items-center">
                                                    <input type="hidden" name="create_new_user" value="1">

                                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                                    <input type="number" name="age" class="form-control" placeholder="Age" required>
                                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                                    <input type="text" name="password" class="form-control" placeholder="Password" required>
                                                    <input type="text" name="photo" class="form-control" placeholder="Photo Name">

                                                    <button type="submit" class="btn btn-success">Add New User</button>
                                                </form>
                                            </div>

                                        </tr>
                                </tbody>

                            </table>
                        </div>

                    </div>

                    <script>
                        document.getElementById("editBtn").addEventListener("click", function() {
                            document.getElementById("editForm").classList.toggle("hidden");
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer mt-5">
        <div class="container">
            <div class="footer-row">

                <div class="footer-col ">
                    <span class="Heading-4 ">3legant</span>
                </div>

                <div class="footer-col">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 p-4"><a href="./index.html" class="Heading-6">Home</a></div>
                        <div class="col-lg-3 col-md-6 p-4"><a href="./shop.html" class="Heading-6 ">Shop</a></div>
                        <div class="col-lg-3 col-md-6 p-4"><a href="./products.html" class="Heading-6 ">Product</a></div>
                        <div class="col-lg-3 col-md-6 p-4"><a href="./contactus.html" class="Heading-6 ">Contact Us</a></div>

                    </div>
                </div>




            </div>
        </div>
    </footer>



    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS file here -->
    <script src="assets/js/main.js"></script>
    <script src="./assets/js/entery.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const viewCells = row.querySelectorAll('.view');
                const editCell = row.querySelector('.edit');

                // Hide read-only cells
                viewCells.forEach(cell => cell.style.display = 'none');

                // Show editable form
                editCell.style.display = 'table-cell';
            });
        });
    </script>
    <script>
document.getElementById("show-form-btn").addEventListener("click", function () {
    document.getElementById("user-form").classList.remove("d-none");
});
</script>

</body>

</html>