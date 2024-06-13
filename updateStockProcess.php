<?php

include "connection.php";

$selProduct = $_POST["sp"];
$qty = $_POST["q"];
$price = $_POST["p"];

// echo($price);

if (empty($selProduct)) {
    echo("Please select select your Product .");
} else if (empty($qty)) {
    echo("Please enter Quantity.");
}else if(!is_numeric($qty)){
    echo("Invalid data type.");
}else if(strlen($qty)>10){   
    echo("Quantity Count Must Be Lower than 10");
}else if (empty($price)){
    echo("Please enter Price");
}else if (!is_numeric($price)){
    echo("Invalid data type.");
}else if(strlen($price)>10){   
    echo("Price Count Must Be Lower than 10");
}else if($qty < 0){
    echo "Quantity must be Positive Number";
}else if($price < 0){
    echo "Please enter the positive Value";
}else {

    $rs = Database::search("SELECT * FROM `stock` WHERE `product_id` = '".$selProduct."' AND `price` = '".$price."'");
    $num = $rs->num_rows;
    $d = $rs->fetch_assoc();

    if ($num == 1){
        // Update Query
        $newQty = $d["qty"] + $qty;
        Database::iud("UPDATE `stock` SET `qty` = '".$newQty."' WHERE `stock_id` = '".$d["stock_id"]."'");
        echo("success");

    } else {
        // Insert Query
        Database::iud("INSERT INTO `stock` (`price`,`qty`,`product_id`) VALUES ('".$price."','".$qty."','".$selProduct."')");
        echo ("New Stock Added Successfully");
    }
}


?>