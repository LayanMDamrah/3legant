<?php
    include("php/database.php");
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

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <section class="login">
        <div class="container-fluid">
            <div class="row">
    <div class="col-12 col-lg-6">
                    <img src="./assets/imgs/public/login-img.webp" class=" login-img " alt="">
                </div>

                <div class="col-lg-6  col-12 justify-content-start align-items-center p-5">
                    <div class="info-login d-flex flex-column">
                        <p class="Heading-1">
                            Login
                        </p>
                        <p class="Heading-5 mb-4">Don’t have an accout yet?<a href="./sign-up" class="green text-decoration-none "> Sign Up</a></p>
                    </div>

                    <!-- input form -->
                    <form action="./php/login1.php" method="POST" class="p-4 border-0 rounded">

                        <div class="mb-4">

                            <input type="text" name="username" id="username" class="form-control"
                                placeholder="Enter your username or email" required>
                        </div>

                        <div class="mb-4">

                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="login" value="login" class="btn btn-dark border-0 w-100">
                                Login
                            </button>
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </section>



    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS file here -->
    <script src="assets/js/main.js"></script>
</body>

</html>