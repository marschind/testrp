function SelectText(element) {
    var doc = document
        , text = doc.getElementById(element)
        , range, selection
    ;    
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

function updatevalue(subsys, name, value) {
    $.get('/portal/updatevalue.php?s='+subsys+'&n='+name+'&v='+escape(value));
}

function close(id) {
alert("Closing "+id);
    $('#'+id).hide();
}
