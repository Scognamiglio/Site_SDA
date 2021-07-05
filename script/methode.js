$( ".popin" ).dialog({
    autoOpen: false,
    modal: true,
    width: 630,
    height: "auto",
    open: function(event, ui) {
        // Reset Dialog Position
        $(this).dialog('widget').position({ my: "top", at: "top+50", of: window });
    },
});


function openPopin($name) {
    console.log("#"+$name);
    $( "#"+$name).dialog('open');
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