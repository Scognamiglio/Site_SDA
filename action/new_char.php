<?php

// Passer sa en base de données ?
$selectAndOption = [
    'genre' => [
        'Homme' => 'Homme',
        'Femme' => 'Femme',
        'Autre' => 'Autre'
    ],
    'classe' => [
        'Voleur' => 'Spécialiste des attaques dans l\'ombre et dôté d\'une excelente agilité<br>Atk ++<br>ADR +',
        'Guerrier' => 'Entrainé depuis toujours à être sur le front, ils sont aussi résistant que dangereux au corps à corps<br>PV ++<br>Atk +',
        'Magicien' => 'Capable de contrôler les élements et la magie dans tous ses formes, il ne faut jamais perdre de vue un magicien.<br>Atk ++ <br>PM +',
        'Prêtre' => 'Toujours là pour soigner et soutenir ce qui en ont besoin, il est la clef de la victoire de long combat<br>PM +<br>PV +<br> ADR+'
    ]
];

$sql = "select label,value from botExtra where label in ('vPhysique','vMagique','race')";
foreach ($bdd->query($sql)->fetchAll() as $json){
    $selectAndOption[$json['label']] = json_decode($json['value'],true);
}

$sql = "select label,value from botExtra where label='raceByVoie'";
$raceByVoie = json_decode($bdd->query($sql)->fetch()['value'],true);


## Traitement data en mémoire. ##
// Query //


$finalStep = 1;
$dataUseDefault = [];
foreach ($_COOKIE as $label => $val){
    if(strpos($label,"step") ===0){
        $ls = explode(":",$label);
        $dataUseDefault[$ls[1]] = $val;
    }
}


if(isset($_POST['act'])){
    switch ($_POST['act']){
        case "getTitle":
            retour([isset($selectAndOption[$_POST['id']][$_POST['value']]) ? $selectAndOption[$_POST['id']][$_POST['value']] : '']);
            break;

        case "deleteChap":
            $sql = "delete from ficheData where idPerso ='{$_SESSION['idPerso']}' and label like '%story-{$_POST['id']}'";
            var_dump($sql);
            $bdd->query($sql);
            retour(['success']);
            break;
        case "setData":
            $val = $_POST['value'];
            $label = $_POST['label'];
            if(empty($val))
                retour(['erreur',"la donnée est vide."]);
            if($label=="name"){
                $sql = "select 1 from perso where prenom like '".explode(' ',$val)[0]." %'";
                if($bdd->query($sql)->fetch()){
                    retour(['erreur','Le premier mot de votre prénom est déjà utilisé.']);
                }
            }
            $myRetour = $_POST['value'];
            $myRetour = empty($_POST['otherVoie']) ? $myRetour : selectRace([$myRetour,$_POST['otherVoie']]);
            // Enregistre la données.
            $sql = "insert into ficheData values ('{$_SESSION['idPerso']}','$label','$val',now()) ON DUPLICATE KEY UPDATE VALUE='$val',dateInsert=NOW()";
            $bdd->query($sql);

            retour(['success',$myRetour]);
            break;

    }

}

function selectRace($array){
    global $raceByVoie;
    $ret = $raceByVoie['all'];

    foreach ($array as $v){
        $v = strtolower($v);
        if(!empty($raceByVoie[$v])){
            foreach ($raceByVoie[$v] as $race){
                $race = strtolower($race);
                if(!in_array($race,$ret)){
                    $ret[] = $race;
                }
            }
        }
    }
    return $ret;
}


function SelectFor($type){
    global $selectAndOption;
    if(!empty($selectAndOption[$type])){
        return genereSelect($selectAndOption[$type],$type);
    }else{
        false;
    }

}

function genereSelect($array,$name){
    global $dataUseDefault;
    $rep = function ($a){return str_replace(["'","<br>"],['&#39;','&#10;'],$a);};

    $ret = "<select class='TheSelect' name='$name'><option value='' style='display: none'>Choisis</option>";
    foreach ($array as $option=>$title){
        $ckeck = isset($dataUseDefault[$name]) && $option==$dataUseDefault[$name] ? 'selected' : '';
        $ret .= "<option value='$option' title='".$rep($title)."' $ckeck>$option</option>";
    }
    $ret .= "</select>";


    return $ret;

}