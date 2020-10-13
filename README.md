# dungeon-saga-quest-builder
A web based editor for creating custom missions for dungeon saga board game

This is the repository for the open-source web application hosted here:
https://www.victoriashare.eu/dungeon-saga-quest-builder/

Users can create their own missions, registered users are allowed to upload the missions for the community of dungeon saga.
(An email with the PDF will be sent to the administrator)

The application allow to create a new mission or reload a backup project (not yet implemented)
![Main menu demo](/repo_doc/main_menu.png?raw=true "MAIN_MENU")

Is possible to edit all the text-areas, the buttons to the left allow to 
1) switch between the first and the second page of the mission.
2) adjust the zoom level in order to fit the sheet in the screen of to ajust the zoom for the user preferences.
3) change dimensions of the map in the second sheet.
4) The "FINISH" button allow you to conclude the project and export a PDF or download the backup of the project
5) return to menu
![first page demo](/repo_doc/first_page.png?raw=true "FIRST_PAGE")


In the second page is possible to click on the map to enter the map editing mode
![second page demo](/repo_doc/second_page.png?raw=true "SECOND_PAGE")

In the map editing mode all the pieces are arranged in the left panel and a drop down list (1) allow you to change pieces groups.
Simply drag a piece from the left to the right for putting it into the map.
For rotating a tile or any other element on the map simply right click with the mouse or double tap on mobile devices.
Is possible to select multiple pieces just by dragging with the mouse for a multiple selection.
Different pieces have different behaviours, see below in the html attributes explaination.
Is possible to asjust the zoom of the map with the + and - top buttons.
When the editing of the map is finished just press return to editor; the edited map will appear in the second sheet.
Use the map zoom buttons in the editor for adjust the map dimension.  
![map editor demo](/repo_doc/the_map_editor.png?raw=true "MAP_EDITOR_PAGE")

