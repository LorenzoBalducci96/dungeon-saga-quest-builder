var to_send_jsn_str;
var login_nonce;

function ajaxF(pdfDocument, author_email) {
    pdfBase64 = pdfDocument.output('datauristring'); 
    var jsn_str = JSON.stringify({
        "pdf" : pdfBase64,
        "authorEmail": author_email,
        "security" : document.getElementById("send_pdf_nonce").value
    });
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
                document.getElementById("message_modal_label").innerHTML = "Thank you, the PDF has been sent to the community – the download of the file for your own use will start soon"
                $("#message_modal").modal("show");
                pdfDocument.save('dungeon.pdf');
            }else if(response['status'] == "500"){
                document.getElementById("message_modal_label").innerHTML = "sorry, unknown server error, the PDF was not sent"
                $("#message_modal").modal("show");
            }else{
                alert(request.responseText);
            }
            //document.getElementById("close_modal_loading_button").style.visibility = "";
            //document.getElementById("loading_div").style.visibility = "hidden";
            $("#please_wait_modal").modal("hide");
        }
    }
    request.send(jsn_str);
}

function login(){
    var request = new XMLHttpRequest();
    request.open('POST', 'get_nonce.php', true); // set the request
    //sends data as json
    request.setRequestHeader('Content-type', 'application/json');
    request.onreadystatechange =()=>{
        if(request.readyState ==4){
            response = JSON.parse(request.responseText);
            if(response['status'] == "200"){
                login_nonce = response['nonce'];
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
                            request = new XMLHttpRequest();
                            request.open('POST', 'get_nonce.php', true); // set the request
                            //sends data as json
                            request.setRequestHeader('Content-type', 'application/json');
                            request.onreadystatechange =()=>{
                                if(request.readyState ==4){
                                    response = JSON.parse(request.responseText);
                                    if(response['status'] == "200"){
                                        document.getElementById("send_pdf_nonce").value = response['nonce'];
                                        document.getElementById("message_modal_label").innerHTML = "login done, now you can send the PDF and download your own"
                                        $("#message_modal").modal("show");
                                        $('#instant_login_modal').modal('hide');
                                    }else{
                                        alert(request.responseText);
                                    }
                                }
                            }
                            jsn_data = JSON.stringify({
                                "type" : "pdf"
                            })
                            request.send(jsn_data);
                        }else{
                            alert("unexpected error : " + request.responseText);
                        }
                    }
                }
                jsn_data = JSON.stringify({
                    "username" : document.getElementById("login_email").value,
                    "password" : document.getElementById("login_password").value,
                    "security" : login_nonce
                })
                request.send(jsn_data);

            }
        }
    }
    jsn_data = JSON.stringify({
        "type" : "login"
    })
    request.send(jsn_data);
}