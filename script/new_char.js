
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
    $step = $(this).parent().parent().attr('class')
    if($step == null){
        $step = $(this).parent().parent().parent().attr('class')
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
})