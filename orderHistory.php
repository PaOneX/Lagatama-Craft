<?php
session_start();
include "connection.php";

if (isset($_SESSION["u"])) {
    $user = $_SESSION["u"];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>History | Lagatama Craft</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    </head>

    <body class="body002">
        <div class="bg-light rounded-2">
            <div class="row ms-3">
                <div class="col-12 col-lg-5 align-self-start mt-2">
                    <span class="text-danger fw-bold">
                        <i class="bi bi-person-circle me-2"></i>
                        Hi <?php echo $user["fname"]; ?>
                    </span>|
                    <span class="fw-bold text-primary me-2 signout" onclick="signout();">Signout</span>|
                </div>
            </div>
        </div>
        <!-- Nav Bar -->
        <?php include "navBar.php"; ?>
        <!-- Nav Bar -->
        <div class="container mt-5">
            <div class="row border border-5 p-5 rounded-5 boxOh" style="margin-top: 30px;">
                <div class="mt-2 mb-3">
                    <h1 class="textX2 text-center" style="color:yellow;  text-shadow: 2px 2px 4px #000, -2px -2px 4px #f00, 0px 0px 4px #00f;">Order History</h1>
                </div>
                <?php
                $rs = Database::search("SELECT * FROM `order_history` WHERE `user_id`='" . $user["id"] . "'");
                $num = $rs->num_rows;

                if ($num > 0) {
                    while ($d = $rs->fetch_assoc()) {
                        $order_id = $d["order_id"];
                        // Initialize total amount for each order
                        $total_amount = 0;
                ?>
                        <!-- Order history card -->
                        <div class="p-3 border border-3 rounded-5 mb-4 boX1">
                            <div>
                                <h5 class="textX2">Order ID <span class="textX1"># <?php echo $order_id; ?></span></h5>
                                <p class="textX5"><?php echo $d["order_date"]; ?></p>
                            </div>
                            <!-- table -->
                            <div class="ps-5 pe-5 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-danger" scope="col">Product Name</th>
                                            <th class="text-danger" scope="col">Quantity</th>
                                            <th class="text-danger" scope="col">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $rs2 = Database::search("SELECT * FROM `order_items` INNER JOIN
                                            `stock` ON  `order_items`.`stock_stock_id`=`stock`.`stock_id`
                                            INNER JOIN `product` ON `stock`.`stock_id`=`product`.`id` WHERE `order_items`.`order_history_oh_id`='" . $d["oh_id"] . "';");

                                        $num2 = $rs2->num_rows;

                                        for ($x = 0; $x < $num2; $x++) {
                                            $d2 = $rs2->fetch_assoc();
                                            // Add the product price to the total amount
                                            $total_amount += $d2["price"] * $d2["oi_qty"];
                                        ?> <tr>
                                                <td><?php echo $d2["name"];?></td>
                                                <td><?php echo $d2["oi_qty"];?></td>
                                                <td><?php echo $d2["price"] * $d2["oi_qty"];?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- table --> 
                            </div>
                            <div class="d-flex flex-column align-items-end pe-5">
                                <h6 class="textX2">Delivery Fee: 500</h6>
                                <!-- Display total amount for the order -->
                                <h4 class="text-primary">Net Total: <span class="text-primary"><?php echo $total_amount + 500; ?></span></h4>
                            </div>
                            <button class="btn btn-dark mb-3 ms-3" onclick="window.location.href='invoice.php?orderId=<?php echo $d["oh_id"]; ?>'">Invoice</button>
                        </div>
                        <!-- Order history card -->
                    <?php
                    }
                } else {
                    ?>
                    <div class="col-12 text-center mt-5">
                        <h2 class="textX3">You have not placed any order !</h2>
                        <a href="index.php" class="btn btn-danger mt-5">Start shopping</a>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>
        <script src="script.js"></script>
        <script src="bootstrap.bundle.js"></script>
    </body>

    </html>
<?php
} else {
    header("location: home.php");
    exit();
}
?>