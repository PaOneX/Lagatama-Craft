<?php

    include "connection.php";

    $clr = $_POST["col"];

    if (empty($clr)) {
        echo ("Please Enter Your Color");
    } else {
        $rs = Database::search("SELECT * FROM `color` WHERE `color_name`= '".$clr."' ");
        $num = $rs->num_rows;

        if ($num > 0) {
            echo ("Color name Already Exists.");
        } else {
            Database::iud("INSERT INTO `color` (`color_name`) VALUES('".$clr."')");
            echo("Success");
        }
        
    }
    

?>