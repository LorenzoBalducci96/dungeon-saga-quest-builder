<?php
    include_once("./config.php");
    require_once($wp_load);

    $response = json_decode(file_get_contents('php://input'), true);
    if($response['type'] == 'login'){
        $nonce = wp_create_nonce('instant_login_dsq');
        echo json_encode(array('status'=>'200', 'nonce'=>$nonce));
    }elseif($response['type'] == 'pdf'){
        $nonce = wp_create_nonce('send_pdf_nonce');
        echo json_encode(array('status'=>'200', 'nonce'=>$nonce));
    }
    die();
?>