<?php
session_start();
include "connection.php";

if (isset($_SESSION["u"])) {
    $user = $_SESSION["u"];
    $op1 = $_POST["op1"];
    $np1 = $_POST["n1"];

    $userid = $user["id"];

    if (empty($op1)) {
        echo ("Please Enter Old Your Password");
    }else if (empty($op1)) {
            echo ("Please Enter New Your Password");
    } else if (strlen($np1) < 6) {
        echo ("Your password should be must contain Greater than 8 characters");
    } else if (strlen($np1) > 50) {
        echo ("Your password should be must contain Less than 50 characters");
    } else {

        $rs2 = Database::search("SELECT `password` FROM `user` WHERE `id` = '" . $userid . "'");
        $num = $rs2->fetch_assoc();

        if ($op1 == $num["password"]) {

            // Update the user's password in the database
            Database::iud("UPDATE `user` SET `password` = '" . $np1 . "' WHERE `id` = '" . $user["id"] . "'");
            echo ("Success");
        } else {
            echo "Incorrect old password";
        }
    }
} else {
    echo "error";
}