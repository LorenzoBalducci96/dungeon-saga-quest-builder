//var pdfPageWidth =  620;var pdfPageHeight = 876; //75dpi
var pdfPageWidth = 1240; var pdfPageHeight = 1754; //150dpi
//var pdfPageWidth = 1654; var pdfPageHeight = 2339 //200dpi
//var pdfPageWidth =  2480;var pdfPageHeight = 3508; //300dpi
var marginLeft = 0;
var marginTop = 0;
var PDF_export_quality = 0.92; //between 0.1 and 1... 0.9 is about 380kb, 0.8 is about 250kb
var fixed_pdf_quality_for_mail = 0.90;

var pdfBase64;

function ajaxF(jsn_str) {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); // XMLHttpRequest object

    request.open('POST', 'send_email_pdf.php', true); // set the request
    //sends data as json
    request.setRequestHeader('Content-type', 'application/json');
    request.send(jsn_str);

    // Check request status
    // If the response is received completely, alert response
    request.onreadystatechange =()=>{
        if(request.readyState ==4){
            if(request.responseText == "401"){
                
            }
            alert(request.responseText); // coursesweb.net
        }
    }
}

function finishProjectOptions(){
    projectZoom("0");
    activePage(1);
    $("#loading-modal").modal("show");
    window.scrollTo(0,0);
}

function exportProjectToPDF(send_email){
    if(send_email){
        document.getElementById("pdfExportQuality").value = fixed_pdf_quality_for_mail;
    }
    PDF_export_quality = parseFloat(document.getElementById("pdfExportQuality").value);
    document.getElementById("close-modal-button").style.visibility = "hidden";
    document.getElementById("loading-div").style.visibility = "";
    
    let pdfDocument = new jsPDF('potrait');
    
    activePage(1)
    html2canvas(document.getElementById("first-page"),{scrollY: -window.scrollY}).then(function (first_page_canvas) {
        var img = first_page_canvas.toDataURL();
        pdfDocument.addImage(first_page_canvas.toDataURL("image/jpeg", PDF_export_quality), 'JPEG', 0, 0, 210, 297);
        document.getElementById("second-page").style.display = "block";
        pdfDocument.addPage();
        //activePage(2)
        //document.getElementById("second-page").style.transform = "scaleX(-1)";
        html2canvas(document.getElementById("second-page")).then(function (second_page_canvas) {
            canvasContext = second_page_canvas.getContext('2d');
            canvasContext.translate(second_page_canvas.width, 0);
            canvasContext.scale(-1, 1);
            canvasContext.drawImage(second_page_canvas,0,0);
            pdfDocument.addImage(second_page_canvas.toDataURL("image/jpeg", PDF_export_quality), 'JPEG', 0, 0, 210, 297);
            //activeActualPage();

            if(send_email){
                pdfBase64 = pdfDocument.output('datauristring');
                var data = JSON.stringify({
                    "pdf" : pdfBase64
                });
                ajaxF(data);
            }
            pdfDocument.save('dungeon.pdf');
            document.getElementById("close-modal-button").style.visibility = "";
            document.getElementById("loading-div").style.visibility = "hidden";
        });
    });
}

function exportMapToPDF() {
    let pdfDocument = new jsPDF('potrait');
    pdfDocument.addImage(document.getElementById('mapRendered'), 'PNG', 0, 0, 210, 297);//A4 format
    pdfDocument.save('dungeon.pdf');
}

async function createImg(imgOutput) {
    html2canvas(document.getElementById("output")).then(function (canvas) {
        document.getElementById(imgOutput).src = canvas.toDataURL("image/png");
        //document.getElementById('mapRendered').style.display = "inherit";
        //document.getElementById('loadingPDFRotatingSVG').style.display = "none";
        document.getElementById("canvasDragShower").width = 0
        document.getElementById("canvasDragShower").height = 0
        document.getElementById("canvasDragShower").width = 4096
        document.getElementById("canvasDragShower").height = 3084
        ctx = document.getElementById("canvasDragShower").getContext('2d');
        ctx.strokeStyle = 'rgba(255, 0, 0, 2)';
        ctx.lineWidth = 4;
        document.getElementById("output").style.display = "none"
    });
}

function createOutputDivForPrint() {
    document.getElementById("output").innerHTML = "";
    var tilesOnMap = [].slice.call(document.getElementById("map").children);//get all the elements on the map
    for (var i = 0; i < tilesOnMap.length; i++) {
        if (tilesOnMap[i].getAttribute("pieceType") == "placeholder") {
            tilesOnMap.splice(i, 1);
        }
    }
    let minX = 0;
    let minY = 0;
    let maxX = 0;
    let maxY = 0;
    if (tilesOnMap.length > 0) {
        minX = parseInt(tilesOnMap[0].style.left, 10);
        minY = parseInt(tilesOnMap[0].style.top, 10);
        maxX = parseInt(tilesOnMap[0].style.left, 10) + parseInt(tilesOnMap[0].offsetWidth, 10);
        maxY = parseInt(tilesOnMap[0].style.top, 10) + parseInt(tilesOnMap[0].offsetHeight, 10);
    }
    tilesOnMap.forEach(tile => {
        if (tile.offsetTop < minY) {
            minY = parseInt(tile.style.top, 10);
        }
        if (tile.offsetTop + tile.offsetHeight > maxY) {
            maxY = parseInt(tile.style.top, 10) + parseInt(tile.offsetHeight, 10);
        }
        if (tile.offsetLeft < minX) {
            minX = parseInt(tile.style.left, 10);
        }
        if (tile.offsetLeft + tile.offsetWidth > maxX) {
            maxX = parseInt(tile.style.left, 10) + parseInt(tile.offsetWidth, 10);
        }
    });

    var pageWidth = maxX - minX
    var pageHeight = maxY - minY

    //document.getElementById("output").style.width = pdfPageWidth + "px";
    //document.getElementById("output").style.height = pdfPageHeight + "px";

    document.getElementById("output").style.width = pageWidth + "px";
    document.getElementById("output").style.height = pageHeight + "px";


    document.getElementById("output").style.display = "block"
    tilesOnMap.forEach(tile => {
        if (tile.getAttribute("pieceType") == "tile") {//we print just the tiles
            var copyTile = tile.cloneNode(true);
            copyTile.style.width = tile.offsetWidth;
            copyTile.style.height = tile.offsetHeight;
            copyTile.style.left = (tile.offsetLeft - minX + marginLeft)/scale + "px";
            copyTile.style.top = (tile.offsetTop - minY + marginTop)/scale + "px";
            document.getElementById("output").appendChild(copyTile);
        }
        if (tile.getAttribute("pieceType") == "text") {//for the text just print the text and adjust offset height
            //we have to convert to label because html2canvas has a bug for textarea
            var label = document.createElement('label');
            label.style.position = "absolute";
            label.style.top = (tile.offsetTop + tile.childNodes[3].childNodes[1].offsetTop - minY + marginTop)/scale + "px";
            label.style.left = (tile.offsetLeft + tile.childNodes[3].childNodes[1].offsetLeft - minX + marginLeft)/scale + "px";
            label.style.width = tile.childNodes[3].childNodes[1].offsetWidth/scale + "px";
            label.style.height = tile.childNodes[3].childNodes[1].offsetHeight/scale + "px";
            label.style.fontSize = parseInt(tile.childNodes[3].childNodes[1].style.fontSize)/scale + "px";
            /*
            var style = window.getComputedStyle(tile.childNodes[3].childNodes[1], null).getPropertyValue('font-size');
            var fontSize = parseFloat(style);
            label.style.fontSize = fontSize + "px";
            */
            label.style.fontWeight = tile.childNodes[3].childNodes[1].style.fontWeight;
            label.style.color = tile.childNodes[3].childNodes[1].style.color;
            //label.style.fontFamily = "Impact,Charcoal,sans-serif";
            //label.style.fontFamily = "Lucida Sans Unicode, Lucida Grande, sans-serif";
            label.style.fontFamily = "Comic Sans MS, cursive, sans-serif";
            label.numberOfLines = 0;
            label.innerHTML = tile.childNodes[3].childNodes[1].value.replace(/\n/g, "<br>");
            document.getElementById("output").appendChild(label);
        }
    });
}

async function goToExport() {
    //document.getElementById('mapRendered').style.display = "none";
    //document.getElementById('loadingPDFRotatingSVG').style.display = "inherit";
    //document.getElementById("mapRendered").src="assets/loading.gif"
    //$("#outputModal").modal('show');
//    $('#outputModal').on('show.bs.modal', function (e) {
    resetMapScale();
        setTimeout(() => {  
            createOutputDivForPrint();
            //createImg('mapRendered'); 
            createImg("map-on-project");
            exitMapEditor();
        }, 200);
//    });
    
    
}

function scaleRes() {
    //we want to support just A4 "standard" resolution
    var value = document.getElementById("zoomScaling").value
    if (value == 0.5) {
        pdfPageWidth = 2480;
        pdfPageHeight = 3508;
    }
    if (value == 1.0) {
        pdfPageWidth = 1654;
        pdfPageHeight = 2339;
    }
    if (value == 1.5) {
        pdfPageWidth = 1240;
        pdfPageHeight = 1754;
    }
    if (value == 2.0) {
        pdfPageWidth = 620;
        pdfPageHeight = 876;;
    }
    goToExport();
}

function setMarginLeft() {
    marginLeft = parseInt(document.getElementById("marginLeft").value)
    goToExport();
}

function setMarginTop() {
    marginTop = parseInt(document.getElementById("marginTop").value)
    goToExport();
}