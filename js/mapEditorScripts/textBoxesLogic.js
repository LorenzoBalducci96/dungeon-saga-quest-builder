function incFont(textArea){
    let actualSize = parseFloat(textArea.style.fontSize);
    if(actualSize < 48){
        textArea.style.fontSize = (actualSize + 4) + "px";
    }
}


function decFont(textArea){
    let actualSize = parseFloat(textArea.style.fontSize);
    if(actualSize > 10){
        textArea.style.fontSize = (actualSize - 4) + "px";
    }
}