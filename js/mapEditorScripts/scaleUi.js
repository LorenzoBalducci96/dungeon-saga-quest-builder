function increaseUiZoom(){
    previousZoom = scale;
    
    //we want to support just resolutions that we want
    if(previousZoom == 0.25){
        scale = 0.5;
        document.getElementById("zoom").innerHTML = " 50%";
    }else if(previousZoom == 0.5){
        scale = 0.75;
        document.getElementById("zoom").innerHTML = " 75%";
    }else if(previousZoom == 0.75){
        scale = 1.0;
        document.getElementById("zoom").innerHTML = "100%";
    }else if(actualZoom == 1){
        return;
    }
    document.documentElement.style.setProperty('--scale', scale);
    document.getElementById("map").setAttribute("scale", scale);
    rearrangeElementsAfterZoomMap(previousZoom, scale);
}

function decreaseUiZoom(){
    previousZoom = scale;
    //we want to support just resolutions that we want
    if(previousZoom == 1){
        scale = 0.75;
        document.getElementById("zoom").innerHTML = " 75%";
    }else if(previousZoom == 0.75){
        scale = 0.50;
        document.getElementById("zoom").innerHTML = " 50%";
    }else if(previousZoom == 0.50){
        scale = 0.25;
        document.getElementById("zoom").innerHTML = " 25%";
    }else if(actualZoom == 0.25){
        return;
    }
    document.documentElement.style.setProperty('--scale', scale);
    document.getElementById("map").setAttribute("scale", scale);
    rearrangeElementsAfterZoomMap(previousZoom, scale);
}

function resetMapScale(){
    old_zoom = scale;
    scale = 1;
    document.getElementById("zoom").innerHTML = "100%";
    document.documentElement.style.setProperty('--scale', scale);
    document.getElementById("map").setAttribute("scale", scale);
    rearrangeElementsAfterZoomMap(old_zoom, scale);
}

function applyUiZoom(){
    scale = parseFloat(document.getElementById("zoom").innerHTML);
    document.documentElement.style.setProperty('--scale', scale);
    document.getElementById("map").setAttribute("scale", scale);
}

function rearrangeElementsAfterZoomMap(oldZoom, newZoom){
    document.querySelectorAll("[piecetype='tile'][onmap='yes']").forEach(elmnt => {
        let scaleFactor = newZoom/oldZoom;
        elmnt.style.top = elmnt.offsetTop*scaleFactor + "px";
        elmnt.style.left = elmnt.offsetLeft*scaleFactor + "px";
        if(elmnt.getAttribute("snap") == "yes"){
            snap(elmnt);
        }
    });
    document.querySelectorAll("[piecetype='text'][onmap='yes']").forEach(elmnt => {
        let scaleFactor = newZoom/oldZoom;
        elmnt.style.transform = "scale(" + newZoom + ")";
        
        //elmnt.childNodes[3].childNodes[1].style.fontSize = parseInt(elmnt.childNodes[3].childNodes[1].style.fontSize)*scaleFactor + "px";
        ////elmnt.style.top = (elmnt.childNodes[3].childNodes[1].offsetTop*scaleFactor + elmnt.childNodes[1].offsetHeight*scaleFactor) + "px";
        ////elmnt.style.left = (elmnt.offsetLeft*scaleFactor) + "px";
        
        elmnt.style.top = (elmnt.offsetTop*scaleFactor /*- elmnt.childNodes[1].offsetHeight*scaleFactor*/) + "px";
        elmnt.style.left = elmnt.offsetLeft*scaleFactor + "px";
    });
}