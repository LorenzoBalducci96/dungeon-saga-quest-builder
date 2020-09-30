var actual_page = 1;
/*
function changePage(){
    if(actual_page == 1){
        actual_page = 2;
        active_actual_page();
    }
    else if(actual_page == 2){
        actual_page = 1;
        active_actual_page();
    }
}

function active_actual_page(){
    if(actual_page == 1){
        document.getElementById("first-page").style.display = "block";
        document.getElementById("second-page").style.display = "none";
        document.getElementById("switch_page_button").src = "assets/next_page.png"
    }
    else if(actual_page == 2){
        document.getElementById("first-page").style.display = "none";
        document.getElementById("second-page").style.display = "block";
        document.getElementById("switch_page_button").src = "assets/previous_page.png"

    }
}
*/

function changePage(){
    if(actual_page == 1){
        actual_page = 2;
        activePage(2);
        document.getElementById("switch_page_button").src = "assets/previous_page.png"
    }
    else if(actual_page == 2){
        actual_page = 1;
        activePage(1);
        document.getElementById("switch_page_button").src = "assets/next_page.png"
    }
}

function activePage(pageToSet){
    if(pageToSet == 1){
        document.getElementById("pages-inner").style.transform = "rotateY(0)"
    }
    else if(pageToSet == 2){
        document.getElementById("pages-inner").style.transform = "rotateY(-180deg)"
        if(detectBrowser() == "Firefox"){
            document.getElementById("pages-inner").style.transform += "translate(-100%)"
        }
    }
}

function active_actual_page(){
    if(actual_page == 1){
        document.getElementById("pages-inner").style.transform = "rotateY(0)"
    }
    else if(actual_page == 2){
        document.getElementById("pages-inner").style.transform = "rotateY(-180deg)"
    }
}

function open_map_editor(){
    //document.getElementById("second-page").style.display = "none";
    document.getElementById("whole-mission-overview").style.display = "none";
    document.getElementById("map-editor").style.display = "block";
    rearrangeAllTilesAfterResizing();//it's not a resizing but when the editor is in display:none, tiles won't be put in correct position
}

function exitMapEditor(){
    document.getElementById("whole-mission-overview").style.display = "block";
    document.getElementById("map-editor").style.display = "none";
}

function projectZoom(moreOrLess){
    /*
    if (! ('zoom' in document.createElement('div').style)){
        alert("sorry...zoom is not available in firefox, you can use ctrl+mouse wheel")
    }
    */
    actualZoom = parseFloat(getComputedStyle(document.body).getPropertyValue('--zoom-editor'));
    if(moreOrLess == '+'){
        if(actualZoom < 1.2){
            document.body.style.setProperty('--zoom-editor', actualZoom + 0.1);
        } 
    }else if(moreOrLess == "-"){
        if(actualZoom > 0.5){
            document.body.style.setProperty('--zoom-editor', actualZoom - 0.1);
        }
    }else if(moreOrLess == "0"){
        document.body.style.setProperty('--zoom-editor', 1);
    }
}