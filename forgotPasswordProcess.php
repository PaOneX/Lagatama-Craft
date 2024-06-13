<?php

include "connection.php";

include "SMTP.php";
include "PHPMailer.php";
include "Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

if(isset($_GET["e"])){

    $email = $_GET["e"];

    $rs = Database::search("SELECT * FROM `user` WHERE `email`='".$email."'");
    $n = $rs->num_rows;

    if($n == 1){

        $code = uniqid();
        Database::iud("UPDATE `user` SET `verification_code`='".$code."' WHERE `email`='".$email."'");

        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'opcmicrosoft@gmail.com';
        $mail->Password = 'jpryskykgzccsawg';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('opcmicrosoft@gmail.com', 'Reset Your Password');
        $mail->addReplyTo('opcmicrosoft@gmail.com', 'Reset Your Password');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Lagatama Craft Forgot password Verification Code';
        $bodyContent = 
        '<div style= "display: flex; justify-content: center; align-items: center">
            <div style="background-color: white; border-radius: 12px; padding: 15px;">                
                <h1 style="color: red;">Your Verification Code is <span style ="font-weight: bold; color: green;">'.$code.'</span></h1>
            </div>
        </div>';
        $mail->Body    = $bodyContent;

        if(!$mail->send()){
            echo 'Verification code sending failed.';
        }else{
            echo 'Success';
        }

    }else{
        echo ("Invalid Email Address.");
    }

}else{
    echo ("Please enter your Email Address in Email Field.");
}

?>