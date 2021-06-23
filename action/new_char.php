<?php

$selectAndOption = [
    'genre' => [
        'Homme' => 'Homme',
        'Femme' => 'Femme',
        'Autre' => 'Autre'
    ],
    'vPhysique' => [
        'Tranchante' => 'Peu d&#39;attaque de groupe, consommation légère, bon dégâts.&#10;Ex : épée, dague, faux et etc...',
        'Contondante' => '',
        'Projectile' => '',
        'Longue' => ''
    ],
    'vMagique' => [
        'Eau' => '',
        'Feu'=> '',
        'Terre'=> '',
        'Vent'=> '',
        'Foudre'=> '',
        'Ténèbres'=> '',
        'Lumière'=> ''
    ],
];

function genereSelect($type){
    global $selectAndOption;
    $ret = "";
    if(!empty($selectAndOption[$type])){
        $ret = "<select class='TheSelect' name='$type'>";
        foreach ($selectAndOption[$type] as $option=>$title){
            $ret .= "<option value='$option' ".(!empty($title) ? "title='$title'" : "").">$option</option>";
        }
        $ret .= "</select>";
    }

    return $ret;

}

if(isset($_POST['act']) && $_POST['act']=='getTitle'){
    if(isset($selectAndOption[$_POST['id']][$_POST['value']]))
    retour([isset($selectAndOption[$_POST['id']][$_POST['value']]) ? $selectAndOption[$_POST['id']][$_POST['value']] : '']);

}