<div class="container-fluid">
<div class="bg-light rounded-2">
        <div class="row">
            <div class="col-12 col-lg-5 align-self-start mt-2">
                <?php
                session_start();
                if (isset($_SESSION["a"])) {
                    $data = $_SESSION["a"];
                ?>
                    <span class="text-lg-start ms-3 text-danger fw-bold"><i class="bi bi-person-circle"></i><b>Welcome Admin User : <?php echo $data["fname"]; ?></b></span> |
            </div>
            <div class="col-12 col-lg-1 offset-lg-5 mb-0">
                <a href="adminSignIn.php" class="fw-bold text-lg-end signout mb-0" onclick="adminSignout();">Signout
                    <i class="me-2 bi bi-box-arrow-right"></i></a>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>