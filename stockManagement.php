<?php

session_start();
include "connection.php";

if (isset($_SESSION["a"])) {
    $data = $_SESSION["a"];

?>

    <!DOCTYPE html>
    <html lang="en" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stock - Admin Panel</title>
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
    </head>

    <body class="adminBody">
        <div class="col-12">
            <span class="text-lg-start ms-3 text-danger fw-bold"><i class="bi bi-person-circle"></i><b>Welcome Admin User : <?php echo $data["fname"]; ?></b></span> |

            <a href="adminSignIn.php" class="fw-bold text-lg-end signout mb-0" onclick="adminSignout();">Signout
                <i class="me-2 bi bi-box-arrow-right"></i></a>
        </div>
        <?php include "adminNavBar.php";
        ?>
        <div class="container-fluid">
            <div id="reg">
                <div class="col-4 mt-4 mb-3">
                    <button class="btn btn-info col-12 button" onclick="changeStockView();">Stock Update</button>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-3\">
                    <div class="row">
                        <h2>Product Registration</h2>
                        <hr />
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div class="card col-5 rounded-4 border border-5 border-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <label for="" class="form-label">Product Name :</label>
                                    <input type="text" class="form-control" placeholder="Enter Your Product Name" id="pname" />
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12 col-lg-6">
                                        <label for="" class="form-label">Brand :</label>
                                        <select name="" class="form-select" id="brand">
                                            <option value="0">Select Brand</option>
                                            <?php

                                            $rs = Database::search("SELECT * FROM `brand`");
                                            $num = $rs->num_rows;

                                            for ($i = 0; $i < $num; $i++) {
                                                $d = $rs->fetch_assoc();
                                            ?>
                                                <option value="<?php echo $d["brand_id"]; ?>"><?php echo $d["brand_name"]; ?></option>
                                            <?php

                                            }

                                            ?>

                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="" class="form-label">Category :</label>
                                        <select name="" class="form-select" id="cat">
                                            <option value="0">Select Category</option>
                                            <?php

                                            $rs2 = Database::search("SELECT * FROM `category`");
                                            $num2 = $rs2->num_rows;

                                            for ($i = 0; $i < $num2; $i++) {
                                                $d2 = $rs2->fetch_assoc();
                                            ?>
                                                <option value="<?php echo $d2["cat_id"]; ?>"><?php echo $d2["cat_name"]; ?></option>
                                            <?php
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12 col-lg-6">
                                        <label for="" class="form-label">Color :</label>
                                        <select name="" class="form-select" id="color">
                                            <option value="0">Select color</option>
                                            <?php

                                            $rs3 = Database::search("SELECT * FROM `color`");
                                            $num3 = $rs3->num_rows;

                                            for ($i = 0; $i < $num3; $i++) {
                                                $d3 = $rs3->fetch_assoc();
                                            ?>
                                                <option value="<?php echo $d3["color_id"]; ?>"><?php echo $d3["color_name"]; ?></option>
                                            <?php
                                            }

                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="" class="form-label">Size :</label>
                                        <select name="" class="form-select" id="size">
                                            <option value="0">Select Size</option>
                                            <?php

                                            $rs4 = Database::search("SELECT * FROM `size`");
                                            $num4 = $rs4->num_rows;

                                            for ($i = 0; $i < $num4; $i++) {
                                                $d4 = $rs4->fetch_assoc();
                                            ?>
                                                <option value="<?php echo $d4["size_id"]; ?>"><?php echo $d4["size_name"]; ?></option>
                                            <?php
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12 mt-2">
                                    <label for="" class="form-label col-12 col-lg-12">Description :</label>
                                    <textarea id="desc" class="col-12 col-lg-12 mt-2" cols="65" rows="5"></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="" class="form-label">Upload product Img</label>
                                    <input id="file" class="form-control" type="file" multiple>
                                </div>
                                <div class="d-grid mt-3">
                                    <button class="btn btn-danger" onclick="regProduct();">Register Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Stock Update  -->
            <div class="d-none" id="update">
                <div class="col-4 mt-4">
                    <button class="btn btn-info col-12 button" onclick="changeStockView();">Product Registration</button>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-1">
                    <div class="row">
                        <h2>Stock Update</h2>
                        <hr />
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div class="card col-5 rounded-3 border border-5 border-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label for="" class="form-label">Product Name</label>
                                    <select class="form-select" id="productSelct">
                                        <option value="0">Select</option>
                                        <?php
                                        $rs = Database::search("SELECT * FROM `product`");
                                        $num = $rs->num_rows;

                                        for ($i = 0; $i < $num; $i++) {
                                            $d = $rs->fetch_assoc();
                                        ?>
                                            <option value="<?php echo ($d["id"]); ?>"><?php echo ($d["name"]); ?></option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                    <div class="col-12 mt-3">
                                        <label for="" class="form-label ">Qty</label>
                                        <input type="text" class="form-control" id="qty">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label for="" class="form-label ">Price</label>
                                        <input type="text" class="form-control" id="price">
                                    </div>
                                    <div class="d-grid mt-5">
                                        <button class="btn btn-danger" onclick="updateStock();">Update Stock</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

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
} else {
?>

    <div>
        <h2 class=''>You Don't have an Admin Account</h2>
        <a href="adminSignIn.php" class="fst-italic">Go TO Admin Sign In</a>
    </div>



<?php
}
?>