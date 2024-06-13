<?php
session_start();
include "connection.php";

if (isset($_SESSION["a"])) {
    // Check if a date is provided for filtering
    $filterDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Modify the SQL query to filter by the provided date and sort by order date in descending order
    $query = "SELECT oh.order_date AS Order_Date, p.name AS Product_Name, oi.oi_qty AS Quantity, s.price AS Price, (oi.oi_qty * s.price) AS Total_Price
              FROM order_history oh
              INNER JOIN order_items oi ON oh.oh_id = oi.order_history_oh_id
              INNER JOIN stock s ON oi.stock_stock_id = s.stock_id
              INNER JOIN product p ON s.product_id = p.id
              WHERE DATE(oh.order_date) = '$filterDate'
              ORDER BY oh.order_date DESC";

    $rs = Database::search($query);

    $num = $rs->num_rows;

    // Initialize net total
    $netTotal = 0;
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <title>Product Report</title>
    </head>

    <body class="adminBody4 p-3">

        <a href="report.php"><button class="btn btn-dark mx-5 my-5 col-lg-1"><i class="bi bi-arrow-left"></i> Go Back</button></a>

        <div class="justify-content-center align-content-center d-flex border border-5 p-3 border-black rounded-5">
            <div class="container-fluid col-12">
                <h2 class="text-center text-black">Sales Report</h2>

                <!-- Form for selecting date and filtering sales data -->

                <form action="" method="GET" class="mb-3">
                    <div class="row col-6">
                        <div class="col-md-3">
                            <label for="date">Select Date:</label>
                            <input class="form-control" type="date" id="date" name="date" value="<?php echo $filterDate; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="date"></label>
                            <button type="submit" class="btn btn-dark col-12">Filter</button>
                        </div>
                    </div>
                </form>


                <div class="table-responsive">
                    <table class="table table-hover table-striped mt-4">
                        <thead class="table-dark">
                            <tr>
                                <th>Order Date & Time</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="table-warning">
                            <?php
                            for ($i = 0; $i < $num; $i++) {
                                $d = $rs->fetch_assoc();
                                // Calculate total price for each item
                                $totalPrice = $d["Total_Price"];
                                // Add to net total
                                $netTotal += $totalPrice;
                            ?>
                                <tr>
                                    <td><?php echo $d["Order_Date"] ?></td>
                                    <td><?php echo $d["Product_Name"] ?></td>
                                    <td><?php echo $d["Quantity"] ?></td>
                                    <td><?php echo $d["Price"] ?></td>
                                    <td><?php echo $totalPrice ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end text-danger">Net Total:</th>
                                <th class="text-success"><?php echo $netTotal; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-outline-dark col-lg-3 mt-3" onclick="window.print()">Print</button>
                </div>
            </div>
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