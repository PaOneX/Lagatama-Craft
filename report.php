<?php
session_start();

if (isset($_SESSION["a"])) {
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    <title>Lagatama Craft | Admin Report</title>
    <style>
        .card {
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background-color: #f8f9fa;
        }
        .card-img-top {
            transition: transform 0.3s;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .card-text {
            transition: color 0.3s;
        }
        .card:hover .card-text {
            color: #d9534f;
        }
    </style>
</head>

<body class="adminBody">
    <?php include "adminNavBar.php"; ?>
    <div class="container-fluid mt-5">
        <div class="row d-flex justify-content-between align-items-center ms-5">
            <h2 class="text-center mt-5">Reports</h2>
            <div class="row mt-5">
                <div class="col-12 col-lg-4">
                    <!-- Card -->
                    <a href="adminReportUser.php" class="btn">
                        <div class="card border border-4 border-white rounded-5 shadow" style="width: 18rem;">
                            <img src="resources/userManage.jpg" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text text-center fw-bolder">User Report</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-4">
                    <a href="adminReportProduct.php" class="btn">
                        <div class="card border border-4 border-white rounded-5 shadow" style="width: 18rem;">
                            <img src="resources/productManag.jpg" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text text-center fw-bolder">Product Report</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-4">
                    <a href="adminReportStock.php" class="btn">
                        <div class="card border border-4 border-white rounded-5 shadow" style="width: 18rem;">
                            <img src="resources/stockManag.png" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text text-center fw-bolder">Stock Report</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-12 col-lg-4">
                    <a href="adminReportSales.php" class="btn">
                        <div class="card border border-4 border-white rounded-5 shadow" style="width: 18rem;">
                            <img src="resources/stockManag.png" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text text-center fw-bolder">Sales Report</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>

<?php
} else {
    echo ("You're not a valid admin");
}
?>
