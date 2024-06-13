<?php

    include "connection.php";

    $category = $_POST["cat"];

    if (empty($category)) {
        echo ("Please Enter Your Category");
    } else {
        $rs = Database::search("SELECT * FROM `category` WHERE `cat_name`= '".$category."' ");
        $num = $rs->num_rows;

        if ($num > 0) {
            echo ("Category name Already Exists.");
        } else {
            Database::iud("INSERT INTO `category` (`cat_name`) VALUES('".$category."')");
            echo("Success");
        }   
    }
    
?>