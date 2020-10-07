<?php
    require_once("../wp-load.php");
    
    $nonce = wp_create_nonce('send_pdf_nonce');
    echo json_encode(array('status'=>'200', 'nonce'=>$nonce));
    
    die();
?>