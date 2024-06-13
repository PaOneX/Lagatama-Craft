<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagatama Craft</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
</head>

<body data-bs-theme="light">
    <div class="container-fluid h_body">
        <div class="row justify-content-between align-items-center mt-2">
            <div class="col-auto">
                <?php
                session_start();
                if (isset($_SESSION["u"])) {
                    $data = $_SESSION["u"];
                ?>
                    <span class="text-danger fw-bold">
                        <i class="bi bi-person-circle"></i>
                        Hi <?php echo $data["fname"]; ?>
                    </span>|
                    <span class="fw-bold signout" onclick="signout();">Signout</span>|
                <?php
                } else {
                ?>
                    <a href="index.php" class="text-decoration-none fw-bold">
                        <i class="bi bi-person-circle"></i>
                        Sign In or Register
                    </a>|
                 
                <?php
                }
                ?>
                <span class="fw-bold">Help and Contact</span>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="cart.php" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-cart-fill"></i>
                </a>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" onclick="themeChange();">
                    <label class="form-check-label" for="themeSwitch">Turn On Dark</label>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="bootstrap.bundle.js"></script>
</body>

</html>