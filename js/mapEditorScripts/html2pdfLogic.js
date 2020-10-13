//var pdfPageWidth =  620;var pdfPageHeight = 876; //75dpi
var pdfPageWidth = 1240; var pdfPageHeight = 1754; //150dpi
//var pdfPageWidth = 1654; var pdfPageHeight = 2339 //200dpi
//var pdfPageWidth =  2480;var pdfPageHeight = 3508; //300dpi
var marginLeft = 0;
var marginTop = 0;
var PDF_export_quality = 0.92; //between 0.1 and 1... 0.9 is about 380kb, 0.8 is about 250kb
var fixed_pdf_quality_for_mail = 0.90;

var pdfBase64;



function finishProjectOptions(){
    projectZoom("0");
    activePage(1);
    $("#loading_modal").modal("show");
    window.scrollTo(0,0);
}

function exportProjectToPDF(send_email){
    if(send_email){
        document.getElementById("pdfExportQuality").value = fixed_pdf_quality_for_mail;
    }
    PDF_export_quality = parseFloat(document.getElementById("pdfExportQuality").value);
    //document.getElementById("close_modal_loading_button").style.visibility = "hidden";
    //document.getElementById("loading_div").style.visibility = "";
    $("#please_wait_modal").modal("show");
    
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
                let author_email = document.getElementById("author-email").innerHTML;
                /*
                pdfBase64 = pdfDocument.output('datauristring');
                var data = JSON.stringify({
                    "pdf" : pdfBase64,
                    "authorEmail": author_email,
                    "security" : document.getElementById("send_pdf_nonce").value
                });
                */
                ajaxF(pdfDocument, author_email);
            }else{
                pdfDocument.save('dungeon.pdf');
                //document.getElementById("close_modal_loading_button").style.visibility = "";
                //document.getElementById("loading_div").style.visibility = "hidden";
                $("#please_wait_modal").modal("hide");
            }
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
            let copyTile = tile.cloneNode(true);
            copyTile.style.width = tile.offsetWidth;
            copyTile.style.height = tile.offsetHeight;
            copyTile.style.left = (tile.offsetLeft - minX + marginLeft)/scale + "px";
            copyTile.style.top = (tile.offsetTop - minY + marginTop)/scale + "px";
            document.getElementById("output").appendChild(copyTile);
        }
        if (tile.getAttribute("pieceType") == "text") {//for the text just print the text and adjust offset height
            let card = tile.cloneNode(true);
            card = card.childNodes[3].childNodes[1];
            card.style.position = "absolute";

            card.style.top = (tile.offsetTop + tile.childNodes[3].childNodes[1].offsetTop - minY + marginTop)/scale + "px";
            card.style.left = (tile.offsetLeft + tile.childNodes[3].childNodes[1].offsetLeft - minX + marginLeft)/scale + "px";
            card.style.width = tile.childNodes[3].childNodes[1].offsetWidth/scale + "px";
            card.style.height = tile.childNodes[3].childNodes[1].offsetHeight/scale + "px";

            card.style.transform = tile.style.transform;
            
            document.getElementById("output").appendChild(card);
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
            document.getElementById("map-on-project").src = "assets/loading.gif"
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