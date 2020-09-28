var activeSet = "baseA"
var activeArea = "A";


//define each function for every group load instead of a unique function with different behaviours
//this choice was taken for handling different behaviours with respect to the set choosen.
function loadBaseB() {
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("baseB").style.visibility = ""

    document.getElementById("flipSetButton").onclick = loadBaseA;

    activeSet = "baseB"
}

function loadBaseA() {
    document.getElementById(activeSet).style.visibility = "hidden"
    document.getElementById("baseA").style.visibility = ""

    document.getElementById("flipSetButton").onclick = loadBaseB;

    activeSet = "baseA"
}