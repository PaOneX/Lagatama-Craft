<?php

session_start();
include "connection.php";

if (isset($_SESSION["a"])) {

    $rs = Database::search("SELECT * FROM `user` INNER JOIN `user_type` ON `user`.`user_type_id` = `user_type`.`id` ORDER BY `user`.`id` ASC");
    $num = $rs->num_rows;
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <title>User Report</title>
    </head>

    <body>
    <a href="report.php"><button class="btn btn-dark mx-5 my-5 col-lg-1"><i class="bi bi-arrow-left"></i> Go Back</button></a>

        <div>
            <div class="container mt-3 table-responsive  border border-4 rounded-4 p-5 border-black">
                <h2 class="text-center ">User Report</h2>
                <table class="table table-hover mt-5 textX3 p-3">

                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>User type</th>
                            <th>Status</th>
                            <th>Joined Date</th>

                        </tr>
                    </thead>

                    <tbody class="table-info">
                        <?php 
                        for ($i = 0; $i < $num; $i++) {
                            $d = $rs->fetch_assoc();
                        ?>
                            <tr>
                                <td><?php echo $d["id"]?></td>
                                <td><?php echo $d["fname"]?></td>
                                <td><?php echo $d["lname"]?></td>
                                <td><?php echo $d["email"]?></td>
                                <td><?php echo $d["mobile"]?></td>
                                <td><?php echo $d["type"]?></td>
                                <td><?php 
                                    if ($d["status"] == 1){
                                        echo ("Active");
                                    } else {
                                        echo ("Inactive");
                                    }
                                ?></td>
                                <td><?php echo $d["joined_date"]?></td>

                            </tr>
                        <?php 
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="container mt-3 d-flex justify-content-end">
            <button class="btn btn-outline-dark col-2" onclick="window.print()">Print</button>
        </div>

        <script src="script.js"></script>
        <script src="bootstrap.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>

    </html>


<?php
} else {
    echo ("You are not a Valid Admin");
}
?>