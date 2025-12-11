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
                        <button class="btn btn-dark" id="login-btn">Login</button>
                        <button class="btn btn-dark" id="logout-btn" hidden>Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- hero -->
    <section class="hero">
        <div class="container hero-container d-flex flex-column m-0 p-0">

            <img src="./assets/imgs/public/hero-img.png" class="img-fluid px-4" alt="">

            <div class="row hero-info w-100 p-5 gap-0 ">
                <div class="col-md-4 col-xs-12 text-center">
                    <p class="Heading-2">Simply Unique <span class="o4">/</span></p>
                    <p class="Heading-2">Simply Better<span class="o4">.</span></p>
                </div>

                <div class="col-md-8 col-xs-12 text-end p-5">
                    <p class="paragraph o4"><span class="o7">3legant</span> is a gift & decorations store based in HCMC,
                        Vietnam. Est since 2019. </p>
                </div>
            </div>

        </div>
    </section>

    <!-- posters -->
    <section class="poster  mb-5">
        <div class="container">
            <div class="row">

                <div class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                    <img id="myImage" src="./assets/imgs/poster/Card1.webp" class="img-fluid" alt="">
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="row pb-4">
                        <div class="col-12">
                            <img id="myImage1" src="./assets/imgs/poster/Card2.webp" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <img id="myImage2" src="./assets/imgs/poster/Card3.webp" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!--Products iteam-->
    <section class="m-5">
        <div class="container">
            <!-- row1 -->
            <div class="row g-4 mb-4">
                <!--card1-->
                <div class="col-xs-12 col-6 col-md-4 col-lg-3">
                    <div class="card h-100 d-flex flex-column">
                        <img src="./assets/imgs/productsPage/Productsiteam/Loveseat sofa.webp" class="card-img-top"
                            alt="Loveseat sofa">
                        <div class="card-body">
                            <h5 class="card-title Heading-4">Loveseat Sofa</h5>
                            <span class="paragraph">$199.0</span>
                            <form action="php/add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="1">
                                <input type="hidden" name="product_price" value="199.0">
                                <input type="hidden" name="product_qty" value="1" id="product-qty-1">
                                <button class="btn btn-dark mt-auto w-100">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--card2-->
                <div class="col-xs-12 col-6 col-md-4 col-lg-3">
                    <div class="card h-100 d-flex flex-column">
                        <img src="./assets/imgs/productsPage/Productsiteam/Luxury Sofa.webp" class="card-img-top"
                            alt="Luxury Sofa">
                        <div class="card-body">
                            <h5 class="card-title Heading-4"> Luxury Sofa</h5>
                            <span class="paragraph">$299.0</span>
                            <form action="php/add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="2">
                                <input type="hidden" name="product_price" value="299.0">
                                <input type="hidden" name="product_qty" value="1" id="product-qty-2">
                                <button class="btn btn-dark mt-auto w-100">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--card3-->
                <div class="col-xs-12 col-6 col-md-4 col-lg-3">
                    <div class="card h-100 d-flex flex-column">
                        <img src="./assets/imgs/productsPage/Productsiteam/Table lamp.webp" class="card-img-top"
                            alt="Table lamp">
                        <div class="card-body">
                            <h5 class="card-title Heading-4">Table Lamp</h5>
                            <span class="paragraph">$19.0</span>
                            <form action="php/add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="3">
                                <input type="hidden" name="product_price" value="19.0">
                                <input type="hidden" name="product_qty" value="1" id="product-qty-3">
                                <button class="btn btn-dark mt-auto w-100">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--card4-->
                <div class="col-xs-12 col-6 col-md-4 col-lg-3">
                    <div class="card h-100 d-flex flex-column">
                        <img src="./assets/imgs/productsPage/Productsiteam/Cozy_sofa.webp" class="card-img-top"
                            alt="Cozy_sofa">
                        <div class="card-body">
                            <h5 class="card-title Heading-4">Cozy Sofa</h5>
                            <span class="paragraph">$299.0</span>
                            <form action="php/add-to-cart.php" method="POST">
                                <input type="hidden" name="product_id" value="4">
                                <input type="hidden" name="product_price" value="299.0">
                                <input type="hidden" name="product_qty" value="1" id="product-qty-4">
                                <button class="btn btn-dark mt-auto w-100">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </div>

    </section>

    <!-- card secvices -->
    <section class="cards mt-5 mb-5">
        <div class="container">
            <div class="row gx-3 gy-3">

                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card custom-card border-0">
                        <div class="card-body text-center p-3">
                            <img src="./assets/imgs/icons/shipping and delivery/outline/fast delivery.svg" alt=""
                                class="mb-3">
                            <p class="Heading-6 fw-bold">Free Shipping</p>
                            <p class="paragraph-small">Order above $200</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card custom-card border-0">
                        <div class="card-body text-center p-3">
                            <img src="./assets/imgs/icons/finance and payment/outline/money.svg" alt="" class="mb-3">
                            <p class="Heading-6 fw-bold">Money-back</p>
                            <p class="paragraph-small">30 days guarantee</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card custom-card border-0">
                        <div class="card-body text-center p-3">
                            <img src="./assets/imgs/icons/outline/lock 01.svg" alt="" class="mb-3">
                            <p class="Heading-6 fw-bold">Secure Payments</p>
                            <p class="paragraph-small">Secured by Stripe</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card custom-card border-0">
                        <div class="card-body text-center p-3">
                            <img src="./assets/imgs/icons/outline/call.svg" alt="" class="mb-3">
                            <p class="Heading-6 fw-bold">24/7 Support</p>
                            <p class="paragraph-small">Phone and Email support</p>
                        </div>
                    </div>
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
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/entery.js"></script>
    
</body>

</html>