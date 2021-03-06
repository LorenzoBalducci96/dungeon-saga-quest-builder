<?php
    include_once("./config.php");
    require_once($wp_load);
    
    $response = json_decode(file_get_contents('php://input'), true);
    if(wp_verify_nonce($response['security'], 'instant_login_dsq')){
        $info = array();
        $info['user_login'] = $response['username'];
        $info['user_password'] = $response['password'];
        $info['remember'] = true;

        $user_signon = wp_signon( $info, false);
        if ( is_wp_error($user_signon) ){
            echo json_encode(array('status'=>'401'));
        } else {
            wp_set_current_user($user_signon->ID);
            wp_set_auth_cookie($user_signon->ID, false, false);
            
            //$nonce = wp_create_nonce('send_pdf_nonce');
            echo json_encode(array('status'=>'200'/*, 'nonce'=>$nonce*/));
        }
    }else{
        echo json_encode(array('status'=>'440'));
    }
    die();
?>