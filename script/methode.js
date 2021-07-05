function openPopin($name) {
    console.log("#"+$name);
    $( "#"+$name).dialog({
        width: 630,
        position: { my: 'top', at: 'top+50' },
        modal: true,
        resizable: false,
        closeOnEscape: false
    });
}


function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}



function setCookie(name, value, days) {
    value = value.replaceAll("\n","<br>");
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);
    document.cookie = name + "=" + value + ";path=/SDA;expires=" + d.toGMTString();
}

function htmlDecode(input){
    var e = document.createElement('textarea');
    e.innerHTML = input;
    // handle case of empty input
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}