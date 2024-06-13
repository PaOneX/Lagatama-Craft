<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="img/SweetT - Copy.png" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminDash.css">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />

    <title>Admin Dashboard | Lagatama Craft</title>
</head>

<body class="adminBody5" onload="loadChart(); loadChart2(); loadChart3();">
    <?php
    session_start();
    if (isset($_SESSION["a"])) {

        include "adminNavBar.php";
    ?>
    <div class="container-fluid p-5">
        <div class="row justify-content-center p-4 align-items-center border border-5 rounded-5" style="margin-top: 100px;">
            <div class="col-lg-3 col-md-6 col-sm-12 p-4 border border-4 rounded-5 my-5 mx-5 bg-white shadow-lg boX11">
                <h2 class="text-center text-black">Daily Income</h2>
                <canvas class="text-white" id="myChart2"></canvas>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 p-4 border border-4 rounded-5 my-5 mx-5 bg-white shadow-lg boX11">
                <h2 class="text-center text-black">Most Sold Product</h2>
                <canvas class="text-white" id="myChart"></canvas>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 p-4 border border-4 rounded-5 my-5 mx-5 bg-white shadow-lg boX11">
                <h2 class="text-center text-black">Most Sold Category</h2>
                <canvas class="text-white" id="myChart3"></canvas>
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="script.js"></script>
</body>

</html>

<?php
    } else {
        header("location:adminLogIn.php");
    }
?>