<?php

include "connection.php";
session_start();
$user = $_SESSION["u"];

$no = $_POST["no"];
$line1 = $_POST["l1"];
$line2 = $_POST["l2"];

if (empty($no)){
    echo ("Please Enter Your no");
} else if (empty($line1)) {
    echo ("Please Enter Your Address line 01");
} else if (strlen($line1) > 50) {
    echo ("Your Address Line 01 Should be less than 50 Charaters");
} else if (empty($line2)) {
    echo ("Please Enter Your Address Line 02");
} else if  (strlen($line2) > 50) {
    echo ("Your Address Line 02 Should be less than 50 Charaters");
}else{
    $rs = Database::iud("UPDATE `user` SET `no`='".$no."',`line_1` = '".$line1."',`line_2` = '".$line2."' WHERE `id` = '". $user["id"]. "'");

    echo("success");
}
?>