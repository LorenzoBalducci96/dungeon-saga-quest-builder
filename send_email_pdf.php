<?php
    require_once("../wp-load.php");
    $email_to = "unexistent@unexistentjjj.it";
    $response = json_decode(file_get_contents('php://input'), true);
    //echo wp_verify_nonce( $response['security'], 'send_pdf_nonce');
    if(wp_verify_nonce( $response['security'], 'send_pdf_nonce')){
        if(is_user_logged_in()){     
            $pdf_base64 =  explode(',', $response['pdf'])[1];
            $data = base64_decode($pdf_base64);
            $fileName = $response['authorEmail'];//filename = mail@mail.it
            $actualFileName = $fileName;
            $count = 0;
            while(file_exists('./STORED_PDF/' . $actualFileName . ".pdf")) {//testing if exist ./STORED_PDF/mail@mail.it_X.pdf
                $actualFileName = $fileName . "_" . $count;
                $count++;
            }
            $fileName = $actualFileName . ".pdf";
            //here filename could be mail@mail.it_3.pdf with respect to the parallelism reached
            //should always not find any pending file...
            $filePath = "./STORED_PDF/" . $fileName; //filepath = /home/wordpress/STORED_PDF/mail@mail.it.pdf
            file_put_contents($filePath, $data);
            $attachments = array($filePath);
            //echo json_encode(array('status'=>'200'));
            if(wp_mail( $email_to, "dungeon saga mission", 
                    "custom mission created by community",$headers, $attachments)){
                echo json_encode(array('status'=>'200'));//sent
            }else{
                echo json_encode(array('status'=>'500'));//unexpected error
            }
            unlink($filePath);
        }else{
            echo json_encode(array('status'=>'401'));//not logged
        }    
    }else{
        echo json_encode(array('status'=>'440'));;//unvalid nonce
    }
    die()
?>