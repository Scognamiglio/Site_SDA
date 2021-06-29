var t

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
        $( "#popin_info" ).dialog();
    });
});


$(".step-2").fadeOut(0);
$(".step-3").fadeOut(0);
$(".step-4").fadeOut(0);
$(".step-5").fadeOut(0);



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
        if(json[0] == "erreur"){
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
    });
}

$('input,select,textarea').change(function (){changeValue($(this))});

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
        }
    }
}

$('#newChap').click(() => {
    addChapStory();
});

function addChapStory(listerner = true){
    i = $('#TheSpanForStory > input[name^="title-story-').length
    if(i<10){
        $('#TheSpanForStory').find('div[id^="show-story-"]').css("display","none")
        html = $('#tpl_story').html().replaceAll("story-x","story-"+i);
        $('#TheSpanForStory').append(html)

        if(listerner){
            $('strong[name="show-story-'+i+'"]').click(function (){spoiler($(this))})
            $('textarea[name="text-story-'+i+'"]').change(function () {changeValue($(this))})
        }

    }else{
        $('#newChap').html("Limite de chapitre atteinte.").addClass('errorButton');
    }
}


$('#deleteChap').click(() => {
    deleteChapStory();
});

function deleteChapStory(){
    i = $('#TheSpanForStory > input[name^="title-story-').length
    if(i>0){
        $('input[name="title-story-'+(i-1)+'"]').nextAll().remove()
        $('input[name="title-story-'+(i-1)+'"]').remove();

        // Ajouté un check
        $('#newChap').html("Nouveau chapitre").removeClass('errorButton');
    }
}


// Créer un premier bloc story
addChapStory(false);