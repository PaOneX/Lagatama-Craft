<?php

session_start();
include "connection.php";

$user = $_SESSION["u"];

if (isset($user)) {
    //Load Cart

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lagatama Craft | Cart</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    </head>

    <body onload="loadCart();">

        <div class="row ms-3">
            <div class="col-12 col-lg-5 align-self-start mt-2">
                <span class="text-danger fw-bold">
                    <i class="bi bi-person-circle "></i>
                    Hi <?php echo $user["fname"]; ?>
                </span>|
                <span class="fw-bold signout me-2" onclick="signout();">Signout</span>|
            </div>
        </div>


        <?php
        include "navBar.php";
        ?>

        <div class="container mt-5">
            <div class="row" id="cartBody">

                <!-- cart  -->
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    </body>

    </html>

<?php
} else {
    header("location: index.php");
}

?>