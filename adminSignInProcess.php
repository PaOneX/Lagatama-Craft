<?php

session_start();
include "connection.php";

    $email = $_POST["e"];
    $password = $_POST["pw"];

    // echo($username);

    if (empty($email)) {
        echo ("Please Enter Your Email Address.");
    } else if (empty($password)) {
        echo ("Please Enter Your Password.");
    }  else {
        
        $rs = Database::search("SELECT * FROM `user` WHERE `email` = '" . $email . "' AND `password` = '" . $password . "'");
        $num = $rs->num_rows;

        if ($num == 1) {
            
            $d = $rs->fetch_assoc();

            if ($d["user_type_id"] == 1) {
                echo ("Success");

                $_SESSION["a"] = $d;
            } else {
                echo ("You Don't Have an Admin Account");
            }
        } else {
            echo ("Invalid Username OR Password");
        }
    }
?>