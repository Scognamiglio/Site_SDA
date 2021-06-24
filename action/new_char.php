<?php

// Passer sa en base de données ?
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
        $ret = "<select class='TheSelect' name='$type'><option value=''>Choisis</option>";
        foreach ($selectAndOption[$type] as $option=>$title){
            $ret .= "<option value='$option' ".(!empty($title) ? "title='$title'" : "").">$option</option>";
        }
        $ret .= "</select>";
    }

    return $ret;

}

if(isset($_POST['act'])){
    switch ($_POST['act']){
        case "getTitle":
            retour([isset($selectAndOption[$_POST['id']][$_POST['value']]) ? $selectAndOption[$_POST['id']][$_POST['value']] : '']);
            break;
        case "setData":
            $val = $_POST['value'];
            $label = $_POST['label'];
            if(empty($val))
                retour(['erreur',"la donnée est vide."]);
            if($label=="name"){
                $sql = "select 1 from perso where prenom like '".explode(' ',$val)[0]."%'";
                if($bdd->query($sql)->fetch()){
                    retour(['erreur','Le premier mot de votre prénom est déjà utilisé.']);
                }
            }
            retour(['success',$_POST['value']]);
            break;

    }

}
