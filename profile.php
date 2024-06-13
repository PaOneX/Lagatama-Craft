<?php

include "connection.php";
session_start();
$user = $_SESSION["u"];

if (isset($_SESSION["u"])) {

    $rs = Database::search("SELECT * FROM `user` WHERE `id` = '" . $user["id"] . "'");
    $d = $rs->fetch_assoc();

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
        <title>Profile | Lagatama Craft</title>
    </head>

    <body>
        <div class="row ms-3">
            <div class="col-12 col-lg-5 align-self-start mt-2">
                <span class="text-danger fw-bold">
                    <i class="bi bi-person-circle"></i>
                    Hi <?php echo $user["fname"]; ?>
                </span>|
                <span class="fw-bold signout me-2" onclick="signout();">Signout</span>|
            </div>
        </div>


        <?php include "navBar.php" ?>

        <div class="container">
            <div class="border border-3 rounded-4 bg-dark text-white border-white shadow mt-3 mb-5">
                <h2 class="text-center">Profile Details</h2>
            </div>

            <div class="row offset-lg-2">
                <div class="col-12 col-lg-4 d-flex p-3 flex-column  border border-5 me-2 ">
                    <div class="d-flex justify-content-center ">
                        <img src="<?php
                                    if (!empty($d["img_path"])) {
                                        echo $d["img_path"];
                                    } else {
                                        echo ("resources/profileImg/profileImg.png");
                                    }
                                    ?>" class="rounded-circle border border-warning-subtle border-4 shadow" style="width: 300px; height: 270px;" />
                    </div>
                    <div class="mt-3">
                        <label for="from-label">Prifile Image</label>
                        <input type="file" class="form-control" id="imgUploader" />
                    </div>
                    <div>
                        <button class="btn btn-warning col-12 mt-4" onclick="uploadImg();">Upload</button>
                    </div>
                </div>

                <div class=" col-12 col-lg-6 d-flex">
                    <div class="row border border-5 shadow">
                        <div class="col-12 col-lg-6">
                            <label for="form-label">First Name</label>
                            <input type="text" class="form-control" value="<?php echo $d["fname"] ?>" id="fname" />
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="form-label">First Name</label>
                            <input type="text" class="form-control" value="<?php echo $d["lname"] ?>" id="lname" />
                        </div>


                        <div class="col-12 mt-3">
                            <label for="form-label">Email</label>
                            <input type="text" class="form-control" value="<?php echo $d["email"] ?>" id="email" disabled>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">Mobile</label>
                            <input type="text" class="form-control" value="<?php echo $d["mobile"] ?>" id="mobile">
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="form-label">Password</label>
                                <input type="password" class="form-control" value="<?php echo $d["password"] ?>" id="pw" disabled>
                            </div>
                            <div class="col-6 mt-3">
                                <button class="btn btn-dark mt-4" onclick="chnagepw();">Change Password</button>
                            </div>
                        </div>

                        <!-- Change Password Model -->
                        <div class="modal fade" data-bs-theme="dark" id="fpModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5 text-white" id="exampleModalLabel">Reset Your Password</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="row col-12 d-flex my-3 justify-content-center">
                                        <div class="col-7 text-white">
                                            <label class="form-label">Old Password</label>
                                            <div class="input-group">
                                                <input type="password" placeholder="******" class="form-control text-warning border border-light" id="opw" />
                                                <button class="btn btn-outline-light" onclick="showpw5();" id="spb5"><i class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12 d-flex my-3 justify-content-center">
                                        <div class="col-7 text-white">
                                            <label class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password" placeholder="******" class="form-control text-warning border border-light" id="newpw1" />
                                                <button class="btn btn-outline-light" onclick="showpw3();" id="spb3"><i class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12 d-flex justify-content-center">
                                        <div class="col-7 mb-4 text-white">
                                            <label class="form-label">Re-type Password</label>
                                            <div class="input-group ">
                                                <input type="password" placeholder="******" class="form-control  text-warning border border-light" id="renewpw1" />
                                                <button class="btn btn-outline-light" onclick="showpw4()" id="spb4"><i class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-light" onclick="resetPassword2();">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Change Password  -->


                        <h3 class="fst-italic btn text-primary fs-4" onclick="viewShip();">More Details....-></h3>

                        <div class="d-none" id="ship">
                            <h5 class="mt-3 text-center fw-bold">Shipping Address</h5>

                            <div class="row mt-3">
                                <div class="col-3">
                                    <label for="form-label">No:</label>
                                    <input type="text" class="form-control" id="no" value="<?php echo $d["no"] ?>">
                                </div>
                                <div class="col-9">
                                    <label for="form-label">Line 01:</label>
                                    <input type="text" class="form-control" id="line1" value="<?php echo $d["line_1"] ?>">
                                </div>
                                <div class="col-12 mb-1">
                                    <label for="form-label">Line 02:</label>
                                    <input type="text" class="form-control" id="line2" value="<?php echo $d["line_2"] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mb-3">
                            <button class="btn btn-success col-12" onclick="updateData();">Update</button>
                        </div>
                    </div>
                </div>


            </div>




            <!-- footer -->

            <?php include "footer.php"; ?>

            <!-- footer -->


        </div>


        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="script.js"></script>
        <script src="bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
<?php
} else {
    header("location: index.php");
}

?>