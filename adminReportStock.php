<?php
session_start();
include "connection.php";

if (isset($_SESSION["a"])) {

    $rs = Database::search("SELECT * FROM `stock` INNER JOIN `product` ON `stock`.`product_id` = `product`.`id` ORDER BY `stock`.`stock_id` ASC");
    $num = $rs->num_rows;
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
        <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <title>Stock Report</title>
    </head>

    <body>

    <a href="report.php"><button class="btn btn-dark mx-5 my-5 col-lg-1"><i class="bi bi-arrow-left"></i> Go Back</button></a>

        <div class="justify-content-center align-content-center d-flex">
            <div class="container mt-3 table-responsive  border border-4 rounded-4 p-5 border-black">
                <h2 class="text-center ">Stock Report</h2><hr>
                <table class="table table-hover mt-5">

                    <thead class="table-dark">
                        <tr>
                            <th>Stock ID</th>
                            <th>Product Name</th>
                            <th>Stock Qty</th>
                            <th>Unit Price</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        for ($i = 0; $i < $num; $i++) {

                            $d = $rs->fetch_assoc();

                        ?>

                            <tr class="table-danger">
                                <td><?php echo $d["stock_id"] ?></td>
                                <td><?php echo $d["name"] ?></td>
                                <td><?php echo $d["qty"] ?></td>
                                <td><?php echo $d["price"] ?></td>
                            </tr>

                        <?php
                        }

                        ?>



                    </tbody>
                </table>
            </div>
        </div>

        <div class="container d-flex justify-content-end mt-5 mb-5 gap-4">
            <button class="btn btn-outline-dark col-2 printbtn" onclick="window.print()">Print</button>
            <!-- <button class="btn btn-outline-danger text-light col-2" >Download</button> -->
        </div>

        <script src="script.js"></script>
        <script src="bootstrap.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>

    </html>

<?php
} else {
    echo ("You are not a valid admin");
}

?>