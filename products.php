<?php
session_start()
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

    <!-- detalis css -->
    <link rel="stylesheet" href="./assets/css/detalis.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php
    if(isset($_SESSION['username']) && isset($_SESSION['password']))
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
                  
                    <a href="./user.php" class="btn btn-link nav-icon p-0">
                        
                        <img src="./assets/imgs/icons/interface/outline/user-circle-1.svg" alt="User">
                    </a>
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


    <!--Products iteam-->
    <section class="m-5">

        <!--Path-->
        <section>

            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <p class="o4 paragraph">Home > shop > <span class="o5 paragraph">product</span></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Product details -->
        <section>
            <div class="container">
                <div class="row align-items-stretch">

                    <!-- Image -->
                    <div class="col-12 col-sm-12 col-md-6 mb-3 mb-md-0 h-100">
                        <img src="./assets/imgs/detalisPage/Product_detalis.webp" alt="Tray Table"
                            class="img-fluid h-100 w-100 object-fit-cover">
                    </div>

                    <!-- Text -->
                    <div class="col-12 col-sm-12 col-md-6 h-100">
                        <div class="d-flex flex-column h-100 justify-content-between">
                            <h5 class="Heading-5 heading_scale">Tray Table</h5>
                            <p class="paragraph o4 description">
                                Buy one or buy a few and make every space where you sit more convenient.
                                Light and easy to move around with removable tray top, handy for serving snacks.
                            </p>
                            <span class="Heading-5 price_scale">$199.00</span>
                        </div>
                        <hr class="border border-dark border-1">
                        <!--offer time section-->
                        <div class="d-flex flex-column my-5">
                            <p class="paragraph description">Offer expires in:</p>
                            <div class="container-fluid">
                                <div class="row w-100">
                                    <!--first squer-->
                                    <div class="col-3">
                                        <div class="bg-body-secondary Heading-5 time">02</div>
                                        <div class="bg-light o4 time_type">Days</div>
                                    </div>
                                    <!--secound squer-->
                                    <div class="col-3">
                                        <div class="bg-body-secondary Heading-5 time">12</div>
                                        <div class="bg-light o4 time_type">Hours</div>
                                    </div>
                                    <!--thired squer-->
                                    <div class="col-3">
                                        <div class="bg-body-secondary Heading-5 time">45</div>
                                        <div class="bg-light o4 time_type">Minutes</div>
                                    </div>
                                    <!--forth squer-->
                                    <div class="col-3">
                                        <div class="bg-body-secondary Heading-5 time">05</div>
                                        <div class="bg-light o4 time_type">Secounds</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr class="border border-dark border-1">
                        <!--Measurements section-->
                        <div>
                            <div class="d-flex flex-column my-5">
                                <h4 class=" o4">Measurements</h4>
                                <p class=" primary1 fw-semibold fs-4">17 1/2x20 5/8</p>
                            </div>
                        </div>
                        <!--button section-->
                        <div class="container-fluid">
                            <div class="row align-items-center g-3">

                                <!-- Quantity selector -->

                                <!-- form -->
                                <form id="quantity-form" action="php/product.php" method="POST"
                                    class="d-flex align-items-center">

                                    <!-- Hidden fields required by PHP -->
                                    <input type="hidden" name="product_id" value="1"> <!-- Change to real ID -->
                                    <input type="hidden" name="action" id="action-input" value="">

                                    <div class="col-4">
                                        <div class="d-flex justify-content-between align-items-center border rounded p-2"
                                            style="width:120px;">
                                            <button type="button" id="dec-btn" class="btn btn-sm fw-bold">-</button>
                                            <span id="qty-display" class="fw-bold">1</span>
                                            <button type="button" id="inc-btn" class="btn btn-sm fw-bold">+</button>
                                        </div>
                                    </div>
                                </form>


                                <!-- Add to cart -->
                                <div class="col-8">
                                    <button onclick="window.location.href='cart.php'" class="btn btn-dark w-100"
                                        style="padding:0.71rem;">Add to cart</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- Footer -->
        <footer class="custom-footer mt-5">
            <div >
                <div class="footer-row">

                    <div class="footer-col ">
                        <span class="Heading-4 ">3legant</span>
                    </div>

                    <div class="footer-col">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 p-4"><a href="./index.php" class="Heading-6">Home</a></div>
                            <div class="col-lg-3 col-md-6 p-4"><a href="./shop.php" class="Heading-6 ">Shop</a></div>
                            <div class="col-lg-3 col-md-6 p-4"><a href="./products.php" class="Heading-6 ">Product</a>
                            </div>
                           

                        </div>
                    </div>




                </div>
            </div>
        </footer>


        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Link JS file here -->
        <script src="assets/js/counter.js"></script>
        <script src="./assets/js/entery.js"></script>

</body>

</html>