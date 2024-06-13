<?php

include "connection.php";


$fname = $_POST["f"];
$lname = $_POST["l"];
$email = $_POST["e"];
$mobile = $_POST["m"];
$password = $_POST["p"];
$gender = $_POST["g"];

if (empty($fname)) {
    echo ("Please enter your First Name.");
} else if (strlen($fname) > 20) {
    echo ("Character Count Must Be Less Than 20.");
} else if (empty($lname)) {
    echo ("Please enter your Last Name.");
} else if (strlen($fname) > 20) {
    echo ("Character Count Must Be Less Than 20.");
} else if (empty($email)) {
    echo ("Please enter your Email Address.");
} else if (strlen($email) > 100) {
    echo ("Character Count in Email Must Be Less Than 100.");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Your Email Address is Invalid.");
} else if (empty($password)) {
    echo ("Please enter Your Password");
} else if (strlen($password) < 5 || strlen($password) > 45) {
    echo ("Password must contain 5 to 45 Characters.");
} else if (empty($mobile)) {
    echo ("Please enter a mobile number.");
} else if (strlen($mobile) != 10) {
    echo ("Your Mobile Number Should Contain 10 characters");
} else if (!preg_match("/07[0,2,4,5,6,7,8]{1}[0-9]{7}/", $mobile)) {
    echo ("Your mobile number is invalid.");  

} else {

    $rs = Database::search("SELECT * FROM `user` WHERE `email`='".$email."' OR `mobile`='".$mobile."'");
    $n = $rs->num_rows;

    if($n > 0){
        echo ("User with the same Email Address or same Mobile Number already exists.");
    }else{

        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");

        Database::iud("INSERT INTO `user`
        (`fname`,`lname`,`email`,`password`,`mobile`,`joined_date`,`gender_id`,`status`,`user_type_id`) VALUES 
        ('".$fname."','".$lname."','".$email."','".$password."','".$mobile."','".$date."','".$gender."','1','2')");

        echo ("success");

    }

}

?>