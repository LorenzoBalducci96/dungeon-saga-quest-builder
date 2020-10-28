var actual_page = 1;

var page_rotateY = 0;
var page_translate = 0;
var page_scale = 1;

function get_page_transform_string(){
    return ("rotateY(" + page_rotateY + 
    "deg) scale(" + page_scale + ")");// translate(" + parseInt(page_translate*page_scale) + "%)";
}

function changePage(){
    if(actual_page == 1){
        actual_page = 2;
        activePage(2);
        document.getElementById("switch_page_button").src = "assets/previous_page.png"
    }
    else if(actual_page == 2){
        actual_page = 1;
        activePage(1);
        document.getElementById("switch_page_button").src = "assets/next_page.png";
    }
}

function activePage(pageToSet){
    if(pageToSet == 1){
        document.getElementById("first-page").style.transform = "rotateY(0)";
        document.getElementById("second-page").style.transform = "rotateY(-180deg)";
    }
    else if(pageToSet == 2){
        document.getElementById("first-page").style.transform = "rotateY(-180deg)";
        document.getElementById("second-page").style.transform = "rotateY(0)";
    }
    /*
    if(pageToSet == 1){
        page_rotateY = 0;
        page_translate = 0;
        document.getElementById("pages-inner").style.transform = get_page_transform_string();
    }
    else if(pageToSet == 2){
        page_rotateY = -180;
        page_translate = -100;
        document.getElementById("pages-inner").style.transform = get_page_transform_string();
    }
    */
}

function activeActualPage(){
    if(actual_page == 1){
        page_rotateY = 0
        page_translate = 0;
        document.getElementById("pages-inner").style.transform = get_page_transform_string();
    }
    else if(actual_page == 2){
        page_rotateY = -180
        page_translate = -100;
        document.getElementById("pages-inner").style.transform = get_page_transform_string();
    }
}




function openEditor(){
    document.getElementById("main-menu").style.opacity = 0;
    setTimeout(function(){
        document.getElementById("whole-mission-overview").style.display = "block";
        document.getElementById("main-menu").style.display = "none";
        setTimeout(function(){
            document.getElementById("whole-mission-overview").style.opacity = 1;
        },100)
    }, 500);
}

function returnToMenu(){
    document.getElementById("whole-mission-overview").style.opacity = 0;
    setTimeout(function(){
        document.getElementById("main-menu").style.display = "block";
        document.getElementById("whole-mission-overview").style.display = "none";
        setTimeout(function(){
            document.getElementById("main-menu").style.opacity = 1;
        }, 100)
    }, 500);
}

function open_map_editor(){
    document.getElementById("whole-mission-overview").style.opacity = 0;
    setTimeout(function(){
        document.getElementById("map-editor").style.display = "block";
        document.getElementById("whole-mission-overview").style.display = "none";
        rearrangeAllTilesAfterResizing();//it's not a resizing but when the editor is in display:none, tiles won't be put in correct position
        setTimeout(function(){
            document.getElementById("map-editor").style.opacity = 1;

        }, 100)
    }, 500);
    
}

function exitMapEditor(){

    document.getElementById("map-editor").style.opacity = 0;
    setTimeout(function(){
        document.getElementById("map-editor").style.display = "none";
        document.getElementById("whole-mission-overview").style.display = "block";
        setTimeout(function(){
            document.getElementById("whole-mission-overview").style.opacity = 1;

        }, 100)
    }, 500);
}

function projectZoom(moreOrLess){
    /*
    if (! ('zoom' in document.createElement('div').style)){
        alert("sorry...zoom is not available in firefox, you can use ctrl+mouse wheel")
    }
    */
    if(moreOrLess == '+'){
        if(page_scale < 1.2){
            page_scale += 0.1;
            document.getElementById("pages-inner").style.transform = "scale(" + page_scale + ")";
        }
    }else if(moreOrLess == "-"){
        if(page_scale > 0.5){
            page_scale -= 0.1;
            document.getElementById("pages-inner").style.transform = "scale(" + page_scale + ")";
        }
    }else if(moreOrLess == "0"){
        page_scale = 1;
        document.getElementById("pages-inner").style.transform = "scale(" + page_scale + ")";
    }
   /*
    if(moreOrLess == '+'){
        if(page_scale < 1.2){
            page_scale += 0.1;
            document.getElementById("pages-inner").style.transform = get_page_transform_string();
        } 
    }else if(moreOrLess == "-"){
        if(page_scale > 0.5){
            page_scale -= 0.1;
            document.getElementById("pages-inner").style.transform = get_page_transform_string();
        }
    }else if(moreOrLess == "0"){
        page_scale = 1;
        document.getElementById("pages-inner").style.transform = get_page_transform_string();
    }
    */
}

function mapOnProjectZoom(moreOrLess){
    if(moreOrLess == "-"){
        document.getElementById("map-on-project").style.height = document.getElementById("map-on-project").offsetHeight*0.9 + "px"
    }else if(moreOrLess == "+"){
        if(document.getElementById("map-on-project").offsetWidth*1.1 < document.getElementById("map-on-project-container").offsetWidth){
            document.getElementById("map-on-project").style.height = document.getElementById("map-on-project").offsetHeight*1.1 + "px"
        }
    }
}