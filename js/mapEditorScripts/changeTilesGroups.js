var activeSet = "qrnA"
var activeArea = "A";

function triggerInnerMenu(){
    if(document.getElementById('innerMenu').style.visibility == "hidden"){
        document.getElementById("triggerInnerMenuButton").innerHTML = "↑"
        document.getElementById('innerMenu').style.visibility = "";
    }else{
        document.getElementById("triggerInnerMenuButton").innerHTML = "↓"
        document.getElementById('innerMenu').style.visibility = "hidden";
    }
}

function triggerGeneralMenu(){
    if(document.getElementById('generalMenu').style.visibility == "hidden"){
        document.getElementById("triggerGeneralMenuButton").innerHTML = "↓"
        document.getElementById('generalMenu').style.visibility = "";
    }else{
        document.getElementById("triggerGeneralMenuButton").innerHTML = "↑"
        document.getElementById('generalMenu').style.visibility = "hidden";
    }
}

//define each function for every group load instead of a unique function with different behaviours
//this choice was taken for handling different behaviours with respect to the set choosen.
//this code is reverted back from the structure that was using JSON logic...now is a bit mess

function setDefault(){
    document.getElementById("loadQrnHeroes").style.visibility = "";
    document.getElementById("loadQrnA").style.visibility = "";
    document.getElementById("loadQrnB").style.visibility = "";

    document.getElementById("loadQrnA").style.opacity = "0.9";
    document.getElementById("loadQrnB").style.opacity = "0.9";
    document.getElementById("loadQrnHeroes").style.opacity = "0.9";
    document.getElementById("loadQrnMinions").style.opacity = "0.9";
    document.getElementById("loadQrnFurnitures").style.opacity = "0.9";
    document.getElementById("loadQrnLocks").style.opacity = "0.9";
    document.getElementById("loadQrnTraps").style.opacity = "0.9";
}

function loadQrnB() {
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnB").style.visibility = ""
    setDefault();
    document.getElementById("loadQrnB").style.opacity = "0.5";
    //document.getElementById("flipSetButton").onclick = loadQrnA;
    activeSet = "qrnB"
}

function loadQrnA() {
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnA").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnA").style.opacity = "0.5";
    //document.getElementById("flipSetButton").onclick = loadQrnB;
    activeSet = "qrnA"
}

function loadQrnHeroes(){
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnHeroes").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnHeroes").style.opacity = "0.5";
    activeSet = "qrnHeroes"
}

function loadQrnMinions(){
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnMinions").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnMinions").style.opacity = "0.5";
    activeSet = "qrnMinions"
}

function loadQrnFurnitures(){
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnFurnitures").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnFurnitures").style.opacity = "0.5";
    activeSet = "qrnFurnitures"
}

function loadQrnLocks(){
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnLocks").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnLocks").style.opacity = "0.5";
    activeSet = "qrnLocks"
}

function loadQrnTraps(){
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("qrnTraps").style.visibility = ""

    setDefault();
    document.getElementById("loadQrnTraps").style.opacity = "0.5";
    activeSet = "qrnTraps"
}