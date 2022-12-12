var fctExec = undefined;
var noSubmitData = false;
const typeEvent = ['change', 'blur','focus'];
$(document).ready(function (){

    $("#popinApp").dialog({
        autoOpen: true,
        minWidth: 600,
        minHeight: 400,
        autoResize:true,
        modal: true,
        buttons: [
            {
                class: 'leftButton backButton',
                text: 'retour',
                click: function () { push("back")}
            },
            {
                class: 'upButton',
                text: 'suivant',
                click: function () { push("up")}
            }
        ]
    });

    push("up");

})

function push(act){
    app = $('#app').val();
    var data = {};
    $('#descr').find('input,select,textarea').each(function () {
        data[$(this).attr('name')] = $(this).val();
    })
    if(act == "up"){
        data['act'] = 'up';
    }else if(act == "back"){
        data['act'] = 'back';
        data['step'] = data['step'] - 2;
        data['noSubmit'] = '1';
    }else if(act == "same"){
        data['act'] = 'same';
        data['step'] = data['step'] - 1;
    }
    if(noSubmitData){
        data['noSubmit'] = '1';
    }
    $.ajax({
        url : 'index.php?page=app&app='+app, // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP.
        data : data
    }).done(function (a){
        res = JSON.parse(htmlDecode(a));
        if(res.status == 'success'){
            treatProcess(res.response);
        }else{
            treatError(res.response);
        }
    })
}

function treatError(res){
    msgShow = Object.values(res.msg).join("<br>")
    $('.withError').removeClass('withError')
    $('#help').fadeIn().html(msgShow).addClass('alert alert-danger')
    for(var i in res.field){
        $(`*[name="${res.field[i]}"]`).addClass('withError');
    }
    if(fctExec != undefined){
        fctExec();
    }
    fctExec = undefined;
}
function treatProcess(res){
    $('#help').fadeOut()
    $('.leftButton,.upButton').prop('disabled', false).removeClass('ui-state-disabled');
    if(res.hasOwnProperty('notBack')){$('.leftButton').prop('disabled', true).addClass('ui-state-disabled');}
    if(res.hasOwnProperty('notNext')){$('.upButton').prop('disabled', true).addClass('ui-state-disabled');}
    noSubmitData = res.hasOwnProperty('noSubmit')
    console.log(noSubmitData)

    if(res.hasOwnProperty('data')){
        data = res.data
        contener = $("<div id='descr'></div>")

        for(var key in data) {
            if(key == 'step'){
                contener.append(`<input style='display:none' name='step' value="${data.step}">`)
            }
            if(key == 'label'){
                contener.append(`<p>${data.label}</p>`)
            }
            if(key == 'alert'){
                contener.append(`<p class="alert ${data.alert.class}">${data.alert.label}</p>`)
            }
            if(key == 'inputs'){
                multiFields(createInput,data.inputs,contener)
            }
            if(key == 'selects'){
                multiFields(createSelect,data.selects,contener);
            }
            if(key == 'textAreas'){
                multiFields(createTextArea,data.textAreas,contener);
                contener.find('textarea[maxlength]').keyup(countCharTextArea);
            }
        }
    }

    $('#descr').replaceWith(contener);

    if(res.hasOwnProperty('up')){
        if(res.up == "redirect"){
            $('#descr').find('select').change(redirect)
            $('input[name="step"]').remove();
        }
    }

}


// Fonction utiliser dans le treatProcess

function countCharTextArea(e){
    $('small[for="'+$(this).attr('name')+'"').html(`${$(this).val().length} / ${$(this).attr('maxlength')}`)
}

function showHelp(e){
    dataHelp = $(this).attr('data-help') ?? $(this).find(`*[value="${$(this).val()}"]`).attr('data-help');

    if(dataHelp != undefined){
        $('#help').fadeIn().html(dataHelp);

    }
}

function showHelpCible(e){
    dataHelp = $(this).attr('data-help') ?? $(this).find(`*[value="${$(this).val()}"]`).attr('data-help');
    if(dataHelp != undefined){
        name = $(this).attr('name');
        $(`#help-${name}`).fadeIn().html(dataHelp);
    }
}

function removeHelpCible(e){
    name = $(this).attr('name');
    $(`#help-${name}`).fadeOut();
}

// Factorie
function createSelect(dataDOM){
    p = $("<p></p>");
    if(dataDOM.hasOwnProperty('label')){
        label = $(`<label for="${dataDOM.name}">${dataDOM.label}</label>`)
        p.append(label)
    }
    select = $(`<select class="form-select" name="${dataDOM.name}"></select>`)
    select.focus(function () {
        $(this).attr('data-oldValue',$(this).val());
    })
    option = $('<option style="display: none">Choisis</option>')
    select.append(option);
    help = false;
    for (var value in dataDOM.options) {
        param = dataDOM.options[value];
        option = $(`<option value="${value}">${param.label ?? value}</option>`);
        if(param.hasOwnProperty("help")){
            help = true;
            option.attr('data-help',param.help)
            option.attr('title',param.help)
        }
        select.append(option);
    }

    select.change(showHelpCible)
    select.focus(showHelpCible)
    select.blur(removeHelpCible)
    if(dataDOM.hasOwnProperty('value')){
        select.val(dataDOM.value);
    }
    if(dataDOM.hasOwnProperty('disabled')){
        select.prop('disabled', true)
    }
    p.append(select)
    if(help){
        divForHelp = $(`<div class="alert alert-primary" style="display: none; margin-top: 1em" id="help-${dataDOM.name}" role="alert"></div>`)
        p.append(divForHelp)
    }
    return p
}

function createInput(dataDOM){
    p = $("<p></p>");
    if(dataDOM.hasOwnProperty('label')){
        label = $(`<label for="${dataDOM.name}">${dataDOM.label}</label>`)
        p.append(label)
    }
    input = $(`<input class='form-control' name="${dataDOM.name}"/>`);
    if(dataDOM.hasOwnProperty('placeHolder')){
        input.attr('placeholder',dataDOM.placeHolder);
    }
    if(dataDOM.hasOwnProperty('type')){
        input.attr('type',dataDOM.type);
    }
    if(dataDOM.hasOwnProperty('value')){
        input.val(dataDOM.value);
    }
    p.append(input)
    return p;
}

function createTextArea(dataDOM){
    help = false
    p = $("<p></p>");
    if(dataDOM.hasOwnProperty('label')){
        label = $(`<label for="${dataDOM.name}">${dataDOM.label}</label>`)
        p.append(label)
    }
    textArea = $(`<textarea class="form-control" name="${dataDOM.name}" rows="5"></textarea>`);
    textArea.attr('maxlength',dataDOM.max)
    size = 0
    if(dataDOM.hasOwnProperty('value')){
        textArea.val(dataDOM.value);
        size = dataDOM.value.length;
    }
    if(dataDOM.hasOwnProperty('placeHolder')){
        textArea.attr('placeholder',dataDOM.placeHolder);
    }

    if(dataDOM.hasOwnProperty("help")){
        textArea.attr('data-help',dataDOM.help)
        help = true;
        divForHelp = $(`<div class="alert alert-primary" style="display: none; margin-top: 1em" id="help-${dataDOM.name}" role="alert"></div>`)
    }

    nbrChar = $(`<small class="form-text text-muted" for="${dataDOM.name}">${size} / ${dataDOM.max}</small>`)

    p.append(textArea);
    p.append(nbrChar)
    if(help){
        p.append(divForHelp)
    }
    return p;
}


// Fonction rajotuable Par l'app sur les fields

function redirect(e){
    $('#app').val($(this).val());
}

function redirectStep(){
    var obj = $(this);
    fctExec = function (){
        obj.val(obj.attr('data-oldValue'))
    }
    push('same');
}

function saveDataStep(){
    if($(this).val().length > 50 && $('input[name*="title-story"]').val().length > 0){
        push('same');
    }else{
        showHelpCible()
    }
    console.log($(this).val().length)
}


// Fct obscure (gestion des fonctions dynamique
function multiFields(fct,tab,contener){
    for(var i in tab){
        objDom = fct(tab[i]);

        typeEvent.forEach(element => {
                if (tab[i].hasOwnProperty(element)) {
                    fctUse = getFct(tab[i][element]);
                    objDom.find('select,input,textarea').on(element, fctUse)
                }
            }
        );
        contener.append(objDom);
    }
}

function getFct(fctName){
    if(fctName == 'redirectStep'){
        return redirectStep;
    }
    if(fctName == 'saveDataStep'){
        return saveDataStep;
    }
    if(fctName == 'showHelpCible'){
        return showHelpCible;
    }
}