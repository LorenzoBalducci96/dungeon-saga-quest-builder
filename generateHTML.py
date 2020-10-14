import numpy as np
import cv2
import matplotlib.pyplot as plt
import os

baseFolder = "assets/tiles/rvA/"
setName = "rvA"
pieceType = "tile"
single = "yes"
snap = "yes"
flippable = "no"
input_path = 'C:/xampp/htdocs/wordpress/dungeon-saga-quest-builder/assets/tiles/rvA'
output_path = 'C:/xampp/htdocs/wordpress/dungeon-saga-quest-builder/assets/tiles/output.txt'
file = open(output_path,"w+") 
for filename in os.listdir(input_path):
        image = filename.split('.')[0]
        idName = image + "_1"
        file.write("<image src=\"" + baseFolder + filename + "\" set=\"" + setName +
        "\" image=\"" + image + "\" flippable=\"" + flippable + "\" orientation=\"0\" single=\"" + single + "\"\
        pieceType=\"" + pieceType + "\" snap=\"" + snap + "\" oncontextmenu=\"return false;\" id=\"" + idName + "\"\
        style=\"position: absolute; cursor: move; width: 100%;\" onMap=\"no\"></image>\n")