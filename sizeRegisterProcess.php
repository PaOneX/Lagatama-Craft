<?php

    include "connection.php";

    $size = $_POST["s"];

    if (empty($size)) {
        echo ("Please Enter Your Size");
    } else {
        $rs = Database::search("SELECT * FROM `size` WHERE `size_name`= '".$size."' ");
        $num = $rs->num_rows;

        if ($num > 0) {
            echo ("Size name Already Exists.");
        } else {
            Database::iud("INSERT INTO `size` (`size_name`) VALUES('".$size."')");
            echo("Success");
        }
        
    }
    

?>