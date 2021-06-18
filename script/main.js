var arrayMemShow = [];


var test; // à utiliser pour tester des variables dans la console.


$('[class^="onglets-"]').click(function () {
    nameOnglets =  $(this).attr('class').split('onglets-')[1].split(" ")[0]
    ongletOn = $(this).attr('name');

    $('.'+nameOnglets+" > *").each(function () {
        $(this).css('display',($(this).attr("id")==ongletOn) ? 'block' : 'none');
    })

})

$('[id^="actif-"]').hover(function() {
    id = $(this).attr('id')
    if(!arrayMemShow[id]==1){
        console.log('#'+$(this).attr('id')+':hover')
        if($('#'+$(this).attr('id')+':hover').length==0){
            $('.'+$(this).attr('id')).fadeOut(100);
        }else{
            $('.'+$(this).attr('id')).fadeIn(100);
        }
    }
});

$('[id^="actif-"]').click(function() {
    id = $(this).attr('id')
    arrayMemShow[id] = (!arrayMemShow.hasOwnProperty(id) || arrayMemShow[id]==0) ? 1 : 0
    console.log(arrayMemShow)
    if(arrayMemShow[id]==1){
        $('.'+$(this).attr('id')).fadeIn(100);
    }else{
        $('.'+$(this).attr('id')).fadeOut(100);
    }


});




$('.spoiler').click(function () {
    $('#'+$(this).attr("name")).fadeToggle();
})









$(".register").click(function() {
    $( "#dialog" ).dialog();
});

$(".login").click(function() {
    $( "#popin_login" ).dialog();
});



$('#dialog > #go').click(function () {
    $.ajax({
        url : 'index.php?page=inscription', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : 'id=' + $('input[name="id"]').val()
    }).done(function ($r) {
        json = JSON.parse($r)
        if(json[0] == "erreur"){
            alert(decodeHtml(json[1]))
        }else if(json[0] == "success"){
            $( "#dialog" ).html('Bien joué !<br> Tu devrais bientôt recevoir ton mot de passe de la part de ton ami le bot ! ' +
                '<br> Dans le cas contraire utilise la commande !register sur le bot. ' +
                '<br>En cas de problème double demande à Zheneos')
        }
    })
})

$('#popin_login > #go').click(function () {


    $.ajax({
        url : 'index.php?page=login', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : {
            login: $('input[name="login"]').val(),
            psw:$('input[name="psw"]').val()
        }
    }).done(function ($r) {
        json = JSON.parse($r)
        if(json[0] == 'erreur'){
            alert(json[1].replace('\\n','\n'));
            test = json[1]
        }else{
            setCookie('myToken',json[1],'5')
            document.location.href=document.location.href;
        }
    })
})




function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}



function setCookie(name, value, days) {
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}