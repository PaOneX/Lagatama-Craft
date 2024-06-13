<?php

session_start();
include "connection.php";

if (isset($_SESSION["a"])) {
$data = $_SESSION["a"];

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Management || Lagatama Craft</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    </head>

    <body>

        <div class="bg-light rounded-2">
            <div class="row">
                <div class="col-12 col-lg-5 align-self-start mt-2">
                    <span class="text-lg-start ms-3 text-danger fw-bold"><i class="bi bi-person-circle"></i><b>Welcome Admin User : <?php echo $data["fname"]; ?></b></span> |
                    <span> <a href="adminSignIn.php" class="fw-bold  signout mb-0" onclick="adminSignout();">Signout
                    <i class="me-2 bi bi-box-arrow-right"></i></a></span>
                </div>                
            </div>
        </div>

        <?php include "adminNavBar.php"; ?>
        <div class="container-fluid ">
            <div class="col-12 text-center mt-3 mb-0">
                <h2 class="text-danger fw-bolder"> Product Management</h2>
                <hr />
            </div>
            <div class="product_background p-3">

                <div class="row justify-content-center mt-2 ms-5">
                    <div class="card border-white col-12 bg-transparent col-lg-5 p-2 me-5 rounded-5 text-center">
                        <div class="card-body">
                            <div class="col-lg-8 offset-2">
                                <label class="form-label textX3">Input Brand Name :</label>
                                <input type="text" class="form-control mt-1" id="bName" />
                                <div class="mt-2">
                                    <button class="btn btn-danger col-12" onclick="addBrand();">Add Brand</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card border-white col-12 bg-transparent col-lg-5 mt-2 mt-lg-0 p-2 me-5 rounded-5 text-center">
                        <div class="card-body">
                            <div class="col-8 offset-2">
                                <label class="form-label textX3">Input Category :</label>
                                <input type="text" class="form-control mt-1" id="catName">
                                <div class="mt-2">
                                    <button class="btn btn-danger col-12" onclick="addCategory();">Add Category</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="row justify-content-center mt-4 mb-3 ms-5 ">
                    <div class="card border-white col-12 bg-transparent col-lg-5 mt-2 mt-lg-0 p-2 me-5 rounded-5 text-center">
                        <div class="card-body">
                            <div class="col-8 offset-2">
                                <label class="form-label textX3">Input Size :</label>
                                <input type="text" class="form-control mt-1" id="size">
                                <div class="mt-2">
                                    <button class="btn btn-danger col-12" onclick="addSize();">Add Size</button>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card border-white col-12 bg-transparent col-lg-5 mt-2 mt-lg-0 p-2 rounded-5 ms-2  ">
                        <div class="card-body text-center">
                            <div class="col-8 offset-lg-1 text-center">
                                <label class="form-label textX3">Input Color :</label>
                                <input type="text" class="form-control mt-1" id="clr">
                                <div class="mt-2">
                                    <button class="btn btn-danger col-12" onclick="addColor();">Add Color</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
            </div>
            <hr class/>

        </div>
        <!-- footer -->
        <div class=" col-12">
            <?php include "footer.php"; ?>
        </div>
        <!-- footer -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>

    </html>


<?php
    // Load Page
} else {
    echo ("You are not a Valid Admin");
}

?>