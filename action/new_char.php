<?php

// Passer sa en base de données ?
$selectAndOption = [
    'genre' => [
        'Homme' => 'Homme',
        'Femme' => 'Femme',
        'Autre' => 'Autre'
    ]
];

$sql = "select label,value from botExtra where label in ('vPhysique','vMagique','race')";
foreach ($bdd->query($sql)->fetchAll() as $json){
    $selectAndOption[$json['label']] = json_decode($json['value'],true);
}


function genereSelect($type){
    global $selectAndOption;
    $ret = "";
    $rep = function ($a){return str_replace(["'","<br>"],['&#39;','&#10;'],$a);};
    if(!empty($selectAndOption[$type])){
        $ret = "<select class='TheSelect' name='$type'><option value=''>Choisis</option>";
        foreach ($selectAndOption[$type] as $option=>$title){
            $ret .= "<option value='$option' title='".$rep($title)."'>$option</option>";
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
