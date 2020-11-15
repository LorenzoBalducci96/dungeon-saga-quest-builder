<?php
    // Check if user is logged in 
    require_once("../wp-load.php");
    if ( !is_user_logged_in() ){
        echo "<link rel='stylesheet' type='text/css' href='css/login_page.css'>
        <link rel='stylesheet' type='text/css' href='css/bootstrap/bootstrap.css'>
        <script src='js/jquery/jquery-3.4.1.min.js'></script>
        <script src='js/jquery/jquery-ui.js'></script>
        <script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>

        <div class='container'>
            <div class='col-12'>
                <div class='login_box'>
                    <label style='margin: 2px;'><strong> Sorry, this application is only available for registered users </strong></label>
                    <div class='col-12'>
        
        ";
        wp_login_form( array( 'echo' => true ) );
        echo "<label style='margin: 2px; max-width:100%;'>";
        echo $_GET["wp-error"];
        echo "</label>";
        echo "  
                    </div>
                </div>
            </div>
        </div>
        ";
        
        die();
        
        
    }
    
?>


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
<script src="js/fileSaver/FileSaver.js"></script>
<script>if (window.module) module = window.module;</script>

<!--script for the editor in the overview-->
<script type="text/javascript" src="js/editor_logic.js"></script>
<script type="text/javascript" src="js/backend.js"></script>
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/backupProjectLogic.js"></script>


<!--scripts for the map editor-->
<script type="text/javascript" src="js/mapEditorScripts/dragLogic.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/bootstrap_page.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/tilesGenerator.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/changeTilesGroups.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/html2pdfLogic.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/scaleUi.js"></script>
<script type="text/javascript" src="js/mapEditorScripts/textBoxesLogic.js"></script>


<link rel="stylesheet" type="text/css" href="css/style-main.css">
<link rel="stylesheet" type="text/css" href="css/style-editor.css">
<link rel="stylesheet" type="text/css" href="css/style-modals.css">
<link rel="stylesheet" type="text/css" href="css/range.css">
<link rel="stylesheet" type="text/css" href="css/text-boxes.css">
<link rel="stylesheet" type="text/css" href="css/wordpress_override.css">


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

<input type="file" id="restorePageInputFile" accept="text/html" style="display:none" />

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

    <div class="modal fade" id="editor_help_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 id="dungeon-saga-quest-builder">dungeon-saga-quest-builder</h1>
                    <button id="close-modal-login-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        
                        <h3 aria-hidden="true">×</h3>
                    </button>
                </div>

                <div class="modal-body">
                
<p>Hello, thanks for using this application, if you are here maybe you want to learn 
how to create a custom mission for dungeon saga, don't you?<br>

You are in the right place, let's introduce this editor.</p><br>
<p>
From the main menu you have the possibility to start a new project or to reopen a previously saved one from the backup files that you have.
<img src="./repo_doc/main_menu.png" alt="Main menu demo" title="MAIN_MENU" style="width:100%; height: auto;"></p>

<br>

<p>
<img src="./repo_doc/first_page.png?raw=true" alt="first page demo" title="FIRST_PAGE" style="width:100%; height: auto;"><br>
In the page editor section <strong>you can edit all the text-areas</strong> in order to create a fantastic mission for dungeon saga.
<br>the buttons to the left allow to <br>
1) <strong>switch between the first and the second page of the mission.</strong><br>
2) Adjust the zoom level in order to fit the sheet in the screen, or to make it fit the zoom level of your preference.<br>
3) Change dimensions of the map in the second sheet.<br>
4) The &quot;FINISH&quot; button allow you to conclude the project and export a PDF or download the backup of the project
that you will be able to reopen from the main menu.<br>
5) Return to main menu.</p>

<p>A fantastic mission needs a fantastic dungeon map right? <br>
This is shown in the second sheet of your mission, just click it to enter in the dungeon editor section.
<img src="./repo_doc/second_page.png?raw=true" alt="second page demo" title="SECOND_PAGE" style="width:100%; height: auto;">
</p>


<p>In the dungeon editing mode all the pieces are arranged in the left panel and a drop down list (1) allow you to change pieces groups.
The pieces are sorted with respect to their expansions, the last panel named as "markers and extra" has all markers and textboxes that you may want to put in the map. 
Simply <strong>drag a piece from the left to the right</strong> for putting it into the map.
For rotating a tile or any other element on the map simply <strong>right click with the mouse or double tap on mobile devices</strong>.
Is possible to select multiple pieces just by dragging with the mouse for a multiple selection.
Different pieces have different behaviours, see below in the html attributes explaination.
Is possible to asjust the zoom of the map with the + and - top buttons.
When the editing of the map is finished just <strong>press return to editor</strong>; the edited map will appear in the second sheet.
Use the map zoom buttons in the editor for adjust the map dimension.<br>
<img src="./repo_doc/the_map_editor.png?raw=true" alt="map editor demo" title="MAP_EDITOR_PAGE" style="width:100%; height: auto;"></p><br>

<p>When you think your mission is ready to be played by the community <strong>click on the FINISH button and click "UPLOAD PDF"</strong>.
A PDF will be sent to the community administrator, after a quick quality check it will be available for download for the community.
Once the PDF has been sent, your browser will start the download of the PDF on your computer for your personal use.</p>
<br>
Contribute: <a href="https://github.com/LorenzoBalducci96/dungeon-saga-quest-builder">github.com/LorenzoBalducci96/dungeon-saga-quest-builder</a>
<br>Copyright (c) 2020 Lorenzo Balducci 

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
                
                <div class="modal-body p-4" id="result">
                    
					<div class="row" style="margin-top: 8px;">
						<div class="col-5 offset-1">
									<button class="old_button" style="width:100%;" onclick="exportProjectToPDF(true)">UPLOAD PDF</button> 
						</div>
                        <div class="col-4 offset-1">
                            <button class="old_button" style="width:100%;" onclick="savePage()">download backup project</button> 
						</div>
                    </div>

                    <div style="display:none;">
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
                    </div>
                    
					<div class="row" style="margin-top: 8px;">
						<div class="col-5 offset-1">
							<label class="label_output_modal">Upload the PDF of your project to the community</label>
						</div>
						<div class="col-4 offset-1">
							<label class="label_output_modal">Download a backup file of your project.
							With this file you will be able to restore you project using the reopen quest from
							backup button</label>
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
                                        <button onclick="openEditor()" class="old_button" style="width:100%; margin-top: 4px;">NEW</button>
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
                                        onclick="loadPage()">OPEN</button>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <label class="old_label">restore one of you backup files previously downloaded</label>    
                                    </div>
                                </div>                 
                            </div>
                        </div>
                        <div style="position: absolute; bottom:2px; width:100%;">
                        <button class="old_button" style="margin-left:4px;" onclick="document.location='../'">EXIT</button>
                        <label style="text-align: center; width:100%;">version 1.3</label>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="project">
        <div id="whole-mission-overview" style="opacity: 0; display:none">
            <div style="display: flex;">
                <div class="editor-menu col-15 px-1">
                    <div class="top-container-bar" style="overflow: auto; width:100%;">
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
                    <div style="position: absolute; bottom:8px; width:100%;">
                        <button id="export_project_pdf_button" class="old_button" style="margin-bottom: 8px; padding: 2px; width: 95%;" onclick="finishProjectOptions()">FINISH</button>
                        <button id="return-to-menu" class="old_button" style="padding:1px; width: 40%"  onclick="returnToMenu()">home</button>
                        <button id="help" class="old_button" style="padding:1px; width: 50%"  onclick="show_editor_help()">help</button>
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
                                                    <div id="main-page-introduction-section-title" class="title cap-first-letter" 
                                                    contenteditable="true">Title of the adventure</div>
                                                    <div contenteditable="true">lorem ipsum dolor sit amet, lorem ipsum dolor sit amet,
                                                    lorem ipsum dolor sit amet, lorem ipsum dolor sit amet,
                                                    lorem ipsum dolor sit amet,lorem ipsum dolor sit amet,
                                                    lorem ipsum dolor sit amet,lorem ipsum dolor sit amet</div>
                                                </div>
                                            </div>

                                            <img draggable="false" src="assets/gem_spacer.png" id="top-gem-spacer" style="width: 100%; pointer-events: none;">

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
                                            <div class="row" style="font-size: 15px; position: absolute; bottom: 0px; width:100%; display:block;">
                                                <div style="float: left; display:flex;">
                                                    <label style="width:auto;">Email:</label>
                                                    <div id="author-email" style="width:100%; min-width: 40px; margin-left: 2px;" contenteditable="true">red_dragon@gmail.com</div>
                                                </div>
                                                    
                                                <div  style="float: right; display:flex;">
                                                    <label style="width:auto;">Nickname:</label>
                                                    <div id="author-nickname" style="min-width: 40px; margin-left: 2px;" contenteditable="true">Red_Dragon</div>
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
                <div class="sidebar-container">
                    <div id="tilesSelectorBar" style="text-align: center;">
                        <button id="triggerInnerMenuButton" style="width: 90%; padding-top: 2px; padding-bottom: 2px;"  class="old_button" 
                        onclick="triggerInnerMenu()">↓</button>
                        <div id="innerMenu" style="visibility: hidden;">
                            <div>
                                <button class="innerMenuButton" style="float:left;" onclick="previousElementsMenu()">←</button>
                                <label id="activeMenuLabel">base set</label>
                                <button class="innerMenuButton" style="float:right;" onclick="nextElementsMenu()">→</button>
                            </div>
                            <div id="elementsMenu0" setName="base set">
                                <button class="innerMenuButton" style="width:90%; opacity: 0.5;" id="loadQrnA" onclick="loadElementsGroup('qrnA', this)">side A</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnB" onclick="loadElementsGroup('qrnB', this)">side B</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnHeroes" onclick="loadElementsGroup('qrnHeroes', this)">Heroes</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnMinions" onclick="loadElementsGroup('qrnMinions', this)">Minions</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnFurnitures" onclick="loadElementsGroup('qrnFurnitures', this)">Furnitures</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnLocks" onclick="loadElementsGroup('qrnLocks', this)">Locks</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnTraps" onclick="loadElementsGroup('qrnTraps', this)">Traps</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnBosses" onclick="loadElementsGroup('qrnBosses', this)">Bosses</button>
                            </div>
                            
                            <div id="elementsMenu1" setName="return of valandor" style="display:none">
                                <button class="innerMenuButton" id="loadRvA" style="width:90%;" onclick="loadElementsGroup('rvA', this)">tiles unique side</button>
                                <button class="innerMenuButton" id="loadRvHeroes" style="width:90%;" onclick="loadElementsGroup('rvHeroes', this)">Heroes</button>
                                <button class="innerMenuButton" id="loadRvBosses" style="width:90%;" onclick="loadElementsGroup('rvBosses', this)">Bosses</button>
                            </div>
                            <div id="elementsMenu2" setName="tyrant of halpi" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadThA" onclick="loadElementsGroup('thA', this)">side A</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadThB" onclick="loadElementsGroup('thB', this)">side B</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadThB" onclick="loadElementsGroup('thHeroes', this)">heroes</button>
                            </div>
                            <div id="elementsMenu3" setName="the infernal crypt" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadCiA" onclick="loadElementsGroup('ciA', this)">side A</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadCiB" onclick="loadElementsGroup('ciB', this)">side B</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadCiMinions" onclick="loadElementsGroup('ciMinions', this)">minions</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadCiBosses" onclick="loadElementsGroup('ciBosses', this)">bosses</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadCiMarkers" onclick="loadElementsGroup('ciMarkers', this)">markers</button>
                            </div>
                            <div id="elementsMenu4" setName="the black fortress" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadFnMinions" onclick="loadElementsGroup('fnMinions', this)">Minions</button>
                            </div>
                            <div id="elementsMenu5" setName="warlord of galahir" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadSgA" onclick="loadElementsGroup('sgA', this)">side A</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadSgB" onclick="loadElementsGroup('sgB', this)">side B</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadSgHeroes" onclick="loadElementsGroup('sgHeroes', this)">heroes</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadSgMinions" onclick="loadElementsGroup('sgMinions', this)">minions</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadSgBosses" onclick="loadElementsGroup('sgBosses', this)">bosses</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadSgMarkers" onclick="loadElementsGroup('sgMarkers', this)">markers</button>
                            </div>

                            <div id="elementsMenu6" setName="eye of the abyss" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadOaHeroes" onclick="loadElementsGroup('oaHeroes', this)">heroes</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadOaBosses" onclick="loadElementsGroup('oaBosses', this)">bosses</button>
                            </div>

                            <div id="elementsMenu7" setName="silouhette kings of war" style="display:none">
                                
                                <div id="an_inner_menu_group" style="display: none;">
                                   
                                    <button class="innerMenuButton" style="width:90%;"  style="disaply: none;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'block';
                                    document.getElementById('an_inner_menu_group').style.display = 'none';
                                    ">↑</button>
                                    <label>nord alleance</label>
                                    <button class="innerMenuButton" style="width:90%;" id="loadAnHeroes" onclick="loadElementsGroup('anHeroes', this)">heroes</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadAnMinions" onclick="loadElementsGroup('anMinions', this)">minions</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadAnBosses" onclick="loadElementsGroup('anBosses', this)">bosses</button>
                                </div>
                                <div id="fa_inner_menu_group" style="display: none;">
                                   
                                    <button class="innerMenuButton" style="width:90%;"  style="disaply: none;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'block';
                                    document.getElementById('fa_inner_menu_group').style.display = 'none';
                                    ">↑</button>
                                    <label>Forces of the abyss</label>
                                    <button class="innerMenuButton" style="width:90%;" id="loadFaHeroes" onclick="loadElementsGroup('faHeroes', this)">heroes</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadFaMinions" onclick="loadElementsGroup('faMinions', this)">minions</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadFaBosses" onclick="loadElementsGroup('faBosses', this)">bosses</button>
                                </div>
                                <div id="rt_inner_menu_group" style="display: none;">
                                    <button class="innerMenuButton" style="width:90%;"  style="disaply: none;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'block';
                                    document.getElementById('rt_inner_menu_group').style.display = 'none';
                                    ">↑</button>
                                    <label>Kingdom of the Trident</label>
                                    <button class="innerMenuButton" style="width:90%;" id="loadRtMinions" onclick="loadElementsGroup('rtMinions', this)">minions</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadRtBosses" onclick="loadElementsGroup('rtBosses', this)">bosses</button>
                                </div>
                                <div id="sa_inner_menu_group" style="display: none;">
                                    <button class="innerMenuButton" style="width:90%;"  style="disaply: none;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'block';
                                    document.getElementById('sa_inner_menu_group').style.display = 'none';
                                    ">↑</button>
                                    <label>Salamandre</label>
                                    <button class="innerMenuButton" style="width:90%;" id="loadSaMinions" onclick="loadElementsGroup('saMinions', this)">minions</button>
                                    <button class="innerMenuButton" style="width:90%;" id="loadSaBosses" onclick="loadElementsGroup('saBosses', this)">bosses</button>
                                </div>
                                <div id="inner_menu_main">
                                    <button class="innerMenuButton" style="width:90%;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'none';
                                    document.getElementById('an_inner_menu_group').style.display = 'block';
                                    ">Nord alleance</button>
                                    <button class="innerMenuButton" style="width:90%;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'none';
                                    document.getElementById('fa_inner_menu_group').style.display = 'block';
                                    ">Forces of the abyss</button>
                                    <button class="innerMenuButton" style="width:90%;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'none';
                                    document.getElementById('rt_inner_menu_group').style.display = 'block';
                                    ">Kingdom of the Trident</button>
                                    <button class="innerMenuButton" style="width:90%;" onclick="
                                    document.getElementById('inner_menu_main').style.display = 'none';
                                    document.getElementById('sa_inner_menu_group').style.display = 'block';
                                    ">Salamandre</button>
                                </div>
                                
                            </div>

                            <div id="elementsMenu8" setName="markers and extra" style="display:none">
                                <button class="innerMenuButton" style="width:90%;" id="loadTextBoxes" onclick="loadElementsGroup('textBoxes', this)">Text</button>
                                <button class="innerMenuButton" style="width:90%;" id="loadQrnMarkers" onclick="loadElementsGroup('qrnMarkers', this)">Markers</button>
                            </div>
                            
                        </div>
                    </div>

                    <div class="sidenav" id="qrnA" style="visibility: ''">
    <image src="assets/tiles/qrnA/QRN-tile-3x4-A.png" set="qrnA" image="QRN-tile-3x4-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-3x6-A.png" set="qrnA" image="QRN-tile-3x6-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-4x8-A.png" set="qrnA" image="QRN-tile-4x8-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-4x8-A.png" set="qrnA" image="QRN-tile-4x8-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-5x8-A.png" set="qrnA" image="QRN-tile-5x8-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-5x8-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-6x4-A.png" set="qrnA" image="QRN-tile-6x4-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-6x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile-7x10-A.png" set="qrnA" image="QRN-tile-7x10-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-7x10-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01+-3x3-A.png" set="qrnA" image="QRN-tile01+-3x3-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01+-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02+-3x3-A.png" set="qrnA" image="QRN-tile02+-3x3-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02+-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-1x2-A.png" set="qrnA" image="QRN-tile01-1x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-1x2-A.png" set="qrnA" image="QRN-tile01-1x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-1x2-A.png" set="qrnA" image="QRN-tile01-1x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-1x2-A.png" set="qrnA" image="QRN-tile02-1x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-1x2-A.png" set="qrnA" image="QRN-tile02-1x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-2x2-A.png" set="qrnA" image="QRN-tile01-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-2x2-A.png" set="qrnA" image="QRN-tile01-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-2x2-A.png" set="qrnA" image="QRN-tile02-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-2x2-A.png" set="qrnA" image="QRN-tile02-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-2x3-A.png" set="qrnA" image="QRN-tile01-2x3-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-2x3-A.png" set="qrnA" image="QRN-tile02-2x3-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-3x1-A.png" set="qrnA" image="QRN-tile01-3x1-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-3x1-A.png" set="qrnA" image="QRN-tile01-3x1-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-3x1-A.png" set="qrnA" image="QRN-tile02-3x1-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-3x1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-4x5-A.png" set="qrnA" image="QRN-tile01-4x5-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-4x5-A.png" set="qrnA" image="QRN-tile02-4x5-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile01-6x6-A.png" set="qrnA" image="QRN-tile01-6x6-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-6x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tile02-6x6-A.png" set="qrnA" image="QRN-tile02-6x6-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-6x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileL01-2x2-A.png" set="qrnA" image="QRN-tileL01-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileL01-2x2-A.png" set="qrnA" image="QRN-tileL01-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileL02-2x2-A.png" set="qrnA" image="QRN-tileL02-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileL02-2x2-A.png" set="qrnA" image="QRN-tileL02-2x2-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>

    <image src="assets/tiles/qrnA/QRN-tileT01-4x5-A.png" set="qrnA" image="QRN-tileT01-4x5-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT01-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileT02-4x5-A.png" set="qrnA" image="QRN-tileT02-4x5-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT02-4x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnA/QRN-tileY-3x3-A.png" set="qrnA" image="QRN-tileY-3x3-A" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileY-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="qrnB" style="visibility: hidden;">
    <image src="assets/tiles/qrnB/QRN-tile-3x4-B.png" set="qrnB" image="QRN-tile-3x4-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-3x6-B.png" set="qrnB" image="QRN-tile-3x6-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-3x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-4x8-B.png" set="qrnB" image="QRN-tile-4x8-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-4x8-B.png" set="qrnB" image="QRN-tile-4x8-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-4x8_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-5x8-B.png" set="qrnB" image="QRN-tile-5x8-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-5x8-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-6x4-B.png" set="qrnB" image="QRN-tile-6x4-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-6x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile-7x10-B.png" set="qrnB" image="QRN-tile-7x10-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile-7x10-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01+-3x3-B.png" set="qrnB" image="QRN-tile01+-3x3-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01+-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02+-3x3-B.png" set="qrnB" image="QRN-tile02+-3x3-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02+-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-1x2-B.png" set="qrnB" image="QRN-tile01-1x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-1x2-B.png" set="qrnB" image="QRN-tile01-1x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-1x2-B.png" set="qrnB" image="QRN-tile01-1x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-1x2_3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-1x2-B.png" set="qrnB" image="QRN-tile02-1x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-1x2-B.png" set="qrnB" image="QRN-tile02-1x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-1x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-2x2-B.png" set="qrnB" image="QRN-tile01-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-2x2-B.png" set="qrnB" image="QRN-tile01-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-2x2-B.png" set="qrnB" image="QRN-tile02-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-2x2-B.png" set="qrnB" image="QRN-tile02-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-2x3-B.png" set="qrnB" image="QRN-tile01-2x3-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-2x3-B.png" set="qrnB" image="QRN-tile02-2x3-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-3x1-B.png" set="qrnB" image="QRN-tile01-3x1-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-3x1-B.png" set="qrnB" image="QRN-tile01-3x1-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-3x1_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-3x1-B.png" set="qrnB" image="QRN-tile02-3x1-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-3x1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-4x5-B.png" set="qrnB" image="QRN-tile01-4x5-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-4x5-B.png" set="qrnB" image="QRN-tile02-4x5-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile01-6x6-B.png" set="qrnB" image="QRN-tile01-6x6-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile01-6x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tile02-6x6-B.png" set="qrnB" image="QRN-tile02-6x6-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tile02-6x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileL01-2x2-B.png" set="qrnB" image="QRN-tileL01-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileL01-2x2-B.png" set="qrnB" image="QRN-tileL01-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL01-2x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileL02-2x2-B.png" set="qrnB" image="QRN-tileL02-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2_1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileL02-2x2-B.png" set="qrnB" image="QRN-tileL02-2x2-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileL02-2x2_2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileT01-4x5-B.png" set="qrnB" image="QRN-tileT01-4x5-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT01-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileT02-4x5-B.png" set="qrnB" image="QRN-tileT02-4x5-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileT02-4x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnB/QRN-tileY-3x3-B.png" set="qrnB" image="QRN-tileY-3x3-B" orientation="0" onMapZIndex="-1" flippable="yes" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="QRN-tileY-3x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>
                    <div class="sidenav" id="qrnHeroes" style="visibility: hidden;">
    <image src="assets/tiles/qrnHeroes/starting-position-hero.png" set="qrnHeroes" image="starting-position-hero" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnHeroes/hero-Danor-human-sorcerer.png" set="qrnHeroes" image="hero-Danor-human-sorcerer" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Danor-human-sorcerer" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnHeroes/hero-Madriga-elve-ranger.png" set="qrnHeroes" image="hero-Madriga-elve-ranger" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Madriga-elve-ranger" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnHeroes/hero-Orlaf-human-barbarian.png" set="qrnHeroes" image="hero-Orlaf-human-barbarian" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Orlaf-human-barbarian" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnHeroes/hero-Rordin-dwarf-warrior.png" set="qrnHeroes" image="hero-Rordin-dwarf-warrior" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Rordin-dwarf-warrior" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>
                    <div class="sidenav" id="qrnBosses" style="visibility: hidden;">
    <image src="assets/tiles/qrnBosses/starting-position-boss.png" set="qrnBosses" image="starting-position-boss" onMapZIndex="2" flippable="no" orientation="0" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-boss_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>    
    <image src="assets/tiles/qrnBosses/Boss-Elshara-Banshee.png" set="qrnBosses" image="Boss-Elshara-Banshee" onMapZIndex="2" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Elshara-Banshee_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnBosses/Boss-Grund-king-dwarf-undead.png" set="qrnBosses" image="Boss-Grund-king-dwarf-undead" onMapZIndex="2" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Grund-king-dwarf-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnBosses/Boss-Hoggar-Chaman-Troll-undead.png" set="qrnBosses" image="Boss-Hoggar-Chaman-Troll-undead" onMapZIndex="2" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Hoggar-Chaman-Troll-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnBosses/Boss-Mortibris-human-Necromancer.png" set="qrnBosses" image="Boss-Mortibris-human-Necromancer" onMapZIndex="2" flippable="no" orientation="0" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="Boss-Mortibris-human-Necromancer_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>
                    <div class="sidenav" id="qrnMinions" style="visibility: hidden;">
    <image src="assets/tiles/qrnMinions/starting-position-Minion-undead.png" set="qrnMinions" image="starting-position-Minion-undead" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-Minion-undead_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-bone-pile.png" set="qrnMinions" image="Minion-bone-pile" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="Minion-bone-pile_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-dwarf-Revenant.png" set="qrnMinions" image="Minion-dwarf-Revenant" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-dwarf-Revenant_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-squeleton-bowman.png" set="qrnMinions" image="Minion-squeleton-bowman" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-bowman_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-squeleton-warrior-01.png" set="qrnMinions" image="Minion-squeleton-warrior-01" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-warrior-01_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-squeleton-warrior-02.png" set="qrnMinions" image="Minion-squeleton-warrior-02" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-squeleton-warrior-02_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-Troll-warrior-zombies.png" set="qrnMinions" image="Minion-Troll-warrior-zombies" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-Troll-warrior-zombies_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-wraith.png" set="qrnMinions" image="Minion-wraith" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-wraith_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-zombies-1.png" set="qrnMinions" image="Minion-zombies-1" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-zombies-2.png" set="qrnMinions" image="Minion-zombies-2" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMinions/Minion-zombies-armored.png" set="qrnMinions" image="Minion-zombies-armored" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="Minion-zombies-armored_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>
                    <div class="sidenav" id="qrnFurnitures" style="visibility: hidden;">
    <image src="assets/tiles/qrnFurnitures/door-large-Lock-red.png" set="qrnFurnitures" image="door-large-Lock-red" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-large-Lock-red" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/door-large-Lock-yellow.png" set="qrnFurnitures" image="door-large-Lock-yellow" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-large-Lock-yellow" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/door-Petit-Lock-red.png" set="qrnFurnitures" image="door-Petit-Lock-red" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-red" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/door-Petit-Lock-Violet.png" set="qrnFurnitures" image="door-Petit-Lock-Violet" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-Violet" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/door-Petit-Lock-yellow.png" set="qrnFurnitures" image="door-Petit-Lock-yellow" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="door-Petit-Lock-yellow" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-barrel-1.png" set="qrnFurnitures" image="furniture-barrel-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-barrel-1.png" set="qrnFurnitures" image="furniture-barrel-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-barrel-1.png" set="qrnFurnitures" image="furniture-barrel-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel_3" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-barrel-1.png" set="qrnFurnitures" image="furniture-barrel-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel_4" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-barrel-2.png" set="qrnFurnitures" image="furniture-barrel-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-barrel-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-chest-1.png" set="qrnFurnitures" image="furniture-chest-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-chest-1.png" set="qrnFurnitures" image="furniture-chest-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-chest-1.png" set="qrnFurnitures" image="furniture-chest-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest_3" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-chest-1.png" set="qrnFurnitures" image="furniture-chest-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest_4" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-chest-2.png" set="qrnFurnitures" image="furniture-chest-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-chest-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-Buffet-1.png" set="qrnFurnitures" image="furniture-Buffet-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Buffet-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-library-1.png" set="qrnFurnitures" image="furniture-library-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-library-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-lectern-1.png" set="qrnFurnitures" image="furniture-lectern-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-lectern-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-lectern-2.png" set="qrnFurnitures" image="furniture-lectern-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-lectern-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-rack-1.png" set="qrnFurnitures" image="furniture-rack-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-rack-1_2.png" set="qrnFurnitures" image="furniture-rack-1_2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-1_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <!--<image src="assets/tiles/qrnFurnitures/furniture-rack-2.png" set="qrnFurnitures" image="furniture-rack-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-rack-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <!--<image src="assets/tiles/qrnFurnitures/furniture-small-door-01.png" set="qrnFurnitures" image="furniture-small-door-01" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-small-door-01" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-Table-1.png" set="qrnFurnitures" image="furniture-Table-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Table_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnFurnitures/furniture-Table-1.png" set="qrnFurnitures" image="furniture-Table-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Table_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-Table-2.png" set="qrnFurnitures" image="furniture-Table-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-Table-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-throne-dwarf.png" set="qrnFurnitures" image="furniture-throne-dwarf" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-dwarf" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-throne-dwarf_2.png" set="qrnFurnitures" image="furniture-throne-dwarf_2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-dwarf_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <!--<image src="assets/tiles/qrnFurnitures/furniture-throne-human.png" set="qrnFurnitures" image="furniture-throne-human" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-throne-human" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-tomb-1.png" set="qrnFurnitures" image="furniture-tomb-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-tomb-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-tomb-2.png" set="qrnFurnitures" image="furniture-tomb-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-tomb-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <!--<image src="assets/tiles/qrnFurnitures/furniture-well-1.png" set="qrnFurnitures" image="furniture-well-1" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
    <image src="assets/tiles/qrnFurnitures/furniture-well-1_2.png" set="qrnFurnitures" image="furniture-well-1_2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-1_2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <!--<image src="assets/tiles/qrnFurnitures/furniture-well-2.png" set="qrnFurnitures" image="furniture-well-2" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="furniture-well-2" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>-->
                    </div>
                    <div class="sidenav" id="qrnLocks" style="visibility: hidden;">
    <image src="assets/tiles/qrnLocks/chest-Lock-red-1.png" set="qrnLocks" image="chest-Lock-red-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-red-2.png" set="qrnLocks" image="chest-Lock-red-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-red-3.png" set="qrnLocks" image="chest-Lock-red-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-red-4.png" set="qrnLocks" image="chest-Lock-red-4" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-red-5.png" set="qrnLocks" image="chest-Lock-red-5" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-yellow-1.png" set="qrnLocks" image="chest-Lock-yellow-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-yellow-2.png" set="qrnLocks" image="chest-Lock-yellow-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/chest-Lock-yellow-3.png" set="qrnLocks" image="chest-Lock-yellow-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="chest-Lock-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-red-1.png" set="qrnLocks" image="door-large-door-red-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-red-2.png" set="qrnLocks" image="door-large-door-red-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-red-3.png" set="qrnLocks" image="door-large-door-red-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-red-4.png" set="qrnLocks" image="door-large-door-red-4" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-red-5.png" set="qrnLocks" image="door-large-door-red-5" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-yellow-1.png" set="qrnLocks" image="door-large-door-yellow-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-yellow-2.png" set="qrnLocks" image="door-large-door-yellow-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-large-door-yellow-3.png" set="qrnLocks" image="door-large-door-yellow-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-large-door-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-purple-2.png" set="qrnLocks" image="door-small-door-purple-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-purple-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-purple-3.png" set="qrnLocks" image="door-small-door-purple-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-purple-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-red-1.png" set="qrnLocks" image="door-small-door-red-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-red-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-red-2.png" set="qrnLocks" image="door-small-door-red-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-red-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-red-3.png" set="qrnLocks" image="door-small-door-red-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-red-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-red-4.png" set="qrnLocks" image="door-small-door-red-4" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-red-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-red-5.png" set="qrnLocks" image="door-small-door-red-5" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-red-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-yellow-1.png" set="qrnLocks" image="door-small-door-yellow-1" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-yellow-2.png" set="qrnLocks" image="door-small-door-yellow-2" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/door-small-door-yellow-3.png" set="qrnLocks" image="door-small-door-yellow-3" orientation="0" single="no" pieceType="tile" snap="no" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="door-small-door-yellow-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-crush-1.png" set="qrnLocks" image="Token-lock-crush-1" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-crush-2.png" set="qrnLocks" image="Token-lock-crush-2" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-crush-3.png" set="qrnLocks" image="Token-lock-crush-3" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-crush-4.png" set="qrnLocks" image="Token-lock-crush-4" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-crush-5.png" set="qrnLocks" image="Token-lock-crush-5" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-crush-5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-magical-1.png" set="qrnLocks" image="Token-lock-magical-1" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-magical-2.png" set="qrnLocks" image="Token-lock-magical-2" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-magical-3.png" set="qrnLocks" image="Token-lock-magical-3" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-magical-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-pick-1.png" set="qrnLocks" image="Token-lock-pick-1" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-pick-2.png" set="qrnLocks" image="Token-lock-pick-2" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-pick-3.png" set="qrnLocks" image="Token-lock-pick-3" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnLocks/Token-lock-pick-4.png" set="qrnLocks" image="Token-lock-pick-4" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-lock-pick-4_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>
                    <div class="sidenav" id="qrnTraps" style="visibility: hidden;">
    <image src="assets/tiles/qrnTraps/marker-Trap-Asphyxiant-gas-DS.png" set="qrnTraps" image="marker-Trap-Asphyxiant-gas-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-Asphyxiant-gas-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-ball.png" set="qrnTraps" image="marker-Trap-ball" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-ball_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-Cage-DS.png" set="qrnTraps" image="marker-Trap-Cage-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-Cage-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-dart-DS.png" set="qrnTraps" image="marker-Trap-dart-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-dart-poisoned-DS.png" set="qrnTraps" image="marker-Trap-dart-poisoned-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-poisoned-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-dart-poisoned.png" set="qrnTraps" image="marker-Trap-dart-poisoned" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-dart-poisoned_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-large-pit.png" set="qrnTraps" image="marker-Trap-large-pit" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-large-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-pit-DS.png" set="qrnTraps" image="marker-Trap-pit-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-pit-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-pit.png" set="qrnTraps" image="marker-Trap-pit" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-poisoned-dart-DS.png" set="qrnTraps" image="marker-Trap-poisoned-dart-DS" orientation="0" single="no" onMapZIndex="2" flippable="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="marker-Trap-poisoned-dart-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-Poisonous-spore-DS.png" set="qrnTraps" image="marker-Trap-Poisonous-spore-DS" orientation="0" single="no" onMapZIndex="2" flippable="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="marker-Trap-Poisonous-spore-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-quagmire-DS.png" set="qrnTraps" image="marker-Trap-quagmire-DS" orientation="0" single="no" pieceType="tile" snap="yes"  onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-quagmire-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-sharp-blade-DS.png" set="qrnTraps" image="marker-Trap-sharp-blade-DS" orientation="0" single="no" pieceType="tile" snap="yes"  onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-sharp-blade-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-small-pit.png" set="qrnTraps" image="marker-Trap-small-pit" orientation="0" single="no" pieceType="tile" snap="yes"  onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-small-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-Sort.png" set="qrnTraps" image="marker-Trap-Sort" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-Sort_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-Spider-Web.png" set="qrnTraps" image="marker-Trap-Spider-Web" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-Spider-Web_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-spike-pit.png" set="qrnTraps" image="marker-Trap-spike-pit" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-spike-pit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnTraps/marker-Trap-stunner-DS.png" set="qrnTraps" image="marker-Trap-stunner-DS" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="marker-Trap-stunner-DS_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="rvA" style="visibility: hidden;">
    <image src="assets/tiles/rvA/RV-1.png" set="rvA" image="RV-1" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-2.png" set="rvA" image="RV-2" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-2x2.png" set="rvA" image="RV-2x2" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-2x2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3+3S.png" set="rvA" image="RV-3+3S" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3+3S_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3.png" set="rvA" image="RV-3" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3L.png" set="rvA" image="RV-3L" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3L_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3x3V.png" set="rvA" image="RV-3x3V" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3x3V_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3x5.png" set="rvA" image="RV-3x5" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3x5_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-3x6.png" set="rvA" image="RV-3x6" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-3x6_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-4T.png" set="rvA" image="RV-4T" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-4T_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-5x2E.png" set="rvA" image="RV-5x2E" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-5x2E_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-5x2TT.png" set="rvA" image="RV-5x2TT" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-5x2TT_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvA/RV-7x7rond.png" set="rvA" image="RV-7x7rond" onMapZIndex="-1" flippable="no" orientation="0" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-7x7rond_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="rvHeroes" style="visibility: hidden;">
    <image src="assets/tiles/rvHeroes/RV-starting-position-hero.png" set="rvHeroes" image="RV-starting-position-hero" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvHeroes/RV-hero-Valandor-warrior-sorcerer.png" set="rvHeroes" image="RV-hero-Valandor-warrior-sorcerer" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="RV-hero-Valandor-warrior-sorcerer" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="rvBosses" style="visibility: hidden;">
    <image src="assets/tiles/rvBosses/RV-starting-position-boss.png" set="rvBosses" image="RV-starting-position-boss" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="RV-starting-position-boss_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rvBosses/RV-Boss-Bael-Seigneur-demon-undead.png" set="rvBosses" image="RV-Boss-Bael-Seigneur-demon-undead" orientation="0" onMapZIndex="2" flippable="no" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="RV-Boss-Bael-Seigneur-demon-undead" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="thA" style="visibility: hidden;">
    <image src="assets/tiles/thA/TH-tile-1x3-A.png" set="thA" image="TH-tile-1x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-1x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-2x3-A.png" set="thA" image="TH-tile-2x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-2x4-A.png" set="thA" image="TH-tile-2x4-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-2x5-A.png" set="thA" image="TH-tile-2x5-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-3x4-A.png" set="thA" image="TH-tile-3x4-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-3x5-A.png" set="thA" image="TH-tile-3x5-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-3x6-A.png" set="thA" image="TH-tile-3x6-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x6-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-5x7-A.png" set="thA" image="TH-tile-5x7-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-5x7-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thA/TH-tile-8x11-A.png" set="thA" image="TH-tile-8x11-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-8x11-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="thB" style="visibility: hidden;">
    <image src="assets/tiles/thB/TH-tile-1x3-B.png" set="thB" image="TH-tile-1x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-1x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-2x3-B.png" set="thB" image="TH-tile-2x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-2x4-B.png" set="thB" image="TH-tile-2x4-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-2x5-B.png" set="thB" image="TH-tile-2x5-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-2x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-3x4-B.png" set="thB" image="TH-tile-3x4-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x4-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-3x5-B.png" set="thB" image="TH-tile-3x5-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-3x6-B.png" set="thB" image="TH-tile-3x6-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-3x6-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-5x7-B.png" set="thB" image="TH-tile-5x7-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-5x7-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thB/TH-tile-8x11-B.png" set="thB" image="TH-tile-8x11-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-tile-8x11-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="thHeroes" style="visibility: hidden;">
    <image src="assets/tiles/thHeroes/TH-starting-position-hero.png" set="thHeroes" image="TH-starting-position-hero" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="TH-starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thHeroes/hero-Ally-Halfling-Thief.png" set="thHeroes" image="hero-Ally-Halfling-Thief" flippable="no" orientation="0" onMapZIndex="2" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Ally-Halfling-Thief" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thHeroes/hero-Brellin-human-sorcerer.png" set="thHeroes" image="hero-Brellin-human-sorcerer" flippable="no" orientation="0" onMapZIndex="2" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Brellin-human-sorcerer" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thHeroes/hero-Ibrahim-human-Paladin.png" set="thHeroes" image="hero-Ibrahim-human-Paladin" flippable="no" orientation="0" onMapZIndex="2" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Ibrahim-human-Paladin" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/thHeroes/hero-Tharn-dwarf-warrior.png" set="thHeroes" image="hero-Tharn-dwarf-warrior" flippable="no" orientation="0" onMapZIndex="2" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Tharn-dwarf-warrior" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="ciA" style="visibility: hidden;">
    <image src="assets/tiles/ciA/CI-01-1x1-A.png" set="ciA" image="CI-01-1x1-A" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-01-1x1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-01-1x2-A.png" set="ciA" image="CI-01-1x2-A" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-01-1x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-1-2x2-A.png" set="ciA" image="CI-1-2x2-A" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-1-2x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile-1x2-A.png" set="ciA" image="CI-tile-1x2-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-1x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile-1x3-A.png" set="ciA" image="CI-tile-1x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-1x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile-2x3-A.png" set="ciA" image="CI-tile-2x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-2x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile-5x5-A.png" set="ciA" image="CI-tile-5x5-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-5x5-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile-8x11-A.png" set="ciA" image="CI-tile-8x11-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-8x11-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile01-4x7-A.png" set="ciA" image="CI-tile01-4x7-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile01-4x7-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciA/CI-tile02-4x7-A.png" set="ciA" image="CI-tile02-4x7-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile02-4x7-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>

                    </div>

                    <div class="sidenav" id="ciB" style="visibility: hidden;">
    <image src="assets/tiles/ciB/CI-02-1x1-B.png" set="ciB" image="CI-02-1x1-B" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-02-1x1-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-02-1x2-B.png" set="ciB" image="CI-02-1x2-B" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-02-1x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-02-2x2-B.png" set="ciB" image="CI-02-2x2-B" flippable="no" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-02-2x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile-1x2-B.png" set="ciB" image="CI-tile-1x2-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-1x2-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile-1x3-B.png" set="ciB" image="CI-tile-1x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-1x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile-2x3-B.png" set="ciB" image="CI-tile-2x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-2x3-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile-5x5-B.png" set="ciB" image="CI-tile-5x5-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-5x5-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile-8x11-B.png" set="ciB" image="CI-tile-8x11-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile-8x11-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile01-4x7-B.png" set="ciB" image="CI-tile01-4x7-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile01-4x7-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciB/CI-tile02-4x7-B.png" set="ciB" image="CI-tile02-4x7-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-tile02-4x7-B" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>

                   <div class="sidenav" id="ciMinions" style="visibility: hidden;">
    <image src="assets/tiles/ciMinions/starting-position-Abyssal-minions.png" set="ciMinions" image="starting-position-Abyssal-minions" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="starting-position-Abyssal-minions_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Abyssal-guardian.png" set="ciMinions" image="CI-Minion-Abyssal-guardian" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Abyssal-guardian_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Efrit.png" set="ciMinions" image="CI-Minion-Efrit" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Efrit_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Infernal-Dog.png" set="ciMinions" image="CI-Minion-Infernal-Dog" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Infernal-Dog_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Minor-Abyssal-fire-carrier.png" set="ciMinions" image="CI-Minion-Minor-Abyssal-fire-carrier" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Minor-Abyssal-fire-carrier_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Moloch.png" set="ciMinions" image="CI-Minion-Moloch" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Moloch_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Succube.png" set="ciMinions" image="CI-Minion-Succube" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Succube_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-Tortured-mind.png" set="ciMinions" image="CI-Minion-Tortured-mind" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-Tortured-mind_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-warrior-Abyssal-01.png" set="ciMinions" image="CI-Minion-warrior-Abyssal-01" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-warrior-Abyssal-01_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMinions/CI-Minion-warrior-Abyssal-02.png" set="ciMinions" image="CI-Minion-warrior-Abyssal-02" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Minion-warrior-Abyssal-02_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>

                   <div class="sidenav" id="ciBosses" style="visibility: hidden;">
    <image src="assets/tiles/ciBosses/CI-starting-position-boss.png" set="ciBosses" image="CI-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-starting-position-boss_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciBosses/CI-Drech-Nok-the-destroyer-Abyssal-champions.png" set="ciBosses" image="CI-Drech-Nok-the-destroyer-Abyssal-champions" flippable="no" orientation="0" onMapZIndex="2" single="yes" pieceType="tile" snap="no" oncontextmenu="return false;" id="CI-Drech-Nok-the-destroyer-Abyssal-champions_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>                   

                   <div class="sidenav" id="ciBosses" style="visibility: hidden;">
    <image src="assets/tiles/ciHeroes/CI-starting-position-hero.png" set="ciHeroes" image="CI-starting-position-hero" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciHeroes/hero-Arianya-Naiad-demon-huntress.png" set="ciHeroes" image="hero-Arianya-Naiad-demon-huntress" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Arianya-Naiad-demon-huntress_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciHeroes/hero-Ragran-human-Barbarian.png" set="ciHeroes" image="hero-Ragran-human-Barbarian" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Ragran-human-Barbarian_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciHeroes/hero-Semential-human-Sorcerer.png" set="ciHeroes" image="hero-Semential-human-Sorcerer" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Semential-human-Sorcerer_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciHeroes/hero-Venetia-human-priest.png" set="ciHeroes" image="hero-Venetia-human-priest" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Venetia-human-priest_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>

                   <div class="sidenav" id="ciMarkers" style="visibility: hidden;">
    <image src="assets/tiles/ciMarkers/CI-marker-lava-projection-01.png" set="ciMarkers" image="CI-marker-lava-projection-01" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-marker-lava-projection-01_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMarkers/CI-marker-lava-projection-02.png" set="ciMarkers" image="CI-marker-lava-projection-02" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-marker-lava-projection-02_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/ciMarkers/CI-marker-rose-of-fire1.png" set="ciMarkers" image="CI-marker-rose-of-fire1" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="CI-marker-rose-of-fire1_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>

                   <div class="sidenav" id="fnMinions" style="visibility: hidden;">
    <image src="assets/tiles/fnMinions/FN-starting-position-Minion-undead.png" set="fnMinions" image="FN-starting-position-Minion-undead" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="FN-starting-position-Minion-undead_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/fnMinions/FN-Minion-Bat-Swarm.png" set="fnMinions" image="FN-Minion-Bat-Swarm" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="FN-Minion-Bat-Swarm_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/fnMinions/FN-Minion-Rat.png" set="fnMinions" image="FN-Minion-Rat" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="FN-Minion-Rat_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/fnMinions/FN-Minion-Spider.png" set="fnMinions" image="FN-Minion-Spider" flippable="no" orientation="0" onMapZIndex="2" single="no" pieceType="tile" snap="no" oncontextmenu="return false;" id="FN-Minion-Spider_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                   </div>

                   <div class="sidenav" id="sgA" style="visibility: hidden;">
    <image src="assets/tiles/sgA/SG-tile-1x2-A.png" set="sgA" image="SG-tile-1x2-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-1x2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-1x3-A.png" set="sgA" image="SG-tile-1x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-1x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x2-01-A.png" set="sgA" image="SG-tile-2x2-01-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-01-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x2-02-A.png" set="sgA" image="SG-tile-2x2-02-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-02-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x2-L-01-A.png" set="sgA" image="SG-tile-2x2-L-01-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-L-01-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x2-L-02-A.png" set="sgA" image="SG-tile-2x2-L-02-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-L-02-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x3_1-A.png" set="sgA" image="SG-tile-2x3_1-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x3_1-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-2x3_2-A.png" set="sgA" image="SG-tile-2x3_2-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x3_2-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-3x3-A.png" set="sgA" image="SG-tile-3x3-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-3x3-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-3x3-Y-A.png" set="sgA" image="SG-tile-3x3-Y-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-3x3-Y-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-4x4-A.png" set="sgA" image="SG-tile-4x4-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-4x4-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-5x7-01-A.png" set="sgA" image="SG-tile-5x7-01-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-5x7-01-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-5x7-02-A.png" set="sgA" image="SG-tile-5x7-02-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-5x7-02-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgA/SG-tile-7x8-A.png" set="sgA" image="SG-tile-7x8-A" flippable="yes" orientation="0" onMapZIndex="-1" single="yes" pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-7x8-A" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="sgB" style="visibility: hidden;">
    <image src="assets/tiles/sgB/SG-tile-1x2-B.png" set="sgB" image="SG-tile-1x2-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-1x2-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-1x3-B.png" set="sgB" image="SG-tile-1x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-1x3-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x2-01-B.png" set="sgB" image="SG-tile-2x2-01-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-01-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x2-02-B.png" set="sgB" image="SG-tile-2x2-02-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-02-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x2-L-01-B.png" set="sgB" image="SG-tile-2x2-L-01-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-L-01-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x2-L-02-B.png" set="sgB" image="SG-tile-2x2-L-02-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x2-L-02-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x3_1-B.png" set="sgB" image="SG-tile-2x3_1-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x3_1-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-2x3_2-B.png" set="sgB" image="SG-tile-2x3_2-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-2x3_2-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-3x3-B.png" set="sgB" image="SG-tile-3x3-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-3x3-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-3x3-Y-B.png" set="sgB" image="SG-tile-3x3-Y-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-3x3-Y-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-4x4-B.png" set="sgB" image="SG-tile-4x4-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-4x4-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-5x7-01-B.png" set="sgB" image="SG-tile-5x7-01-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-5x7-01-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-5x7-02-B.png" set="sgB" image="SG-tile-5x7-02-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-5x7-02-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgB/SG-tile-7x8-B.png" set="sgB" image="SG-tile-7x8-B" flippable="yes" orientation="0" onMapZIndex="-1" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-tile-7x8-B"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="sgHeroes" style="visibility: hidden;">
    <image src="assets/tiles/sgHeroes/SG-starting-position-hero.png" set="sgHeroes" image="SG-starting-position-hero" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-starting-position-hero"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgHeroes/SG-hero-Guraf-warrior-dwarf.png" set="sgHeroes" image="SG-hero-Guraf-warrior-dwarf" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-hero-Guraf-warrior-dwarf"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgHeroes/SG-hero-Hrrath-warrior-Salamander.png" set="sgHeroes" image="SG-hero-Hrrath-warrior-Salamander" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-hero-Hrrath-warrior-Salamander"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgHeroes/SG-hero-Kapoka-Druid-sylvan.png" set="sgHeroes" image="SG-hero-Kapoka-Druid-sylvan" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-hero-Kapoka-Druid-sylvan"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgHeroes/SG-hero-Thesilar-ranger-elve.png" set="sgHeroes" image="SG-hero-Thesilar-ranger-elve" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-hero-Thesilar-ranger-elve"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="sgMinions" style="visibility: hidden;">
    <image src="assets/tiles/sgMinions/SG-starting-position-Minion-green-skin.png" set="sgMinions" image="SG-starting-position-Minion-green-skin" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-starting-position-Minion-green-skin_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-ax-orc.png" set="sgMinions" image="SG-Minion-ax-orc" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-ax-orc_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-greatax-orc.png" set="sgMinions" image="SG-Minion-greatax-orc" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-greatax-orc_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-mawbeast.png" set="sgMinions" image="SG-Minion-mawbeast" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-mawbeast_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-spitters-Goblins.png" set="sgMinions" image="SG-Minion-spitters-Goblins" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-spitters-Goblins_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-spitters-orc.png" set="sgMinions" image="SG-Minion-spitters-orc" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-spitters-orc_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-swarm-orclins.png" set="sgMinions" image="SG-Minion-swarm-orclins" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-swarm-orclins_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-Troll.png" set="sgMinions" image="SG-Minion-Troll" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-Troll_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-warrior-Goblins-01.png" set="sgMinions" image="SG-Minion-warrior-Goblins-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-warrior-Goblins-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMinions/SG-Minion-warrior-Goblins-02.png" set="sgMinions" image="SG-Minion-warrior-Goblins-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Minion-warrior-Goblins-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="sgBosses" style="visibility: hidden;">
    <image src="assets/tiles/sgBosses/SG-starting-position-boss.png" set="sgBosses" image="SG-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SG-starting-position-boss_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgBosses/SG-Thrundak-Barbarianorc.png" set="sgBosses" image="SG-Thrundak-Barbarianorc" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Thrundak-Barbarianorc_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="sgMarkers" style="visibility: hidden;">
    <image src="assets/tiles/sgMarkers/SG-Token-green-rage.png" set="sgMarkers" image="SG-Token-green-rage" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Token-green-rage_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/sgMarkers/SG-Token-merchandise.png" set="sgMarkers" image="SG-Token-merchandise" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SG-Token-merchandise_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="oaHeroes" style="visibility: hidden;">
    <image src="assets/tiles/oaHeroes/OA-starting-position-hero.png" set="oaHeroes" image="OA-starting-position-hero" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="OA-starting-position-hero"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/oaHeroes/hero-Artakl-watchman-Ghekkotah.png" set="oaHeroes" image="hero-Artakl-watchman-Ghekkotah" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Artakl-watchman-Ghekkotah"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/oaHeroes/hero-Eckter-defender-placoderm.png" set="oaHeroes" image="hero-Eckter-defender-placoderm" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Eckter-defender-placoderm"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/oaHeroes/hero-Jarvis-Necromancer-Ophidian.png" set="oaHeroes" image="hero-Jarvis-Necromancer-Ophidian" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Jarvis-Necromancer-Ophidian"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/oaHeroes/hero-Magnilde-Varangur-demon-huntress.png" set="oaHeroes" image="hero-Magnilde-Varangur-demon-huntress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="hero-Magnilde-Varangur-demon-huntress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="oaBosses" style="visibility: hidden;">
    <image src="assets/tiles/oaBosses/OA-starting-position-boss.png" set="oaBosses" image="OA-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="yes" oncontextmenu="return false;" id="OA-starting-position-boss"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/oaBosses/OA-Mau-ti-bu-su-Abyssal-temptress.png" set="oaBosses" image="OA-Mau-ti-bu-su-Abyssal-temptress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="OA-Mau-ti-bu-su-Abyssal-temptress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="anHeroes" style="visibility: hidden;">
<image src="assets/tiles/anBosses/AN-starting-position-hero.png" set="anHeroes" image="AN-starting-position-hero" orientation="0" onMapZIndex="2" flippable="no" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="AN-starting-position-hero_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Berserker-half-elve-01.png" set="anHeroes" image="AN-hero-Berserker-half-elve-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Berserker-half-elve-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Berserker-half-elve-02.png" set="anHeroes" image="AN-hero-Berserker-half-elve-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Berserker-half-elve-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-clan-dwarfwith-spike.png" set="anHeroes" image="AN-hero-clan-dwarfwith-spike" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-clan-dwarfwith-spike"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-clan-hunter-dwarf-01.png" set="anHeroes" image="AN-hero-clan-hunter-dwarf-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-clan-hunter-dwarf-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-clan-hunter-dwarf-02.png" set="anHeroes" image="AN-hero-clan-hunter-dwarf-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-clan-hunter-dwarf-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Huscarl-01.png" set="anHeroes" image="AN-hero-Huscarl-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Huscarl-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Huscarl-02.png" set="anHeroes" image="AN-hero-Huscarl-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Huscarl-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Ice-huntress.png" set="anHeroes" image="AN-hero-Ice-huntress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Ice-huntress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Ice-Naiads01.png" set="anHeroes" image="AN-hero-Ice-Naiads01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Ice-Naiads01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Ice-Naiads02.png" set="anHeroes" image="AN-hero-Ice-Naiads02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Ice-Naiads02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-ice-queen.png" set="anHeroes" image="AN-hero-ice-queen" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-ice-queen"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-iceblade.png" set="anHeroes" image="AN-hero-iceblade" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-iceblade"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Northern-alliance-lord.png" set="anHeroes" image="AN-hero-Northern-alliance-lord" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Northern-alliance-lord"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Skald-Bard.png" set="anHeroes" image="AN-hero-Skald-Bard" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Skald-Bard"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anHeroes/AN-hero-Thegn.png" set="anHeroes" image="AN-hero-Thegn" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-hero-Thegn"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="anMinions" style="visibility: hidden;">
<image src="assets/tiles/anMinions/AN-starting-position-Minion-Northern-alliance.png" set="anMinions" image="AN-starting-position-Minion-Northern-alliance" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="AN-starting-position-Minion-Northern-alliance_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion--Thegn-on-Frostfang.png" set="anMinions" image="AN-Minion--Thegn-on-Frostfang" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion--Thegn-on-Frostfang_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Berserker-half-elve-01.png" set="anMinions" image="AN-Minion-Berserker-half-elve-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Berserker-half-elve-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Berserker-half-elve-02.png" set="anMinions" image="AN-Minion-Berserker-half-elve-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Berserker-half-elve-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clan's-dwarfwith-axe.png" set="anMinions" image="AN-Minion-clan's-dwarfwith-axe" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clan's-dwarfwith-axe_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clan's-dwarfwith-spike.png" set="anMinions" image="AN-Minion-clan's-dwarfwith-spike" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clan's-dwarfwith-spike_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clan-hunter-01.png" set="anMinions" image="AN-Minion-clan-hunter-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clan-hunter-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clan-hunter-02.png" set="anMinions" image="AN-Minion-clan-hunter-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clan-hunter-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clan-hunter-dwarf-01.png" set="anMinions" image="AN-Minion-clan-hunter-dwarf-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clan-hunter-dwarf-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clansmen-with-axe-01.png" set="anMinions" image="AN-Minion-clansmen-with-axe-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clansmen-with-axe-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clansmen-with-axe-02.png" set="anMinions" image="AN-Minion-clansmen-with-axe-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clansmen-with-axe-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clansmen-with-sword-01.png" set="anMinions" image="AN-Minion-clansmen-with-sword-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clansmen-with-sword-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clansmen-with-sword-02.png" set="anMinions" image="AN-Minion-clansmen-with-sword-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clansmen-with-sword-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-clansmen-with-two-handed-weapon.png" set="anMinions" image="AN-Minion-clansmen-with-two-handed-weapon" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-clansmen-with-two-handed-weapon_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-elve-du-Clan.png" set="anMinions" image="AN-Minion-elve-du-Clan" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-elve-du-Clan_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-hunter-Clan-dwarf-02.png" set="anMinions" image="AN-Minion-hunter-Clan-dwarf-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-hunter-Clan-dwarf-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Huscarl-01.png" set="anMinions" image="AN-Minion-Huscarl-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Huscarl-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Huscarl-02.png" set="anMinions" image="AN-Minion-Huscarl-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Huscarl-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-ice-elementary.png" set="anMinions" image="AN-Minion-ice-elementary" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-ice-elementary_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Ice-huntress.png" set="anMinions" image="AN-Minion-Ice-huntress" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Ice-huntress_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Ice-Naiads01.png" set="anMinions" image="AN-Minion-Ice-Naiads01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Ice-Naiads01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Ice-Naiads02.png" set="anMinions" image="AN-Minion-Ice-Naiads02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Ice-Naiads02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-ice-queen.png" set="anMinions" image="AN-Minion-ice-queen" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-ice-queen_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-iceblade.png" set="anMinions" image="AN-Minion-iceblade" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-iceblade_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Snow-troll01.png" set="anMinions" image="AN-Minion-Snow-troll01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Snow-troll01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-Snow-troll02.png" set="anMinions" image="AN-Minion-Snow-troll02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-Snow-troll02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anMinions/AN-Minion-tundra-wolf.png" set="anMinions" image="AN-Minion-tundra-wolf" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Minion-tundra-wolf_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="anBosses" style="visibility: hidden;">
<image src="assets/tiles/anBosses/AN-starting-position-boss.png" set="anBosses" image="AN-starting-position-boss" onMapZIndex="2" flippable="no" orientation="0" single="no" pieceType="tile" snap="yes" oncontextmenu="return false;" id="AN-starting-position-boss_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>    
<image src="assets/tiles/anBosses/AN-Boss-Berserker-half-elve-01.png" set="anBosses" image="AN-Boss-Berserker-half-elve-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Berserker-half-elve-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Berserker-half-elve-02.png" set="anBosses" image="AN-Boss-Berserker-half-elve-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Berserker-half-elve-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Cold-giant.png" set="anBosses" image="AN-Boss-Cold-giant" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Cold-giant"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Huscarl-01.png" set="anBosses" image="AN-Boss-Huscarl-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Huscarl-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Huscarl-02.png" set="anBosses" image="AN-Boss-Huscarl-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Huscarl-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-ice-elementary.png" set="anBosses" image="AN-Boss-ice-elementary" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-ice-elementary"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Ice-huntress.png" set="anBosses" image="AN-Boss-Ice-huntress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Ice-huntress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Ice-Naiads01.png" set="anBosses" image="AN-Boss-Ice-Naiads01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Ice-Naiads01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Ice-Naiads02.png" set="anBosses" image="AN-Boss-Ice-Naiads02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Ice-Naiads02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-ice-queen.png" set="anBosses" image="AN-Boss-ice-queen" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-ice-queen"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-iceblade.png" set="anBosses" image="AN-Boss-iceblade" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-iceblade"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Northern-alliance-lord.png" set="anBosses" image="AN-Boss-Northern-alliance-lord" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Northern-alliance-lord"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Seigneur-sur-Croc-de-Givre.png" set="anBosses" image="AN-Boss-Seigneur-sur-Croc-de-Givre" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Seigneur-sur-Croc-de-Givre"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Skald-Bard.png" set="anBosses" image="AN-Boss-Skald-Bard" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Skald-Bard"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Snow-trollAlpha.png" set="anBosses" image="AN-Boss-Snow-trollAlpha" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Snow-trollAlpha"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Thegn-on-Frostfang.png" set="anBosses" image="AN-Boss-Thegn-on-Frostfang" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Thegn-on-Frostfang"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/anBosses/AN-Boss-Thegn.png" set="anBosses" image="AN-Boss-Thegn" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="AN-Boss-Thegn"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="faHeroes" style="visibility: hidden;">
<image src="assets/tiles/faHeroes/FA-starting-position-hero.png" set="faHeroes" image="FA-starting-position-hero" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="FA-starting-position-hero"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-Abyssal-Gargoyls.png" set="faHeroes" image="FA-hero-Abyssal-Gargoyls" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-Abyssal-Gargoyls"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-Abyssal-guardian-01.png" set="faHeroes" image="FA-hero-Abyssal-guardian-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-Abyssal-guardian-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-Abyssal-guardian-02.png" set="faHeroes" image="FA-hero-Abyssal-guardian-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-Abyssal-guardian-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-ChamToken-Abyssal-01.png" set="faHeroes" image="FA-hero-ChamToken-Abyssal-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-ChamToken-Abyssal-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-ChamToken-Abyssal-02.png" set="faHeroes" image="FA-hero-ChamToken-Abyssal-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-ChamToken-Abyssal-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-flamme-carrier.png" set="faHeroes" image="FA-hero-flamme-carrier" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-flamme-carrier"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-Watchmen-Succubs01.png" set="faHeroes" image="FA-hero-Watchmen-Succubs01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-Watchmen-Succubs01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faHeroes/FA-hero-Watchmen-Succubs02.png" set="faHeroes" image="FA-hero-Watchmen-Succubs02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-hero-Watchmen-Succubs02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>


                    <div class="sidenav" id="faMinions" style="visibility: hidden;">
    <image src="assets/tiles/faMinions/FA-Minion-Abyssal-Gargoyls.png" set="faMinions" image="FA-Minion-Abyssal-Gargoyls" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Abyssal-Gargoyls_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Abyssal-guardian-01.png" set="faMinions" image="FA-Minion-Abyssal-guardian-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Abyssal-guardian-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Abyssal-guardian-02.png" set="faMinions" image="FA-Minion-Abyssal-guardian-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Abyssal-guardian-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-ChamToken-Abyssal-01.png" set="faMinions" image="FA-Minion-ChamToken-Abyssal-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-ChamToken-Abyssal-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-ChamToken-Abyssal-02.png" set="faMinions" image="FA-Minion-ChamToken-Abyssal-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-ChamToken-Abyssal-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Efrit-01.png" set="faMinions" image="FA-Minion-Efrit-01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Efrit-01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Efrit-02.png" set="faMinions" image="FA-Minion-Efrit-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Efrit-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-flamme-carrier.png" set="faMinions" image="FA-Minion-flamme-carrier" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-flamme-carrier_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Hennequin-Blood-mask.png" set="faMinions" image="FA-Minion-Hennequin-Blood-mask" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Hennequin-Blood-mask_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Hennequin.png" set="faMinions" image="FA-Minion-Hennequin" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Hennequin_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Infernal-Dog-02.png" set="faMinions" image="FA-Minion-Infernal-Dog-02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Infernal-Dog-02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Watchmen-Succubs01.png" set="faMinions" image="FA-Minion-Watchmen-Succubs01" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Watchmen-Succubs01_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/faMinions/FA-Minion-Watchmen-Succubs02.png" set="faMinions" image="FA-Minion-Watchmen-Succubs02" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Minion-Watchmen-Succubs02_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="faBosses" style="visibility: hidden;">
<image src="assets/tiles/faBosses/FA-starting-position-boss.png" set="faBosses" image="FA-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="FA-starting-position-boss"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss--Abyssal-guardian-01.png" set="faBosses" image="FA-Boss--Abyssal-guardian-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss--Abyssal-guardian-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss--Abyssal-guardian-02.png" set="faBosses" image="FA-Boss--Abyssal-guardian-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss--Abyssal-guardian-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-abyssal-Archdemon.png" set="faBosses" image="FA-Boss-abyssal-Archdemon" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-abyssal-Archdemon"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Abyssal-Chroneas.png" set="faBosses" image="FA-Boss-Abyssal-Chroneas" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Abyssal-Chroneas"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Abyssal-dread.png" set="faBosses" image="FA-Boss-Abyssal-dread" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Abyssal-dread"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Abyssal-Gargoyls.png" set="faBosses" image="FA-Boss-Abyssal-Gargoyls" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Abyssal-Gargoyls"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-abyssal-temptress.png" set="faBosses" image="FA-Boss-abyssal-temptress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-abyssal-temptress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-ChamToken-Abyssal-01.png" set="faBosses" image="FA-Boss-ChamToken-Abyssal-01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-ChamToken-Abyssal-01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-ChamToken-Abyssal-02.png" set="faBosses" image="FA-Boss-ChamToken-Abyssal-02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-ChamToken-Abyssal-02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Drech-nok.png" set="faBosses" image="FA-Boss-Drech-nok" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Drech-nok"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-flamme-carrier.png" set="faBosses" image="FA-Boss-flamme-carrier" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-flamme-carrier"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Hennequin-Masque-de-Sang.png" set="faBosses" image="FA-Boss-Hennequin-Masque-de-Sang" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Hennequin-Masque-de-Sang"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Hennequin.png" set="faBosses" image="FA-Boss-Hennequin" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Hennequin"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Mau-ti-bu-su-Abyssal-temptress.png" set="faBosses" image="FA-Boss-Mau-ti-bu-su-Abyssal-temptress" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Mau-ti-bu-su-Abyssal-temptress"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Roue-des-Ames.png" set="faBosses" image="FA-Boss-Roue-des-Ames" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Roue-des-Ames"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-sorcerer-Abyssal.png" set="faBosses" image="FA-Boss-sorcerer-Abyssal" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-sorcerer-Abyssal"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Watchmen-Succubs01.png" set="faBosses" image="FA-Boss-Watchmen-Succubs01" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Watchmen-Succubs01"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
<image src="assets/tiles/faBosses/FA-Boss-Watchmen-Succubs02.png" set="faBosses" image="FA-Boss-Watchmen-Succubs02" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="FA-Boss-Watchmen-Succubs02"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="rtMinions" style="visibility: hidden;">
    <image src="assets/tiles/rtMinions/RT-Minion-Starting-position-Trident-realm.png" set="rtMinions" image="RT-Minion-Starting-position-Trident-realm" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="RT-Minion-Starting-position-Trident-realm_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion--baby-giga.png" set="rtMinions" image="RT-Minion--baby-giga" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion--baby-giga_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-Giga.png" set="rtMinions" image="RT-Minion-Giga" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-Giga_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-Harpoon-naiad.png" set="rtMinions" image="RT-Minion-Harpoon-naiad" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-Harpoon-naiad_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-naïad-on-serpent.png" set="rtMinions" image="RT-Minion-naïad-on-serpent" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-naïad-on-serpent_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-placoderm.png" set="rtMinions" image="RT-Minion-placoderm" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-placoderm_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-river-guardian.png" set="rtMinions" image="RT-Minion-river-guardian" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-river-guardian_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-Thuul.png" set="rtMinions" image="RT-Minion-Thuul" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-Thuul_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-warrior-naïad.png" set="rtMinions" image="RT-Minion-warrior-naïad" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-warrior-naïad_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtMinions/RT-Minion-Water-elemental.png" set="rtMinions" image="RT-Minion-Water-elemental" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Minion-Water-elemental_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="rtBosses" style="visibility: hidden;">
    <image src="assets/tiles/rtBosses/RT-starting-position-boss.png" set="rtBosses" image="RT-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="RT-starting-position-boss_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtBosses/RT-Boss--Naiad-standard-bearer-on-serpent.png" set="rtBosses" image="RT-Boss--Naiad-standard-bearer-on-serpent" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Boss--Naiad-standard-bearer-on-serpent_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtBosses/RT-Boss-Kraken.png" set="rtBosses" image="RT-Boss-Kraken" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Boss-Kraken_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtBosses/RT-Boss-Major-water-elemental.png" set="rtBosses" image="RT-Boss-Major-water-elemental" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Boss-Major-water-elemental_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/rtBosses/RT-Boss-River-guardian-standard-bearer.png" set="rtBosses" image="RT-Boss-River-guardian-standard-bearer" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="RT-Boss-River-guardian-standard-bearer_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="saBosses" style="visibility: hidden;">
    <image src="assets/tiles/saBosses/SA-starting-position-boss.png" set="saBosses" image="SA-starting-position-boss" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SA-starting-position-boss"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saBosses/SA-Boss-Fire-elemental-majeur.png" set="saBosses" image="SA-Boss-Fire-elemental-majeur" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Boss-Fire-elemental-majeur"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saBosses/SA-Boss-Hrrath-legendary.png" set="saBosses" image="SA-Boss-Hrrath-legendary" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Boss-Hrrath-legendary"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saBosses/SA-Boss-mercenary-salamander.png" set="saBosses" image="SA-Boss-mercenary-salamander" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Boss-mercenary-salamander"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saBosses/SA-Boss-Salamander-Necromancer.png" set="saBosses" image="SA-Boss-Salamander-Necromancer" flippable="no" orientation="0" onMapZIndex="2" single="yes"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Boss-Salamander-Necromancer"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>

                    <div class="sidenav" id="saMinions" style="visibility: hidden;">
    <image src="assets/tiles/saMinions/SA-Minion-starting-position-Salamander.png" set="saMinions" image="SA-Minion-starting-position-Salamander" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="yes" oncontextmenu="return false;" id="SA-Minion-starting-position-Salamander_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saMinions/SA-Minion-Fire-elemental.png" set="saMinions" image="SA-Minion-Fire-elemental" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Minion-Fire-elemental_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saMinions/SA-Minion-Ghekkotah.png" set="saMinions" image="SA-Minion-Ghekkotah" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Minion-Ghekkotah_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saMinions/SA-Minion-Great-Axe.png" set="saMinions" image="SA-Minion-Great-Axe" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Minion-Great-Axe_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/saMinions/SA-Minion-warrior-salamander.png" set="saMinions" image="SA-Minion-warrior-salamander" flippable="no" orientation="0" onMapZIndex="2" single="no"pieceType="tile" snap="no" oncontextmenu="return false;" id="SA-Minion-warrior-salamander_1"style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
                    </div>








                    <div class="sidenav" id="textBoxes" style="visibility: hidden;">
                        <div class="card" set="textBoxes" orientation="0" oncontextmenu="return false;" single="no" pieceType="text"
                            onMap="no" id="textConfig_1" style="position: absolute; cursor: move; z-index: -1;">
                            <div class="card-header" single="no" pieceType="text" draggable_trigger="true">
                                <button class="old_button text_box_button" single="no" pieceType="text" draggable_trigger="false"
                                    onclick="incFont(this.parentNode.parentNode.childNodes[3].childNodes[1])">+</button>
                                <button class="old_button text_box_button" single="no" pieceType="text" draggable_trigger="false"
                                    onclick="decFont(this.parentNode.parentNode.childNodes[3].childNodes[1])">-</button>
                            </div>

                            <div class="card-body funny_scrollbar_blue" single="no" pieceType="text">
                                <textarea class="text_area_on_card" ontouchend="endResize()" ontouchmove="resizeTextArea(event, this)" id="text_1" style="font-size: 22px; font-weight: bold;" single="no" pieceType="text"
                                    draggable_trigger="false">Special rules</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="sidenav" id="qrnMarkers" style="visibility: hidden;">
    <image src="assets/tiles/qrnMarkers/Point-A.png" set="qrnMarkers" image="Point-A" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-A_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-B.png" set="qrnMarkers" image="Point-B" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-B_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-C.png" set="qrnMarkers" image="Point-C" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-C_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-D.png" set="qrnMarkers" image="Point-D" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-D_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD-2.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD-2" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD-2_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD-3.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD-3" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD-3_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-de-Sortie-hero-HD.png" set="qrnMarkers" image="Point-de-Sortie-hero-HD" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-de-Sortie-hero-HD_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-E.png" set="qrnMarkers" image="Point-E" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-E_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Point-F.png" set="qrnMarkers" image="Point-F" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Point-F_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/QRN-marker-colapse.png" set="qrnMarkers" image="QRN-marker-colapse" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="QRN-marker-colapse_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/QRN-marker-rift.png" set="qrnMarkers" image="QRN-marker-rift" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="QRN-marker-rift_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-divine-magic.png" set="qrnMarkers" image="Token-divine-magic" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-divine-magic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Aeromancie.png" set="qrnMarkers" image="Token-magic-Aeromancie" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Aeromancie_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Bard.png" set="qrnMarkers" image="Token-magic-Bard" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Bard_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-common.png" set="qrnMarkers" image="Token-magic-common" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-common_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Druidic.png" set="qrnMarkers" image="Token-magic-Druidic" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Druidic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Geomancy.png" set="qrnMarkers" image="Token-magic-Geomancy" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Geomancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Hydromancy.png" set="qrnMarkers" image="Token-magic-Hydromancy" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Hydromancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Necromancy.png" set="qrnMarkers" image="Token-magic-Necromancy" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Necromancy_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-Pyromancie.png" set="qrnMarkers" image="Token-magic-Pyromancie" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-Pyromancie_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-magic-sorcery.png" set="qrnMarkers" image="Token-magic-sorcery" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-magic-sorcery_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
    <image src="assets/tiles/qrnMarkers/Token-Major-magic.png" set="qrnMarkers" image="Token-Major-magic" orientation="0" single="no" pieceType="tile" snap="yes" onMapZIndex="2" flippable="no" oncontextmenu="return false;" id="Token-Major-magic_1" style="position: absolute; cursor: move; width: 100%;" onMap="no"></image>
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
    </div>

</body>

</html>


