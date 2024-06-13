<?php

session_start();

if (isset($_SESSION["a"])) {
    $data = $_SESSION["a"];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
        <title>Lagatama Craft || User Management</title>
    </head>

    <body class="" onload="loadUser();">
        <!-- nav bar -->


        <div class="col-12">
            <span class="text-lg-start ms-3 text-danger fw-bold"><i class="bi bi-person-circle"></i><b>Welcome Admin User : <?php echo $data["fname"]; ?></b></span> |

            <a href="adminSignIn.php" class="fw-bold text-lg-end signout mb-0" onclick="adminSignout();">Signout
                <i class="me-2 bi bi-box-arrow-right"></i></a>
        </div>

        <?php include "adminNavBar.php";
        ?>
        <!-- nav bar -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-10 offset-1 mt-lg-5 mt-6">
                    <h2 class="text-center">User Management</h2>
                    <hr />
                    <div class="row mt-4 d-flex justify-content-end">                       
                        <div class="col-12 col-lg-2 mt-lg-0 mt-2">
                            <input type="text" class="form-control" placeholder="User Id" id="uid" />
                        </div>

                        <button class="btn btn-outline-info col-12 col-lg-2 mt-lg-0 mt-2" onclick="updateUserStatus();">Change Status</button>
                    </div>

                    <div class="mt-3 table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">User ID</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Join Date</th>

                                </tr>
                            </thead>
                            <tbody id="tb">
                                <!-- Table Row -->
                                <!-- Table Row -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>







        <!-- footer -->
        <div class=" col-12">
            <?php include "footer.php"; ?>
        </div>
        <!-- footer -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </body>

    </html>

<?php
    // Load Page
} else {
    echo ("You are not a Valid Admin");
}

?>