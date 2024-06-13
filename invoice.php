<?php
session_start();
include "connection.php";

if (!isset($_SESSION["u"])) {
    header("location: signIn.php");
    exit();
}

$user = $_SESSION["u"];
$orderHistoryId = isset($_GET["orderId"]) ? $_GET["orderId"] : null;

// Log the order history ID being used
error_log("Order History ID (invoice): " . $orderHistoryId);

if ($orderHistoryId === null) {
    die("Order ID not provided.");
}

$rs = Database::search("SELECT * FROM `order_history` WHERE `oh_id` = '" . $orderHistoryId . "'");
$num = $rs->num_rows;

if ($num > 0) {
    $d = $rs->fetch_assoc();
    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagatama Craft</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="invoice.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body class="body006">
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 body-main">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <img class="img" alt="Invoice Template" src="resources/invoice.webp" />
                            </div>
                            <div class="col-md-8 text-right">
                                <img src="resources/images/hansi logo jpg.jpg" class=" rounded-circle" height="80px">
                                <h4 style="color: #F81D2D;"><strong>Lagata Craft</strong></h4>
                                <p>353/3,Udumulla, Mulleriyawa, New Town</p>
                                <p>071268077</p>
                                <p>lagatamaCraftt@gmail.com</p>
                            </div>
                            <div class="col-md-8 text-left">
                            <p><?php echo $user["fname"] ?> <?php echo $user["lname"] ?></p>
                                    <p><?php echo $user["mobile"] ?></p>
                                    <p><?php echo $user["no"] ?></p>
                                    <p><?php echo $user["line_1"] ?></p>
                                    <p><?php echo $user["line_2"] ?></p>
                            </div>
                        </div>
                        <br />


                        <div class="border border-4 border-black p-5">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h2 class="fw-bold">INVOICE</h2>
                                    <h5>Order Id: <?php echo $d["order_id"] ?></h5>

                                </div>
                            </div>
                            <br />
                            <!-- <div> -->
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Brand Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="table-warning">
                                    <?php
                                    $rs2 = Database::search("SELECT * FROM `order_items` INNER JOIN `stock` ON `order_items`.`stock_stock_id` = `stock`.`stock_id` INNER JOIN `product` ON `stock`.`product_id` = `product`.`id` INNER JOIN `brand` ON `product`.`brand_id` = `brand`.`brand_id` INNER JOIN `color` ON `product`.`color_id` = `color`.`color_id` INNER JOIN `category` ON `product`.`category_id` = `category`.`cat_id` INNER JOIN `size` ON `product`.`size_id` = `size`.`size_id` WHERE `order_items`.`order_history_oh_id` = '" . $orderHistoryId . "'");
                                    if ($rs2 === false) {
                                    die("Order items query failed: " . Database::$connection->error);
                                    }

                                    $num2 = $rs2->num_rows;
                                    for ($i = 0; $i < $num2; $i++) {
                                    $d2 = $rs2->fetch_assoc();
                                    ?>
                                    <tr>                                
                                        <td><?php echo $d2["name"] ?></td>
                                        <td><?php echo $d2["brand_name"] ?></td>
                                        <td><?php echo $d2["cat_name"] ?></td>
                                        <td><?php echo $d2["color_name"] ?></td>
                                        <td><?php echo $d2["size_name"] ?></td>
                                        <td><?php echo $d2["oi_qty"] ?></td>
                                        <td>Rs.<?php echo ($d2["price"] * $d2["oi_qty"]) ?></td>                                            
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="text-right">
                                    <p>Number of Items: <span class="text-danger"><?php echo $num2 ?></span></p>
                                    <p>Delivery Fee: <span class="text-danger">Rs: 500</span></p>
                                    <p>Net Total: <span class="text-danger">Rs: <?php echo ($d["amount"]) ?></span></p>
                            </div>
                            <!-- </div> -->
                            <div>
                                <div class="col-md-12">
                                    <p>Date: <?php echo $d["order_date"] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row col-12 d-flex justify-content-center">
                    <button class="btn btn-warning text-black col-md-3 mx-2" onclick="window.print()">Print</button>
                    <button class="btn btn-light col-md-3 mx-3" onclick="window.location.href='home.php'">Go Back</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js">
        function goHome() {
            window.location.href = 'home.php';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
} else {
    header("location: signIn.php");
    exit();
}
?>