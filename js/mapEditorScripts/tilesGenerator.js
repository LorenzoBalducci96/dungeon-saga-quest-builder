function setImage(elmnt){
    if(elmnt.getAttribute("orientation") == "90"){
        rotateBase64Image90deg(getTileSrcPath(elmnt.getAttribute("set"), elmnt.getAttribute("image"), "0"), true, elmnt)
            
    }else if(elmnt.getAttribute("orientation") == "180"){
        rotateBase64Image180deg(getTileSrcPath(elmnt.getAttribute("set"), elmnt.getAttribute("image"), "0"), elmnt)
        
    }else if(elmnt.getAttribute("orientation") == "270"){
        rotateBase64Image90deg(getTileSrcPath(elmnt.getAttribute("set"), elmnt.getAttribute("image"), "0"), false, elmnt)
    
    }else if(elmnt.getAttribute("orientation") == "0"){
        elmnt.src = getTileSrcPath(elmnt.getAttribute("set"), elmnt.getAttribute("image"), elmnt.getAttribute("orientation"))
    }
}

function getTileSrcPath(setName, imageName, orientation){
    return "assets/tiles/" + setName + "/" + imageName + ".png";
    /*
    switch(setName){
        case "baseA" : return "assets/tiles/baseA/" + imageName + ".png";
        case "baseB" : return "assets/tiles/baseB/" + imageName + ".png";
    }
    */
}

function rotateBase64Image180deg(base64Image, elmnt) {
    // create an off-screen canvas
    var offScreenCanvas = document.createElement('canvas');
    offScreenCanvasCtx = offScreenCanvas.getContext('2d');

    // cteate Image
    var img = new Image();
    img.onload = function create(){
        offScreenCanvas.height = img.height;
        offScreenCanvas.width = img.width;
    
        // rotate and draw source image into the off-screen canvas:
        //offScreenCanvasCtx.translate(img.height, img.width);
        offScreenCanvasCtx.rotate(Math.PI);
        offScreenCanvasCtx.translate(-offScreenCanvas.width, -offScreenCanvas.height);
        offScreenCanvasCtx.drawImage(img, 0, 0);

        elmnt.src = offScreenCanvas.toDataURL("image/png");
    };
    img.src = base64Image;
}

function rotateBase64Image90deg(base64Image, isClockwise, elmnt) {
    // create an off-screen canvas
    var offScreenCanvas = document.createElement('canvas');
    offScreenCanvasCtx = offScreenCanvas.getContext('2d');

    // cteate Image
    var img = new Image();

    img.onload = function create(){
        // set its dimension to rotated size
        offScreenCanvas.height = img.width;
        offScreenCanvas.width = img.height;
        
        // rotate and draw source image into the off-screen canvas:
        if (isClockwise) { 
            offScreenCanvasCtx.rotate(90 * Math.PI / 180);
            offScreenCanvasCtx.translate(0, -offScreenCanvas.width);
        } else {
            offScreenCanvasCtx.rotate(-90 * Math.PI / 180);
            offScreenCanvasCtx.translate(-offScreenCanvas.height, 0);
        }
        offScreenCanvasCtx.drawImage(img, 0, 0);

        // encode image to data-uri with base64
        elmnt.src = offScreenCanvas.toDataURL("image/png");
    }
    img.src = base64Image;
}

function getPngDimensions(base64) {
    let header = base64.slice(0, 50)
    let uint8 = Uint8Array.from(atob(header), c => c.charCodeAt(0))
    let dataView = new DataView(uint8.buffer, 0, 28)
  
    return {
      width: dataView.getInt32(16),
      height: dataView.getInt32(20)
    }
}