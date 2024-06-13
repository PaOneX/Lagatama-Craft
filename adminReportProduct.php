<?php
session_start();
include "connection.php";

if (isset($_SESSION["a"])) {

    $rs = Database::search("SELECT * FROM `product` 
    INNER JOIN `brand` ON `product`.`brand_id`=`brand`.`brand_id` 
    INNER JOIN `color` ON `product`.`color_id`=`color`.`color_id` 
    INNER JOIN `category` ON `product`.`category_id`=`category`.`cat_id` 
    INNER JOIN `size` ON `product`.`size_id`=`size`.`size_id` ORDER BY `product`.`id` ASC");

    $num = $rs->num_rows;
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
        <title>Product Report</title>
    </head>

    <body>

    <a href="report.php"><button class="btn btn-dark mx-5 my-5 col-lg-1"><i class="bi bi-arrow-left"></i> Go Back</button></a>

        <div>
        <div class="container mt-3 table-responsive  border border-4 rounded-4 p-5 border-black">
                <h2 class="text-center">Product Report</h2>
                <table class="table table-hover mt-5 border">

                    <thead class="table-dark">
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Brand Name</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Image</th>

                        </tr>
                    </thead>

                    <tbody class="table-info">

                        <?php

                        for ($i = 0; $i < $num; $i++) {
                            $d = $rs->fetch_assoc();
                        ?>
                            <tr>
                                <td><?php echo $d["id"] ?></td>
                                <td><?php echo $d["name"] ?></td>
                                <td><?php echo $d["brand_name"] ?></td>
                                <td><?php echo $d["color_name"] ?></td>
                                <td><?php echo $d["cat_name"] ?></td>
                                <td><?php echo $d["size_name"] ?></td>
                                <td><?php echo $d["description"] ?></td>
                                <td><img src="<?php echo $d["path"] ?>" height="100" /></td>
                            </tr>

                        <?php
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="container mt-3 d-flex justify-content-end">
            <button class="btn btn-outline-dark col-2" onclick="window.print()">Print</button>
        </div>

        <script src="script.js"></script>
        <script src="bootstrap.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </body>

    </html>

<?php
} else {
?>
    <div class="col-12 text-center  mt-5">
        <h2>Your Cart is Empty!</h2>
        <a href="home.php" class="btn btn-primary mt-3">Start Shopping</a>
    </div>
<?php
    // echo ("You are not a valid admin");
}

?>