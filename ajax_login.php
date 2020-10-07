<?php
    require_once("../wp-load.php");
    $response = json_decode(file_get_contents('php://input'), true);
    
    $info = array();
    $info['user_login'] = $response['username'];
    $info['user_password'] = $response['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false);
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('status'=>'401'));
    } else {
        
        wp_set_current_user($user_signon->ID);
        wp_set_auth_cookie($user_signon->ID);
        
        $nonce = wp_create_nonce('send_pdf_nonce');
        $valid = wp_verify_nonce(  $nonce, 'send_pdf_nonce');
        echo json_encode(array('status'=>'200', 'nonce'=>$nonce));
    }
    die();
?>