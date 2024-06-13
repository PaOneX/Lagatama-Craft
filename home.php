<?php

include "connection.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagatama Craft</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
</head>

<body onload="loadProduct(0);" data-bs-theme="light">
    <div class="container-fluid">
        <?php
        include "header.php";
        include "navBar.php";
        ?>
        <div class="row col-12 d-flex justify-content-center">
            <!-- <form class="d-flex search_1" role="search"> -->
            <div class="col-lg-6 col-12 ms-3 mt-2">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="sProduct" onkeyup="searchProduct(0)">
            </div>

            <div class="col-lg-4 col-12 mt-2 mb-3">
                <button class="btn btn-outline-danger col-lg-6 col-12" type="submit" onclick="advSearch();">Advanced Search</button>
            </div>


            <!-- Advances Search -->
            <div class="d-none col-12 col-lg-8" id="filterId">
                <div class="mb-5  opacity-75 rounded-5">
                    <div class="card-body">
                        <div class="border bg-dark border-white border-5 mt-4 p-5  mb-5 rounded-4">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <label class="form-label col-3 text-white">Color</label>
                                    <select class="form-select  col-9 text-center" id="color">
                                        <option value="0">Select color</option>

                                        <?php
                                        $rs1 = Database::search("SELECT * FROM `color`");
                                        $num1 = $rs1->num_rows;

                                        for ($i = 0; $i < $num1; $i++) {
                                            $d1 = $rs1->fetch_assoc();
                                        ?>
                                            <option value="<?php echo $d1["color_id"] ?>"><?php echo $d1["color_name"] ?></option>
                                        <?php
                                        }
                                        ?>


                                    </select>
                                </div>




                                <div class="col-lg-6 col-12 mt-2">
                                    <label class="form-label col-4 text-white">Category</label>
                                    <select class="form-select  col-9 text-center" id="cat">
                                        <option value="0">Select Category</option>

                                        <?php
                                        $rs2 = Database::search("SELECT * FROM `category`");
                                        $num2 = $rs2->num_rows;

                                        for ($i = 0; $i < $num2; $i++) {
                                            $d2 = $rs2->fetch_assoc();
                                        ?>
                                            <option value="<?php echo $d2["cat_id"] ?>"><?php echo $d2["cat_name"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>



                                <div class="col-lg-6 col-md-12 col-sm-12 mt-3">
                                    <label class="form-label col-3 text-white">Brand</label>
                                    <select class="form-select  col-9 text-center" id="brand">
                                        <option value="0">Select Brand</option>
                                        <?php
                                        $rs3 = Database::search("SELECT * FROM `brand`");
                                        $num3 = $rs3->num_rows;

                                        for ($i = 0; $i < $num3; $i++) {
                                            $d3 = $rs3->fetch_assoc();
                                        ?>
                                            <option value="<?php echo $d3["brand_id"] ?>"><?php echo $d3["brand_name"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="col-lg-6 col-md-12 col-sm-12 mt-3 ">
                                    <label class="form-label col-3 text-white">Size</label>
                                    <select class="form-select  col-9 text-center" id="size">
                                        <option value="0">Select Size</option>
                                        <?php
                                        $rs4 = Database::search("SELECT * FROM `size`");
                                        $num4 = $rs4->num_rows;

                                        for ($i = 0; $i < $num4; $i++) {
                                            $d4 = $rs4->fetch_assoc();
                                        ?>
                                            <option value="<?php echo $d4["size_id"] ?>"><?php echo $d4["size_name"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>



                                <div class="col-lg-6 col-md-12 col-sm-12 mt-4">
                                    <input type="text" class="form-control" placeholder="Minimum price" id="min" />
                                </div>

                                <div class="col-lg-6 col-md-12 col-sm-12 mt-4 ">
                                    <input type="text" class="form-control" placeholder="Maximum price" id="max" />
                                </div>

                            </div>
                            <div class="col-12 mt-4 text-center">
                                <button class="btn btn-warning col-lg-3 col-sm-4 col-md-4 " onclick="advSearchProduct(0);"> Search</button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <!-- Advanced Search  -->

            <!-- Load Product -->

            <div class="row col-12 col-lg-10 offset-1" id="pid">

            </div>
            <!-- Load Product -->

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