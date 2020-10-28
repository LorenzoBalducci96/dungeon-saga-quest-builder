function savePage() {
    var tilesOnMap = [].slice.call(document.getElementById("map").children);//get all the elements on the map
    //put text data into HTML
    for (var i = 0; i < tilesOnMap.length; i++) {
        if (tilesOnMap[i].getAttribute("pieceType") == "text") {
            tilesOnMap[i].childNodes[3].childNodes[1].innerHTML = tilesOnMap[i].childNodes[3].childNodes[1].value;
        }
    }
    //removing src data from elements (base64 data removed)
    for (var i = 0; i < tilesOnMap.length; i++) {
        if (tilesOnMap[i].getAttribute("pieceType") == "tile") {
            tilesOnMap[i].src = "";
        }
    }
    for (var i = 0; i < tilesOnMap.length; i++) {
        if (tilesOnMap[i].getAttribute("pieceType") == "ghost") {
            tilesOnMap[i].src = "";
        }
    }
    //document.getElementById("output").style.backgroundImage = "";
    documentToSave = document.getElementById("project").innerHTML;
    //bootstrap_page();//for readding src images

    //this code re-add all images
    var pieces = document.querySelectorAll("[pieceType='tile']");
    pieces.forEach(element => {
        setImage(element);
    });
    var pieces = document.querySelectorAll("[pieceType='ghost']");
    pieces.forEach(element => {
        setImage(element);
    });
    //this code re-add all images
    
    document.getElementById("message_modal_label").innerHTML = "you are going to download a backup file";
    document.getElementById("confirm_message_button").onclick = function () {
        document.getElementById("confirm_message_button").onclick = "";
        var blob = new Blob([documentToSave],
            { type: "text/plain;charset=utf-8" });
        anchor = document.createElement('a');

        anchor.download = "backupMap.html";
        anchor.href = (window.webkitURL || window.URL).createObjectURL(blob);
        anchor.dataset.downloadurl = ['text/plain', anchor.download, anchor.href].join(':');
        anchor.click();
    }
    $("#message_modal").modal("show");
}

function handleFileSelect(event) {
    const reader = new FileReader()
    
    reader.onload = function (event) {
        document.getElementById("project").innerHTML = event.target.result;
        bootstrap_page();
        openEditor();
    };
    
    reader.readAsText(event.target.files[0])
}


function loadPage() {
    document.getElementById("message_modal_label").innerHTML = 
    "Please, select the backup file you want to restore. \
    <br>WARNING: this will inject here all the elements contained in the file, \
    use this function only with your backup files or with trusted ones";
    document.getElementById("confirm_message_button").onclick = function () {
        document.getElementById("confirm_message_button").onclick = "";
        document.getElementById("restorePageInputFile").addEventListener('change', handleFileSelect, false);
        document.getElementById("restorePageInputFile").click();
    }
    $("#message_modal").modal("show");

}