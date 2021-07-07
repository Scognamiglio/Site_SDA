var t

debug = false;

var forceValue = {};


//<Action>//
if($('#TheSpanForStory > input[name^="title-story-').length == 0){
    addChapStory(false);
}else{
    t = 0;$('#TheSpanForStory').find('textarea').each(function(){t +=$(this).val().length})
    $('#total').html( "Total : "+t+" caractères"+((t < 2000) ? ' (Minimum 2000)' : ''))
}

CheckGoodStep();
//</Action>//

//<Listener>//
$('input,select,textarea').change(function (){changeValue($(this))});
$('#newChap').click(() => {addChapStory();});
$('#deleteChap').click(() => {deleteChapStory();});
$('textarea').keyup(function (){nmbCaraArea($(this))})
$('#save').click(() => {saveData()});

$( "#FormCreate" ).submit(function( event ) {
    saveData(false)
    event.preventDefault();
    retour = checkGo();
    if(retour.length == 0){
        retourText = "Bien joué à toi ! Un administrateur reviendra vers toi d'ici peu pour valider ta fiche !"
        $.ajax({
            url : 'index.php?page=new_char', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP.
            data : {
                act: 'valid',
            }
        })
    }else{
        retourText = "Certain élément sont à corrigé :<br>- "
        retourText += retour.join('<br>- ')
        retourText += "<br><br> Tes modifications ayant était enregistrer plus tard, tu peux reprendre plus tard ou demander de l'aide à un MJ"
    }
    $('#popinInfoChange').html(retourText);
    $( "#popin_info" ).dialog('open')
});

$(window).on("beforeunload", function() {
    saveData(false)
})


//</Listener>//

$(".info").click(function() {
    id = $(this).attr('id');
    value = $('select[name="'+id+'"]').val()

    $.ajax({
        url : 'index.php?page=new_char', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : {
            act: 'getTitle',
            id: id,
            value:value
        }
    }).done(function ($r) {
        json = JSON.parse($r)
        $('#popinInfoChange').html(htmlDecode(json[0]));
        $( "#popin_info" ).dialog('open');
    });
});


function saveData(alerte = true){
    var arrayAll = {}
    $('#FormCreate').find('input,select,textarea').each(function(){if($(this).val() != ""){arrayAll[$(this).attr('name')] = $(this).val()}})


    $.ajax({
        url : 'index.php?page=new_char', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : {
            act: 'saveData',
            data: arrayAll
        }
    }).done(function ($r) {
        json = JSON.parse($r)
        if(json[0]=="success" && alerte){
            alert(json[1]);
        }
    });

}

function changeValue(cible){

    tabV = {
        vPhysique : 'vMagique',
        vMagique : 'vPhysique'
    }
    t = cible
    console.log(t);
    name = t.attr('name');

    data = {
        act: 'setData',
        label: name,
        value: cible.val()
    }

    if(tabV.hasOwnProperty(name) && $('select[name="'+tabV[name]+'"]').val() != ''){
        data["otherVoie"] = $('select[name="'+tabV[name]+'"]').val()
    }

    $.ajax({
        url : 'index.php?page=new_char', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : data
    }).done(function ($r) {
        json = JSON.parse($r)
        name = t.attr('name');

        if(json[0] == "erreur" || forceValue.hasOwnProperty(name) && forceValue[name] == false){
            t.addClass('error');
        }else{
            t.removeClass('error');
            if(typeof json[1]==="object"){
                tab = Object.values(json[1]).map(x => htmlDecode(x))
                $('select[name="race"] > option').each(function(i){if(tab.indexOf($(this).val().toLowerCase())!=-1){$(this).removeClass('not')}else{$(this).addClass('not')}})
                $('select[name="race"]').val("");
            }

            checkActiveStep(t);
        }

        val = t.val();
        regex = new RegExp('(don|caractere|objectif|text-story)');
        CheckCookie = regex.test(name);
        console.log("CheckCookie");

        if(val!="" && !CheckCookie){
            setCookie("data:"+name,val+"[dateCookie]"+Math.round(+new Date() / 1000),15);
        }
    });
}

function checkActiveStep($t){
    $step = $t.closest('*[class^="step-"]').attr('class')

    $nextStep="step-"+(parseInt($step[$step.length-1],10)+1)

    if($('.'+$nextStep+'[style*="display: none"]').length > 0){
        test = true;
        $('.'+$step).find('input,select,textarea').each(function( index ) {
            if($(this).val()==""){
                test=false;
            }
        });
        if(test){
            $('.'+$nextStep).fadeIn(0);
            stepI = (parseInt($step[$step.length-1],10)+1);
            $('#showStep > td:nth-child('+stepI+')').addClass('now')
            $('#showStep > td:nth-child('+stepI+')').prevAll().addClass('good')
        }
    }else{
        $('#showStep > td').addClass('good')
    }
}





function addChapStory(listerner = true){
    i = $('#TheSpanForStory > input[name^="title-story-').length
    if(i<10){
        $('#TheSpanForStory').find('div[id^="show-story-"]').css("display","none")
        html = $('#tpl_story').html().replaceAll("story-x","story-"+i);

        $('#TheSpanForStory').append(html)

        if(listerner){
            $('strong[name="show-story-'+i+'"]').click(function (){spoiler($(this))})
            $('textarea[name="text-story-'+i+'"]').change(function () {changeValue($(this))})
            $('input[name="title-story-'+i+'"]').change(function () {changeValue($(this))})
            $('textarea[name="text-story-'+i+'"]').keyup(function (){nmbCaraArea($(this))})
        }

    }else{
        $('#newChap').html("Limite de chapitre atteinte.").addClass('errorButton');
    }
}


function deleteChapStory(){
    i = $('#TheSpanForStory > input[name^="title-story-').length
    if(i>1){
        $('input[name="title-story-'+(i-1)+'"]').nextAll().remove()
        $('input[name="title-story-'+(i-1)+'"]').remove();

        // Ajouté un check
        $('#newChap').html("Nouveau chapitre").removeClass('errorButton');

        t = 0;$('#TheSpanForStory').find('textarea').each(function(){t +=$(this).val().length})
        $('#total').html( "Total : "+t+" caractères"+((t < 2000) ? ' (Minimum 2000)' : ''))

        $.ajax({
            url : 'index.php?page=new_char', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP.
            data : {
                act: 'deleteChap',
                id: (i-1)
            }
        })
    }
}



function nmbCaraArea($t){
        id = $t.attr('id');
        maxlength = $t.attr('maxlength');
        minlength = $t.attr('minlength');
        name = $t.attr('name');
        length = $t.val().length;
        ret = "";
        forceValue[name] = true;
        if(length < minlength){
            forceValue[name] = false;
            ret = "Il manque encore "+(minlength-length)+" caractères";
        }else if(length > maxlength){
            forceValue[name] = false;
            ret = "Il y'a "+(length-maxlength)+" caractères en trop";
        }else if(length > maxlength-200){
            ret = "Encore "+(maxlength-length)+" caractères possible";
        }
        idSpan = id.split('_')[0]+'_c'
        if(ret==""){
            $('#'+idSpan).addClass('not');
        }else{
            $('#'+idSpan).html(ret);
            $('#'+idSpan).removeClass('not');
        }


        if(name.startsWith("text-story")){
            t = 0;$('#TheSpanForStory').find('textarea').each(function(){t +=$(this).val().length})
            $('#total').html( "Total : "+t+" caractères"+((t < 2000) ? ' (Minimum 2000)' : ''))
        }

}


function CheckGoodStep(){
    var step = "";

    goodStep = ""
    $($('#FormCreate').find('input,select,textarea').get().reverse()).each(function(){if($(this).val()!=""){step=$(this).closest('*[class^="step-"]').attr('class');return false}})
    if(step != ""){
        stepI = parseInt(step[step.length-1],10)
        check = true;$('.'+step).find('input,select,textarea').each(function(){if($(this).val() == ""){check=false;return false}})
        if(check){
            stepI++;
        }
        for (i = 1;i<stepI+1;i++){
            goodStep += ".step-"+i+","
        }

        goodStep = goodStep.substring(0, goodStep.length - 1);
    }else{
        stepI = 1
        goodStep = ".step-1"
    }
    if(!debug){
        $('*[class^="step-"]:not('+goodStep+')').fadeOut(0);
    }

    if(stepI == 10){
        $('#showStep > td').addClass('good')
    }else{
        $('#showStep > td:nth-child('+stepI+')').addClass('now')
        $('#showStep > td:nth-child('+stepI+')').prevAll().addClass('good')
    }
}

function checkGo(){
    var arrayAll = {}
    error = [];
    $('#FormCreate').find('input,select,textarea').each(function(){if($(this).val() != ""){arrayAll[$(this).attr('name')] = $(this).val()}})
    console.log(arrayAll);


    t = 0;$('#TheSpanForStory').find('textarea').each(function(){t +=$(this).val().length})
    if(t < 2000){
        error.push('Il manque encore '+(2000-t)+' lettres pour ton histoire')
    }


    checkTextArea = {
        caractere : 500,
        objectif : 500,
        donDescription : 30,
        donEveil : 30,
        donTranscendance : 30
    }

    for (var key in checkTextArea) {
        t = $('textarea[name="'+key+'"]').val().length
        if(t < checkTextArea[key]){
            error.push('Il manque encore '+(checkTextArea[key]-t)+' lettres pour ton '+key)
        }
    }

    requiered = ['name','age','genre','image','classe','vPhysique','vMagique','race','donName']
    for(i=0;i<requiered.length;i++){
        if(!arrayAll.hasOwnProperty(requiered[i]) || arrayAll[requiered[i]] == ""){
            error.push("Le champ '"+requiered[i]+"' ne dois pas être vide.")
        }
    }
    return error;


}