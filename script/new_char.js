
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


var t
$('input,select,textarea').change(function (){

    tabV = {
        vPhysique : 'vMagique',
        vMagique : 'vPhysique'
    }
    t = $(this)
    name = t.attr('name');

    data = {
        act: 'setData',
        label: name,
        value: $(this).val()
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
})

function checkActiveStep($t){
    $step = $t.parent().parent().attr('class')
    if($step == null){
        $step = $t.parent().parent().parent().attr('class')
    }

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