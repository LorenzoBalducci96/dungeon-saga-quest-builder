var actual_page = 1;

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
        document.getElementById("first-page").style.display = "none";
        document.getElementById("second-page").style.display = "block";
        document.getElementById("switch_page_button").src = "assets/previous_page.png"
    }
    else if(actual_page == 2){
        document.getElementById("first-page").style.display = "block";
        document.getElementById("second-page").style.display = "none";
        document.getElementById("switch_page_button").src = "assets/next_page.png"
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