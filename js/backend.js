var to_send_jsn_str;

function ajaxF(jsn_str) {
    to_send_jsn_str = jsn_str;
    var request = new XMLHttpRequest();
    request.open('POST', 'send_email_pdf.php', true); // set the request
    //sends data as json
    request.setRequestHeader('Content-type', 'application/json');

    request.onreadystatechange =()=>{
        if(request.readyState ==4){
            response = JSON.parse(request.responseText);
            if(response['status'] == "401" || response['status'] == "440"){
                $("#instant_login_modal").modal("show");
            }else if(response['status'] == "200"){
                document.getElementById("message_modal_label").innerHTML = "Thank you, the PDF has been sent"
                $("#message_modal").modal("show");
            }else if(response['status'] == "500"){
                document.getElementById("message_modal_label").innerHTML = "sorry, unknown server error, the PDF was not sent"
                $("#message_modal").modal("show");
            }else{
                alert(request.responseText);
            }
            document.getElementById("close_modal_loading_button").style.visibility = "";
            document.getElementById("loading_div").style.visibility = "hidden";
        }
    }
    request.send(jsn_str);
}

function login(){
    var request = new XMLHttpRequest();
    request.open('GET', 'get_nonce.php', true); // set the request
    //sends data as json
    request.setRequestHeader('Content-type', 'application/json');
    request.onreadystatechange =()=>{
        if(request.readyState ==4){
            response = JSON.parse(request.responseText);
            if(response['status'] == "200"){

                document.getElementById("send_pdf_nonce").value = response['nonce'];
                
                request = new XMLHttpRequest();
                request.open('POST', 'ajax_login.php', true); // set the request
                //sends data as json
                request.setRequestHeader('Content-type', 'application/json');
                
                request.onreadystatechange =()=>{
                    if(request.readyState ==4){
                        response = JSON.parse(request.responseText);
                        if(response['status'] == "401"){
                            document.getElementById("message_modal_label").innerHTML = "username or password invalid"
                            $("#message_modal").modal("show");
                        }else if(response['status'] == "200"){
                            document.getElementById("send_pdf_nonce").value = response['nonce'];
                            document.getElementById("message_modal_label").innerHTML = "login done, now you can send the PDF"
                            $("#message_modal").modal("show");
                            $('#instant_login_modal').modal('hide');
                        }else{
                            alert(request.responseText);
                        }
                    }
                }
                jsn_data = JSON.stringify({
                    "username" : document.getElementById("login_email").value,
                    "password" : document.getElementById("login_password").value,
                    "security" : document.getElementById("send_pdf_nonce").value
                })
                request.send(jsn_data);

            }
        }
    }
    request.send();





    
}