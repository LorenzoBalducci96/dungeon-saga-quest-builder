<?php
    require_once("../wp-load.php");
        if(is_user_logged_in()){
            
            $response = json_decode(file_get_contents('php://input'), true);
            $pdf_base64 =  explode(',', $response['pdf'])[1];
            $data = base64_decode($pdf_base64);
            file_put_contents( "./to_send.pdf", $data );
            
            $attachments = array('./to_send.pdf');
            if(wp_mail( "admin@admin.com", "dungeon saga mission", 
                    "custom mission created by community",$headers, $attachments)){
                echo "email sent";
            }else{
                echo "error when sending the email";
            }
            unlink("./to_send.pdf");
        }else{
            echo "401";//not logged
        }    
?>