<html>

<head>
    <meta charset="utf-8">
    <title>Dungeon saga quest builder</title>
</head>
<link rel="icon" href="assets/icon.png">

<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.css">
<script>if (typeof module === 'object') { window.module = module; module = undefined; }</script>
<script src="js/jquery/jquery-3.4.1.min.js"></script>
<script src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>

<script src="js/html2canvas/html2canvas.js"></script>
<script src="js/html2canvas/jspdf.debug.js"></script>
<script src="https://cdn.bootcss.com/html2pdf.js/0.9.1/html2pdf.js"></script> 
<script src="js/fileSaver/FileSaver.js"></script>
<script>if (window.module) module = window.module;</script>

<!--script for the editor in the overview-->
<script src="js/editor_logic.js"></script>
<script type="text/javascript" src="js/backend.js"></script>
<script type="text/javascript" src="js/utils.js"></script>


<!--scripts for the map editor-->
<script type="text/javascript" src="js/mapEditorScripts/dragLogic.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/bootstrap_page.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/tilesGenerator.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/changeTilesGroups.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/html2pdfLogic.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/scaleUi.js"></script>





<link rel="stylesheet" type="text/css" href="css/style-main.css">
<link rel="stylesheet" type="text/css" href="css/style-editor.css">
<link rel="stylesheet" type="text/css" href="css/style-modals.css">
<link rel="stylesheet" type="text/css" href="css/range.css">

<script>
    if(detectBrowser() == "IE"){
        alert("sorry, internet explorer not supported")
    }
    var appType = "web";
    
    window.onload = function () {
        setTimeout(() => {
            bootstrap_page();
        }, 200);
    };

    /*this piece of code allow multiple bootstrap modal on top of each other*/
    $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
    
</script>



<body>
<?php
        require_once("../wp-load.php");
        wp_nonce_field( 'send_pdf_nonce', 'send_pdf_nonce' );
?>

    <div class="modal fade" id="please_wait_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-smog-dark">
                <div class="modal-body">
                    <div class="row align-items-center" style="width: 100%; text-align: center;">
                        <div class="col-6 offset-1">
                            <label style="font-size: 24px;">
                                PLEASE WAIT    
                            </label>    
                        </div>
                        <div class="col-4">
                            <img src="assets/loading.gif" style="width: 100%;">
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="message_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-smog-dark">
                <div class="modal-body">
                    <label id="message_modal_label"></label>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm_message_button" class="btn btn-default" 
                    data-dismiss="modal">ok</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="instant_login_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-smog-dark">
                <div class="modal-header">
                    <h4 class="modal-title">Ops...seems you are no logged in</h4>
                    <button id="close-modal-login-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <h3 aria-hidden="true">×</h3>
                    </button>
                </div>

                <div class="modal-body">
                    <label>for uploading you pdf to the forum you must be logged in. Login to the forum for upload you pdf</label>
                    <input id="login_email" type="text" placeholder="email@email.com" size="20" />
                    <input id="login_password" type="password" size="20" />
                    <br>
                    <button class="old_button" style="padding:2px; width:100%;" onclick="login()">Login</button>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="loading_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content modal-smog-content">

                <div class="modal-header">
                    <h4 class="modal-title">finish project</h4>
                    <button id="close_modal_loading_button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <h3 aria-hidden="true">×</h3>
                    </button>
                </div>
                
                <div class="modal-body p-4 funny_scrollbar_blue" id="result">
                    
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-4">
                            <button class="old_button" style="width:100%;" onclick="alert('sorry function not yet available');">download backup project</button> 
                        </div>
                        <div class="col-8">
                            <label class="label_output_modal">Download a backup file of you project.
                            With this file you will be able to restore you project using the reopen quest from
                            backup button</label>
                        </div>
                    </div>
                    <div class="row" style="justify-content: center;">
                        <label class="label_output_modal" >PDF image quality :</label>
                        <label class="label_output_modal" id="pdfExportQualityLabel">0.92</label>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label style="width: 100%; text-align: right;">lower file size <-</label>
                        </div>    
                        <div class="col-4">
                            <input type="range" class="rounded_range" id="pdfExportQuality" name="quality" 
                            step="0.02" min="0.7" max="1" value="0.92" 
                            onchange="document.getElementById('pdfExportQualityLabel').innerHTML = this.value" 
                            oninput="document.getElementById('pdfExportQualityLabel').innerHTML = this.value">
                        </div>
                        <div class="col-4">
                            <label style="width: 100%; text-align: left;">-> higher image quality</label>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-4">
                            <button class="old_button" style="width:100%;" onclick="exportProjectToPDF(false)">export PDF</button> 
                        </div>
                        <div class="col-8">
                            <label class="label_output_modal">Generate and download an A-4 format PDF,
                                 use the slider above to decide the quality of the output file </label>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-4">
                            <button class="old_button" style="width:100%;" onclick="exportProjectToPDF(true)">upload project for contest</button> 
                        </div>
                        <div class="col-8">
                            <label class="label_output_modal">Upload the PDF of you project, 
                                (first download it with the button above here and take a final look)</label>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
        




    <div id="main-menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="menu-box">
                        <div class="row">
                            <div class="col-4 offset-4">
                                <img src="assets/icon.png" style="width:100%; height: auto;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-10 offset-1">
                                        <button onclick="startNewQuest()" class="old_button" style="width:100%; margin-top: 4px;">NEW</button>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <label class="old_label">you will start creating a new quest</label>    
                                    </div>
                                </div> 
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-10 offset-1">
                                        <button class="old_button" style="width:100%;  margin-top: 4px;"
                                        onclick="alert('sorry function not yet available');">OPEN</button>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <label class="old_label">restore one of you backup files previously downloaded</label>    
                                    </div>
                                </div>                 
                            </div>
                        </div>
                        <div style="position: absolute; bottom:2px; width:100%;">
                        <button class="old_button" style="margin-left:4px;" onclick="document.location='../'">EXIT</button>
                        <label style="text-align: center; width:100%;">version 1.1</label>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="whole-mission-overview" style="opacity: 0; display:none">
        <div style="display: flex; width:100%">
            <div class="editor-menu col-15 px-1">
                <div class="top-container-bar" style="overflow: auto;">
                    <div style="padding:8px; margin-bottom: 2px; border-color: gray; border-style: solid; border-width: thin;">
                        <label>turn page</label>
                        <img id="switch_page_button" src="assets/next_page.png" onclick="changePage()">
                    </div>
                    
                    <div style="padding:8px;  margin-bottom: 2px; border-color: gray; border-style: solid; border-width: thin;">
                        <label>page zoom</label>
                        <div style="display: flex;">
                            <button class="old_button" style="width: 49%; padding: 2px;" onclick="projectZoom('-')">-</button>
                            <button class="old_button" style="width: 49%; padding: 2px;" onclick="projectZoom('+')">+</button>
                        </div>
                    </div>
                    <div style="padding:8px; border-color: gray; border-style: solid; border-width: thin;">
                        <label>map zoom</label>
                        <div style="display: flex;">
                            <button class="old_button" style="width: 49%; padding: 2px;" onclick="mapOnProjectZoom('-')">-</button>
                            <button class="old_button" style="width: 49%; padding: 2px;" onclick="mapOnProjectZoom('+')">+</button>
                        </div>
                    </div>
                </div>
                <div style="position: absolute; bottom:2px;">
                    <button id="export_project_pdf_button" class="old_button" style="margin-bottom: 8px; padding: 2px; width: 90%;" onclick="finishProjectOptions()">FINISH</button>
                    <button id="return-to-menu" class="old_button" style="padding:2px; width: 90%"  onclick="returnToMenu()">return to menu</button>
                </div>
            </div>

            

            <div class="pages-container col-105 offset-15">
                <div id="flip-pages" disply="text-align: center;">
                    <div id="pages-inner" disply="text-align: center;">
                        <div id="first-page" class="main-page">
                            <div class="container-fluid">
                                <div class="row">
                                    <div id="bootstrap-padding-left" class="col-1">
                                    
                                    </div>
                                    <div id="main-content" class="col-11">
                                        <div class="row" id="title-row">
                                            <div id="main-page-section-1" class="col-12">
                                                <p placeholder="insert title of mission here" id="main-page-introduction-section-title" 
                                                class="title cap-first-letter" contenteditable="true">Title of the adventure</p>
                                                <div contenteditable="true">lorem ipsum dolor sit amet, lorem ipsum dolor sit amet,
                                                lorem ipsum dolor sit amet, lorem ipsum dolor sit amet,
                                                lorem ipsum dolor sit amet,lorem ipsum dolor sit amet,
                                                lorem ipsum dolor sit amet,lorem ipsum dolor sit amet</div>
                                            </div>
                                        </div>

                                        <img src="assets/gem_spacer.png" id="top-gem-spacer" style="width: 100%">

                                        <div class="row">
                                            <div id="main-page-section-2" class="col-4">
                                                <div id="main-page-introduction-section-title" class="title sub cap-first-letter" contenteditable="true">The heroes</div>
                                                <div contenteditable="true">The heroes start the mission in the start point as shown in the map.</div>
                                            </div>   
                                            <div id="main-page-section-3" class="col-4">
                                                <div id="main-page-introduction-section-title" class="title sub cap-first-letter" contenteditable="true">The demon</div>
                                                <div contenteditable="true">The heroes start the mission in the start point as shown in the map.</div>
                                            </div>    
                                            <div id="main-page-section-4" class="col-4">
                                                <div id="main-page-introduction-section-title" class="title sub cap-first-letter" contenteditable="true">Victory conditions</div>
                                                <div contenteditable="true">The heroes start the mission in the start point as shown in the map.</div>
                                            </div>      
                                        </div>

                                        <div class="row">
                                            <div id="special-rules" class="col-12">
                                                <div id="special-rules-title" contenteditable="true">SPECIAL RULES: POTIONS NOT PERMITTED FOR WARRIORS</div>
                                                <div contenteditable="true">Players that use a warrior character can't use potions unless for the behaviour of a special effect.
                                                    Healing with objects is not allowed too for warriors.
                                                    Once a hero is dead (whatever hero), he can revive just after rolling 5 on a response dice roll. This can be done just once per turn.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bootstrap-padding-right" class="col-0">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div id="second-page" class="main-page">
                            <div class="container-fluid">
                                <div class="row" style="height: 100%;">
                                    <div id="bootstrap-padding-left" class="col-0">
                                    </div>
                                    <div id="main-content" class="col-11">
                                        <div class="row" id="title-row">
                                            <div id="main-page-section-1" class="tactical_tips_box" class="col-12">
                                                <p id="main-page-introduction-section-title" class="tactical-tips-title" contenteditable="true" >TACTICAL TIPS</p>
                                                <hr style="height: 1px; background-color: #991111; border: none;">
                                                <div contenteditable="true">from the moment that the warriors can't be healed in any way...is better to use warriors in another way.
                                                    In this encounter magiciants will be defeating the boss and warriors will be doing something else...</div>
                                            </div>
                                        </div>
                                        <div id="map-on-project-container" class="map-on-project-container" onclick="open_map_editor()">
                                            <img id="map-on-project" src="assets/sample_map.png">
                                        </div>
                                        <div class="row" id="title-row">
                                            <div id="main-page-section-2" class="in-the-end" class="col-12">
                                                <p id="main-page-introduction-section-title" class="in-the-end-title" contenteditable="true" >In the end</p>
                                                <hr style="height: 1px; background-color: #991111; border: none;">
                                                <div contenteditable="true">lorem ipsum dolor sit amet, lorem ipsum dolor sit amet, lorem ipsum dolor sit amet, lorem ipsum dolor sit amet.
                                                    lorem ipsum dolor sit amet lorem ipsum dolor sit amet, lorem ipsum dolor sit amet.</div>
                                            </div>
                                        </div>
                                        <div class="row" style="position: absolute; bottom: 0px; width:100%;">
                                            <div style="width:100%; display: flex">
                                                <label>Email:</label>
                                                <div id="author-email" style="width:100%; min-width: 40px; margin-left: 2px;" contenteditable="true">red_dragon@gmail.com</div>
                                                <div style="width:100%; display: flex; justify-content: flex-end;">
                                                    <label style="float:left">Nickname:</label>
                                                    <div id="author-nickname" style="float:left; min-width: 40px; margin-left: 2px;" contenteditable="true">Red Dragon</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bootstrap-padding-right" class="col-1">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="map-editor" style="display: none">
        <div id="output" style="display: none;"></div>

        <div class="topMenuBar">
            <div id="editor_controls">
                <label class="label_on_top_menu">map_zoom:</label>
                <label class="label_on_top_menu" id="zoom">100%</label>
                <button class="old_button map_editor_menu_button" style="padding-left:10px; padding-right:10px; font-weight: bolder;" onclick="decreaseUiZoom()">-</button>
                <button class="old_button map_editor_menu_button" style="padding-left:10px; padding-right:10px; font-weight: bolder;" onclick="increaseUiZoom()">+</button>
                <div style="width: 100%; display: flex; justify-content: flex-end;">
                    <button style="float: right;" class="old_button map_editor_menu_button" onclick="goToExport()">return to editor</button>
                </div>
            </div>
        </div>

        <div id="application">

            <div id="generalMenuContainer">
                <button id="triggerGeneralMenuButton" style="width: 90%; padding-top: 2px; padding-bottom: 2px;"  class="old_button" 
                onclick="triggerGeneralMenu()">↑</button>
                <div id="generalMenu" style="visibility: hidden;">
                    <button class="innerMenuButton">bla bla bla</button>
                    <button class="innerMenuButton">lorem ipsum</button>
                </div>
            </div>


            <div class="sidebar-container">
                <div id="tilesSelectorBar" style="text-align: center;">
                    <button id="triggerInnerMenuButton" style="width: 90%; padding-top: 2px; padding-bottom: 2px;"  class="old_button" 
                    onclick="triggerInnerMenu()">↓</button>
                    <div id="innerMenu" style="visibility: hidden;">
                        <button class="innerMenuButton" style="width:90%; opacity: 0.5;" id="loadQrnA" onclick="loadQrnA()">side A</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnB" onclick="loadQrnB()">side B</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnHeroes" onclick="loadQrnHeroes()">Heroes</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnMinions" onclick="loadQrnMinions()">Minions</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnFurnitures" onclick="loadQrnFurnitures()">Furnitures</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnLocks" onclick="loadQrnLocks()">Locks</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnTraps" onclick="loadQrnTraps()">Traps</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnMarkers" onclick="loadQrnMarkers()">Markers</button>
                        <button class="innerMenuButton" style="width:90%;" id="loadQrnBosses" onclick="loadQrnBosses()">Bosses</button>
                    </div>
                </div>

                <div class="sidenav" id="qrnA" style="visibility: ''">
<image src="assets/tiles/qrnA/QRN-tile-3x4-A.png" set="qrnA" image="QRN-tile-3x4-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile-3x6-A.png" set="qrnA" image="QRN-tile-3x6-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile-4x8-A.png" set="qrnA" image="QRN-tile-4x8-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile-5x8-A.png" set="qrnA" image="QRN-tile-5x8-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-5x8-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile-6x4-A.png" set="qrnA" image="QRN-tile-6x4-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-6x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile-7x10-A.png" set="qrnA" image="QRN-tile-7x10-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-7x10-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01+-3x3-A.png" set="qrnA" image="QRN-tile01+-3x3-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01+-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-1x2-A.png" set="qrnA" image="QRN-tile01-1x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-2x2-A.png" set="qrnA" image="QRN-tile01-2x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-2x3-A.png" set="qrnA" image="QRN-tile01-2x3-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-3x1-A.png" set="qrnA" image="QRN-tile01-3x1-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-4x5-A.png" set="qrnA" image="QRN-tile01-4x5-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile01-6x6-A.png" set="qrnA" image="QRN-tile01-6x6-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-6x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02+-3x3-A.png" set="qrnA" image="QRN-tile02+-3x3-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02+-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-1x2-A.png" set="qrnA" image="QRN-tile02-1x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-2x2-A.png" set="qrnA" image="QRN-tile02-2x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-2x3-A.png" set="qrnA" image="QRN-tile02-2x3-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-3x1-A.png" set="qrnA" image="QRN-tile02-3x1-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-3x1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-4x5-A.png" set="qrnA" image="QRN-tile02-4x5-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tile02-6x6-A.png" set="qrnA" image="QRN-tile02-6x6-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-6x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tileL01-2x2-A.png" set="qrnA" image="QRN-tileL01-2x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tileL02-2x2-A.png" set="qrnA" image="QRN-tileL02-2x2-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tileT01-4x5-A.png" set="qrnA" image="QRN-tileT01-4x5-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT01-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tileT02-4x5-A.png" set="qrnA" image="QRN-tileT02-4x5-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT02-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnA/QRN-tileY-3x3-A.png" set="qrnA" image="QRN-tileY-3x3-A" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileY-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnB" style="visibility: hidden;">
<image src="assets/tiles/qrnB/QRN-tile-3x4-B.png" set="qrnB" image="QRN-tile-3x4-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile-3x6-B.png" set="qrnB" image="QRN-tile-3x6-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile-4x8-B.png" set="qrnB" image="QRN-tile-4x8-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile-5x8-B.png" set="qrnB" image="QRN-tile-5x8-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-5x8-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile-6x4-B.png" set="qrnB" image="QRN-tile-6x4-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-6x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile-7x10-B.png" set="qrnB" image="QRN-tile-7x10-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-7x10-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01+-3x3-B.png" set="qrnB" image="QRN-tile01+-3x3-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01+-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-1x2-B.png" set="qrnB" image="QRN-tile01-1x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-2x2-B.png" set="qrnB" image="QRN-tile01-2x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-2x3-B.png" set="qrnB" image="QRN-tile01-2x3-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-3x1-B.png" set="qrnB" image="QRN-tile01-3x1-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-4x5-B.png" set="qrnB" image="QRN-tile01-4x5-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile01-6x6-B.png" set="qrnB" image="QRN-tile01-6x6-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-6x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02+-3x3-B.png" set="qrnB" image="QRN-tile02+-3x3-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02+-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-1x2-B.png" set="qrnB" image="QRN-tile02-1x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-2x2-B.png" set="qrnB" image="QRN-tile02-2x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-2x3-B.png" set="qrnB" image="QRN-tile02-2x3-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-3x1-B.png" set="qrnB" image="QRN-tile02-3x1-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-3x1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-4x5-B.png" set="qrnB" image="QRN-tile02-4x5-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tile02-6x6-B.png" set="qrnB" image="QRN-tile02-6x6-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-6x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tileL01-2x2-B.png" set="qrnB" image="QRN-tileL01-2x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tileL02-2x2-B.png" set="qrnB" image="QRN-tileL02-2x2-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tileT01-4x5-B.png" set="qrnB" image="QRN-tileT01-4x5-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT01-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tileT02-4x5-B.png" set="qrnB" image="QRN-tileT02-4x5-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT02-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnB/QRN-tileY-3x3-B.png" set="qrnB" image="QRN-tileY-3x3-B" orientation="0" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileY-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnHeroes" style="visibility: hidden;">
<image src="assets/tiles/qrnHeroes/hero-Danor-human-sorcerer.png" set="qrnHeroes" image="hero-Danor-human-sorcerer" orientation="0" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Danor-human-sorcerer" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnHeroes/hero-Madriga-elve-ranger.png" set="qrnHeroes" image="hero-Madriga-elve-ranger" orientation="0" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Madriga-elve-ranger" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnHeroes/hero-Orlaf-human-barbarian.png" set="qrnHeroes" image="hero-Orlaf-human-barbarian" orientation="0" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Orlaf-human-barbarian" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnHeroes/hero-Rordin-dwarf-warrior.png" set="qrnHeroes" image="hero-Rordin-dwarf-warrior" orientation="0" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Rordin-dwarf-warrior" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnHeroes/starting-position-hero.png" set="qrnHeroes" image="starting-position-hero" orientation="0" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnBosses" style="visibility: hidden;">
<image src="assets/tiles/qrnBosses/Boss-Elshara-Banshee.png" set="qrnBosses" image="Boss-Elshara-Banshee" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Elshara-Banshee_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnBosses/Boss-Grund-king-dwarf-undead.png" set="qrnBosses" image="Boss-Grund-king-dwarf-undead" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Grund-king-dwarf-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnBosses/Boss-Hoggar-Chaman-Troll-undead.png" set="qrnBosses" image="Boss-Hoggar-Chaman-Troll-undead" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Hoggar-Chaman-Troll-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnBosses/Boss-Mortibris-human-Necromancer.png" set="qrnBosses" image="Boss-Mortibris-human-Necromancer" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Mortibris-human-Necromancer_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnBosses/starting-position-boss.png" set="qrnBosses" image="starting-position-boss" flippable="no" orientation="0" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-boss_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>    
                </div>
                <div class="sidenav" id="qrnMinions" style="visibility: hidden;">
<image src="assets/tiles/qrnMinions/Minion-bone-pile.png" set="qrnMinions" image="Minion-bone-pile" orientation="0" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="Minion-bone-pile_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-dwarf-Revenant.png" set="qrnMinions" image="Minion-dwarf-Revenant" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-dwarf-Revenant_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-squeleton-bowman.png" set="qrnMinions" image="Minion-squeleton-bowman" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-bowman_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-squeleton-warrior-01.png" set="qrnMinions" image="Minion-squeleton-warrior-01" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-warrior-01_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-squeleton-warrior-02.png" set="qrnMinions" image="Minion-squeleton-warrior-02" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-warrior-02_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-Troll-warrior-zombies.png" set="qrnMinions" image="Minion-Troll-warrior-zombies" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-Troll-warrior-zombies_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-wraith.png" set="qrnMinions" image="Minion-wraith" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-wraith_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-zombies-1.png" set="qrnMinions" image="Minion-zombies-1" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-zombies-2.png" set="qrnMinions" image="Minion-zombies-2" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/Minion-zombies-armored.png" set="qrnMinions" image="Minion-zombies-armored" orientation="0" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-armored_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMinions/starting-position-Minion-undead.png" set="qrnMinions" image="starting-position-Minion-undead" orientation="0" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-Minion-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnFurnitures" style="visibility: hidden;">
<image src="assets/tiles/qrnFurnitures/door-large-Lock-red.png" set="qrnFurnitures" image="door-large-Lock-red" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-large-Lock-red" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/door-large-Lock-yellow.png" set="qrnFurnitures" image="door-large-Lock-yellow" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-large-Lock-yellow" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/door-Petit-Lock-red.png" set="qrnFurnitures" image="door-Petit-Lock-red" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-red" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/door-Petit-Lock-Violet.png" set="qrnFurnitures" image="door-Petit-Lock-Violet" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-Violet" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/door-Petit-Lock-yellow.png" set="qrnFurnitures" image="door-Petit-Lock-yellow" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-yellow" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-barrel-1.png" set="qrnFurnitures" image="furniture-barrel-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-barrel-2.png" set="qrnFurnitures" image="furniture-barrel-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-Buffet-1.png" set="qrnFurnitures" image="furniture-Buffet-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Buffet-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-chest-1.png" set="qrnFurnitures" image="furniture-chest-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-chest-2.png" set="qrnFurnitures" image="furniture-chest-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-lectern-1.png" set="qrnFurnitures" image="furniture-lectern-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-lectern-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-lectern-2.png" set="qrnFurnitures" image="furniture-lectern-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-lectern-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-library-1.png" set="qrnFurnitures" image="furniture-library-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-library-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-rack-1.png" set="qrnFurnitures" image="furniture-rack-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-rack-1_2.png" set="qrnFurnitures" image="furniture-rack-1_2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-1_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-rack-2.png" set="qrnFurnitures" image="furniture-rack-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-small-door-01.png" set="qrnFurnitures" image="furniture-small-door-01" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-small-door-01" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-Table-1.png" set="qrnFurnitures" image="furniture-Table-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Table-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-Table-2.png" set="qrnFurnitures" image="furniture-Table-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Table-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-throne-dwarf.png" set="qrnFurnitures" image="furniture-throne-dwarf" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-dwarf" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-throne-dwarf_2.png" set="qrnFurnitures" image="furniture-throne-dwarf_2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-dwarf_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-throne-human.png" set="qrnFurnitures" image="furniture-throne-human" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-human" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-tomb-1.png" set="qrnFurnitures" image="furniture-tomb-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-tomb-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-tomb-2.png" set="qrnFurnitures" image="furniture-tomb-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-tomb-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-well-1.png" set="qrnFurnitures" image="furniture-well-1" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-well-1_2.png" set="qrnFurnitures" image="furniture-well-1_2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-1_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnFurnitures/furniture-well-2.png" set="qrnFurnitures" image="furniture-well-2" orientation="0" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnLocks" style="visibility: hidden;">
<image src="assets/tiles/qrnLocks/chest-Lock-red-1.png" set="qrnLocks" image="chest-Lock-red-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-red-2.png" set="qrnLocks" image="chest-Lock-red-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-red-3.png" set="qrnLocks" image="chest-Lock-red-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-red-4.png" set="qrnLocks" image="chest-Lock-red-4" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-red-5.png" set="qrnLocks" image="chest-Lock-red-5" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-yellow-1.png" set="qrnLocks" image="chest-Lock-yellow-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-yellow-2.png" set="qrnLocks" image="chest-Lock-yellow-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/chest-Lock-yellow-3.png" set="qrnLocks" image="chest-Lock-yellow-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-red-1.png" set="qrnLocks" image="door-large-door-red-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-red-2.png" set="qrnLocks" image="door-large-door-red-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-red-3.png" set="qrnLocks" image="door-large-door-red-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-red-4.png" set="qrnLocks" image="door-large-door-red-4" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-red-5.png" set="qrnLocks" image="door-large-door-red-5" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-yellow-1.png" set="qrnLocks" image="door-large-door-yellow-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-yellow-2.png" set="qrnLocks" image="door-large-door-yellow-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-large-door-yellow-3.png" set="qrnLocks" image="door-large-door-yellow-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-purple-2.png" set="qrnLocks" image="door-small-door-purple-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-purple-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-purple-3.png" set="qrnLocks" image="door-small-door-purple-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-purple-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-red-1.png" set="qrnLocks" image="door-small-door-red-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-red-2.png" set="qrnLocks" image="door-small-door-red-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-red-3.png" set="qrnLocks" image="door-small-door-red-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-red-4.png" set="qrnLocks" image="door-small-door-red-4" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-red-5.png" set="qrnLocks" image="door-small-door-red-5" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-yellow-1.png" set="qrnLocks" image="door-small-door-yellow-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-yellow-2.png" set="qrnLocks" image="door-small-door-yellow-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/door-small-door-yellow-3.png" set="qrnLocks" image="door-small-door-yellow-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-crush-1.png" set="qrnLocks" image="Token-lock-crush-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-crush-2.png" set="qrnLocks" image="Token-lock-crush-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-crush-3.png" set="qrnLocks" image="Token-lock-crush-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-crush-4.png" set="qrnLocks" image="Token-lock-crush-4" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-crush-5.png" set="qrnLocks" image="Token-lock-crush-5" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-magical-1.png" set="qrnLocks" image="Token-lock-magical-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-magical-2.png" set="qrnLocks" image="Token-lock-magical-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-magical-3.png" set="qrnLocks" image="Token-lock-magical-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-pick-1.png" set="qrnLocks" image="Token-lock-pick-1" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-pick-2.png" set="qrnLocks" image="Token-lock-pick-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-pick-3.png" set="qrnLocks" image="Token-lock-pick-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnLocks/Token-lock-pick-4.png" set="qrnLocks" image="Token-lock-pick-4" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnTraps" style="visibility: hidden;">
<image src="assets/tiles/qrnTraps/marker-Trap-Asphyxiant-gas-DS.png" set="qrnTraps" image="marker-Trap-Asphyxiant-gas-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-Asphyxiant-gas-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-ball.png" set="qrnTraps" image="marker-Trap-ball" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-ball_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-Cage-DS.png" set="qrnTraps" image="marker-Trap-Cage-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-Cage-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-dart-DS.png" set="qrnTraps" image="marker-Trap-dart-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-dart-poisoned-DS.png" set="qrnTraps" image="marker-Trap-dart-poisoned-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-poisoned-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-dart-poisoned.png" set="qrnTraps" image="marker-Trap-dart-poisoned" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-poisoned_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-large-pit.png" set="qrnTraps" image="marker-Trap-large-pit" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-large-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-pit-DS.png" set="qrnTraps" image="marker-Trap-pit-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-pit-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-pit.png" set="qrnTraps" image="marker-Trap-pit" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-poisoned-dart-DS.png" set="qrnTraps" image="marker-Trap-poisoned-dart-DS" orientation="0" single="no" flippable="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="marker-Trap-poisoned-dart-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-Poisonous-spore-DS.png" set="qrnTraps" image="marker-Trap-Poisonous-spore-DS" orientation="0" single="no" flippable="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="marker-Trap-Poisonous-spore-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-quagmire-DS.png" set="qrnTraps" image="marker-Trap-quagmire-DS" orientation="0" single="no" pieceType="tile" snap="yes"  flippable="no" oncontextmenu="return false;" id="marker-Trap-quagmire-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-sharp-blade-DS.png" set="qrnTraps" image="marker-Trap-sharp-blade-DS" orientation="0" single="no" pieceType="tile" snap="yes"  flippable="no" oncontextmenu="return false;" id="marker-Trap-sharp-blade-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-small-pit.png" set="qrnTraps" image="marker-Trap-small-pit" orientation="0" single="no" pieceType="tile" snap="yes"  flippable="no" oncontextmenu="return false;" id="marker-Trap-small-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-Sort.png" set="qrnTraps" image="marker-Trap-Sort" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-Sort_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-Spider-Web.png" set="qrnTraps" image="marker-Trap-Spider-Web" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-Spider-Web_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-spike-pit.png" set="qrnTraps" image="marker-Trap-spike-pit" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-spike-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnTraps/marker-Trap-stunner-DS.png" set="qrnTraps" image="marker-Trap-stunner-DS" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="marker-Trap-stunner-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
                <div class="sidenav" id="qrnMarkers" style="visibility: hidden;">
<image src="assets/tiles/qrnMarkers/Point-A.png" set="qrnMarkers" image="Point-A" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-A_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-B.png" set="qrnMarkers" image="Point-B" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-B_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-C.png" set="qrnMarkers" image="Point-C" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-C_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-D.png" set="qrnMarkers" image="Point-D" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-D_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD-2.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD-2" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD-3.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD-3" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-E.png" set="qrnMarkers" image="Point-E" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-E_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Point-F.png" set="qrnMarkers" image="Point-F" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Point-F_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/QRN-marker-colapse.png" set="qrnMarkers" image="QRN-marker-colapse" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="QRN-marker-colapse_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/QRN-marker-rift.png" set="qrnMarkers" image="QRN-marker-rift" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="QRN-marker-rift_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-divine-magic.png" set="qrnMarkers" image="Token-divine-magic" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-divine-magic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Aeromancie.png" set="qrnMarkers" image="Token-magic-Aeromancie" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Aeromancie_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Bard.png" set="qrnMarkers" image="Token-magic-Bard" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Bard_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-common.png" set="qrnMarkers" image="Token-magic-common" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-common_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Druidic.png" set="qrnMarkers" image="Token-magic-Druidic" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Druidic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Geomancy.png" set="qrnMarkers" image="Token-magic-Geomancy" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Geomancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Hydromancy.png" set="qrnMarkers" image="Token-magic-Hydromancy" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Hydromancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Necromancy.png" set="qrnMarkers" image="Token-magic-Necromancy" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Necromancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-Pyromancie.png" set="qrnMarkers" image="Token-magic-Pyromancie" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-Pyromancie_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-magic-sorcery.png" set="qrnMarkers" image="Token-magic-sorcery" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-magic-sorcery_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/qrnMarkers/Token-Major-magic.png" set="qrnMarkers" image="Token-Major-magic" orientation="0" single="no" pieceType="tile" snap="yes" flippable="no" oncontextmenu="return false;" id="Token-Major-magic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                </div>
            </div>


            <!--width="4096" height="3084"-->
            <div id="canvasContainer" class="canvasContainer">
                <canvas id="canvasDragShower" width="4096" height="3084"></canvas>
            </div>


            <div id="map" scale="1" class="map funny_scrollbar_blue" onscroll="adjustCanvasScroll()">
                <label style="position: absolute; top: 3024px;" pieceType="placeholder" id="placeholder">.</label>
            </div>

        </div>
    </div>

</body>

</html>