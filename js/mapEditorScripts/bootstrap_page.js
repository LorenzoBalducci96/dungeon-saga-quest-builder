var completedImages = 0;
var scale = 1;


var activeSet;
var tiles = [];
var offsetTopTiles = [];
var count = 0;

var setsLists = []; //= ["qrnA", "qrnB", "qrnHeroes", "qrnMinions", "qrnFurnitures", "qrnLocks", "qrnTraps", "qrnMarkers", "qrnBosses"];



var margin_of_tiles_on_sidenav = 8

function rearrangeAllTilesAfterResizing(){
    setsLists.forEach(setName => {
        this.rearrangeTilesAfterResize(setName)
    });
}

//canvas and cntrIsPressed are in dragLogic.js
function initCanvasDragShower(elmnt) {
    canvas = elmnt
    ctx = canvas.getContext('2d');
    ctx.strokeStyle = 'rgba(255, 0, 0, 2)';
    ctx.lineWidth = 4;
    rect = {};
    drag = false;
    

    $(document).keydown(function (event) {
        if (event.which == "17")
            cntrlIsPressed = true;
    });

    $(document).keyup(function () {
        cntrlIsPressed = false;
    });
}

function bootstrap_page() {
    document.querySelectorAll(".sidenav").forEach(element => {
        setsLists.push(element.getAttribute("id"));
    });
    
    
    zoomed = document.getElementById("first-page");
    /*
    zoomed = document.getElementById("first-page");
    if (! ('zoom' in document.createElement('div').style)) {
        setInterval(() => {
            const scale = Number(zoomed.getAttribute('data-zoom'))
            zoomed.style.transform = 'none !important'
            zoomed.style.marginRight = '0 !important'
            zoomed.style.marginBottom = '0 !important'
            const width = zoomed.clientWidth
            const height = zoomed.clientHeight
            zoomed.style.transform = `scale(${scale})`
            zoomed.style.transformOrigin = 'top left'
            const pullUp = height - height * scale
            const pullLeft = width - width * scale
            zoomed.style.marginBottom = `${-pullUp}px`
            zoomed.style.marginRight = `${-pullLeft}px`
          
        }, 100)
      }
    */
   
    //scale = getComputedStyle(document.documentElement).getPropertyValue('--scale');
    //applyUiZoom();

    var pieces = document.querySelectorAll("[pieceType='tile']");
    pieces.forEach(element => {
        setImage(element);
    });
    
    var pieces = document.querySelectorAll("[pieceType='ghost']");
    pieces.forEach(element => {
        setImage(element);
    });
    setTimeout(() => {
        //loadAndArrange decide the position of each tile in his bar
        setsLists.forEach(setName => {
            this.loadAndArrangeTiles(setName)
        });

        initCanvasDragShower(document.getElementById('canvasDragShower'));

        window.addEventListener("resize", rearrangeAllTilesAfterResizing);//we want to rearrange all tiles on zoom change
        window.addEventListener("resize", trickForCorrectGoogleChromeResizeBug);

        multiDragOnMapSelect(document.getElementById('map'))
        //used for restoring save point...if is the first startup, no tiles will be found in map
        var piecesOnMap = [].slice.call(document.getElementById("map").children);
        piecesOnMap.forEach(element => {
            if (element.getAttribute("pieceType") == "tile" || element.getAttribute("pieceType") == "text") {
                attachDragLogic(element);
            }
        });

        /*
        document.getElementById("control_bar").childNodes.forEach(element => {
                $(element).on('click', function() {
                    document.getElementById("control_bar").childNodes.forEach(allElements => {
                        $(allElements).removeClass('clicked');
                        
                    });
                    $(element).toggleClass('clicked');
                });
        });
        */
        //$('#baseLoadButton').click();
        //document.getElementById("output").style.backgroundImage = getSmokyBackground();
        //document.getElementById("loadingOverlay").style.display = "none";
        //document.getElementById("application").style.visibility = "";
        
    }, 2000);
}

function loadAndArrangeTiles(blockId) {
    if (!(document.getElementById(blockId).style.visibility == "hidden")) {
        activeSet = blockId;
    }
    tiles = [];
    offsetTopTiles = [];
    count = 0;
    var pieces = [].slice.call(document.getElementById(blockId).children);//get all the elements on the map
    pieces.forEach(element => {
        if (element.getAttribute("pieceType") == "tile" || element.getAttribute("pieceType") == "text") {
            tiles[count] = element.getAttribute("id");
            count++;
            attachDragLogic(element);
            
        }
    });
    arrangeTiles(blockId);
}

function arrangeTiles(blockId) {
    //arrange position
    offsetTopTiles[0] = margin_of_tiles_on_sidenav;

    var c = 0;
    
    for (c = 0; c < count; c++) {
        //put the tile
        //now put the background placeholder
        if (document.getElementById("placeholder_" + document.getElementById(tiles[c]).getAttribute("image")) == null) {
            if(c > 0){
                offsetTopTiles[c] = offsetTopTiles[c - 1] + document.getElementById(tiles[c - 1]).offsetHeight + margin_of_tiles_on_sidenav;
                //offsetTopTiles[c] = 100*c;
            }
            let placeholderImage = document.createElement("img");
            //placeholderImage.setAttribute("draggable", "false");
            placeholderImage.setAttribute("pieceType", "ghost");
            placeholderImage.setAttribute("set", document.getElementById(tiles[c]).getAttribute("set"));
            placeholderImage.setAttribute("image", document.getElementById(tiles[c]).getAttribute("image"));
            placeholderImage.setAttribute("tags", document.getElementById(tiles[c]).getAttribute("tags"));
            placeholderImage.setAttribute("orientation", "0");
            placeholderImage.style.position = "absolute";
            placeholderImage.style.top = (offsetTopTiles[c]) + "px";
            placeholderImage.style.left = "0px";
            if (document.getElementById(tiles[c]).getAttribute("pieceType") == "text") {
                placeholderImage.src = "assets/trasnparent.png"
            } else {
                //placeholderImage.src = document.getElementById(tiles[c]).src;
                placeholderImage.src = document.getElementById(tiles[c]).getAttribute("src");
            }
            placeholderImage.id = "placeholder_" + document.getElementById(tiles[c]).getAttribute("image");
            placeholderImage.style.zIndex = "-2"
            placeholderImage.style.opacity = "0.4"
            placeholderImage.style.width = "100%"//document.getElementById(tiles[c]).offsetWidth + "px"
            placeholderImage.style.height = "auto"//document.getElementById(tiles[c]).offsetHeight + "px"
            document.getElementById(blockId).appendChild(placeholderImage);
            document.getElementById(tiles[c]).style.top = (offsetTopTiles[c]) + "px";
            document.getElementById(tiles[c]).style.left = "0px";
            document.getElementById(tiles[c]).style.width = "100%";
        } else {
            offsetTopTiles[c] = offsetTopTiles[c - 1];
            document.getElementById(tiles[c]).style.top = document.getElementById(
                "placeholder_" + document.getElementById(tiles[c]).getAttribute("image")).style.top;
            document.getElementById(tiles[c]).style.left = "0px"
        }
    }
}
/**end of arranging of tiles functions for startup */

function trickForCorrectGoogleChromeResizeBug(){
    document.getElementById("placeholder").style.left = "5000px"
    setTimeout(() => {
        document.getElementById("placeholder").style.left = "0px"
    }, 20);
}