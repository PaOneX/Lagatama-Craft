<?php

include "connection.php";
session_start();


if (isset($_SESSION["u"])) {

    $user = $_SESSION["u"];
}
$stockId = $_GET["s"];

//echo($stockId);

if (isset($stockId)) {

    $q = "SELECT * FROM `stock` INNER JOIN `product` ON `stock`.`product_id` = `product`.`id` INNER JOIN `brand` 
    ON `product`.`brand_id` = `brand`.`brand_id` INNER JOIN `color` ON `product`.`color_id` = `color`.color_id 
    INNER JOIN `category`ON `product`.`category_id` = `category`.cat_id INNER JOIN `size` ON `product`.`size_id` = `size`.`size_id`
    WHERE `stock`.`stock_id`= '" . $stockId . "'";

    $rs = Database::search($q);
    $d =  $rs->fetch_assoc();

?>
    <!DOCTYPE html>
    <html lang="en" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
        <title>Lagatama Craft | Single ProductView</title>
    </head>

    <body>
        <div class="bg-light rounded-2">
            <div class="row">
                <div class="col-12 col-lg-5 align-self-start mt-2">
                    <span class="text-lg-start ms-3 text-danger fw-bold"><i class="bi bi-person-circle"></i><b>Hello : <?php echo $user["fname"]; ?></b></span> |
                </div>
                <div class="col-12 col-lg-1 offset-lg-5 mb-0">
                    <a href="adminSignIn.php" class="fw-bold text-lg-end signout mb-0" onclick="signout();">Signout
                        <i class="me-2 bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- navbar -->
        <?php include "navBar.php" ?>
        <!-- navbar -->
        <div class="container-fliud d-flex justify-content-between align-items-center">
            <div class="row mt-5">

                <div class="col-12 col-lg-4 d-flex justify-content-center offset-lg-1">
                    <img src="<?php echo $d["path"]; ?>" class="card card-img-top mt-2 rounded-3 shadow-lg" width="250px" />
                </div>
                <div class="col-12 col-lg-6 border border-4 border-white p-3">
                    <h2 class="card-text textX3"> <span class="text-warning">Product Name : </span><?php echo $d["name"]; ?></h2>
                    <p class="card-text fs-5 textX3"><span class="text-warning">Brand Name : </span><?php echo $d["brand_name"]; ?></p>
                    <p class="card-text fs-5 textX3"><span class="text-warning">Category Name : </span><?php echo $d["cat_name"]; ?></p>
                    <p class="card-text fs-5 textX3"><span class="text-warning">Color Name : </span><?php echo $d["color_name"]; ?></p>
                    <p class="card-text fs-5 textX3"><span class="text-warning">Description : </span><?php echo $d["description"]; ?></p>

                    <div class="row mt-4 d-flex justify-content-center align-items-center">
                        <div class="col-md-3">
                            <input value="1" type="number" placeholder="Qty" class="form-control" id="qty" min="1" max="<?php echo $d["qty"]; ?>" oninput="checkQuantity(this)" />
                        </div>
                        <div class="col-md-6">
                            <h4 class="textX3">In Stock: <?php echo $d["qty"] ?></h4>
                        </div>
                    </div>
                    <h3 class="mt-3 textX4">Price: LKR. <?php echo $d["price"] ?> </h3>
                    <h6 class="mt-1 mb-2 textX4">Delivery Fee : LKR.500/=  </h6>
                    <div class="d-flex justify-content-center mt-1 mb-4">
                        <?php if ($d["qty"] > 0) { ?>
                            <button class="btn btn-warning col-6" onclick="addtoCart('<?php echo $d['stock_id'] ?>');">Add To Cart</button>
                            <?php if (isset($_SESSION["u"])) { ?>
                                <button class="btn btn-success col-6 ms-2" onclick="buyNow('<?php echo $d['stock_id'] ?>');">Buy Now</button>
                            <?php } else { ?>
                                <button class="btn btn-danger col-6 ms-2" onclick="alert5();">Buy Now</button>
                            <?php } ?>
                        <?php } else { ?>
                            <p class="textX6 fs-3">Out of stock</p>
                        <?php } ?>
                    </div>
                    <span class="fs-3 text-primary btn mt-3" onclick="shipAdd();">Shipping details >> </span>
                    <!-- shipping  -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-center">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Shipping Address</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label">No</label>
                                            <input type="text" class="form-control" id="no1"/>
                                        </div>
                                        <div class="col-8">
                                            <label class="form-label">Line 1</label>
                                            <input type="text" class="form-control" id="line1"/>
                                        </div>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <label class="form-label">Line 2</label>
                                        <input type="text" class="form-control" id="line2"/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="setAddress();">Set Address</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- shipping  -->
                </div>
                <?php include "footer.php"; ?>
            </div>
            <!--footer -->

            <!--footer -->
        </div>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
        <script src="script.js"></script>

    </body>

    </html>
<?php

} else {
    header("location: home.php");
}
?>