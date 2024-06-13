<?php

include "connection.php";


$user = $_POST["user"];
// echo($user); 

$rs = Database::search("SELECT * FROM `user` WHERE = `fname` AND `lname` = '".$user."'");
$num = $rs->num_rows;

if (0 < $num) {
    $d = $rs->fetch_assoc();
    ?>

<tr>
    <th scope="row"><?php echo $d["id"]?></th>
    <td><?php echo $d["fname"]?></td>
    <td><?php echo $d["lname"]?></td>
    <td><?php echo $d["email"]?></td>
    <td><?php echo $d["mobile"]?></td>
    <td><?php 
        if ($d["status"] == 1) {
            echo("Active");
        } else {
            echo ("Deactive");
        }

    ?></td>
</tr>

<?php
} else {
    echo ("Incorrect Fist name or Last Name");
}


?>