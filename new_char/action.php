<?php

if(empty($_SESSION['idPerso'])){
    redirectError();
}

// Passer ça en base de données ?
$selectAndOption = [
    'genre' => [
        'Homme' => 'Homme',
        'Femme' => 'Femme',
        'Autre' => 'Autre'
    ]
];


$fTextArea = function ($t){return str_replace("<br>","\n",$t);};

function selectRace($array)
{
    global $raceByVoie;
    $ret = $raceByVoie['all'];

    foreach ($array as $v) {
        $v = strtolower($v);
        if (!empty($raceByVoie[$v])) {
            foreach ($raceByVoie[$v] as $race) {
                $race = strtolower($race);
                if (!in_array($race, $ret)) {
                    $ret[] = $race;
                }
            }
        }
    }
    return $ret;
}

function checkForCookie($label){
    return !preg_match("/(don|caractere|objectif|text-story)/",$label);
}



$sql = "select label,value from botExtra where label in ('vPrimaire','race')";
foreach ($bdd->query($sql)->fetchAll() as $json) {
    $selectAndOption[$json['label']] = json_decode($json['value'], true);
}
$selectAndOption['vSecondaire'] = $selectAndOption['vPrimaire'];

$sql = "select label,value from botExtra where label='raceByVoie'";
$raceByVoie = json_decode($bdd->query($sql)->fetch()['value'], true);

// Fonction anonyme pour gérer correctement l'insert de l'histoire

$insertData = function($l,$v){
    global $dataUseDefault;
    if (strpos($l,"story") == false){
        $dataUseDefault[$l] = $v;
    }else{
        $i = explode("-",$l);
        $dataUseDefault['story'][$i[2]][$i[0]] = $v;
    }
};

if (isset($_POST['act'])) {
    switch ($_POST['act']) {
        case "getTitle":
            retour([isset($selectAndOption[$_POST['id']][$_POST['value']]) ? $selectAndOption[$_POST['id']][$_POST['value']] : '']);
            break;

        case "deleteChap":
            $sql = "delete from ficheData where idPerso ='{$_SESSION['idPerso']}' and label like '%story-{$_POST['id']}'";
            $bdd->query($sql);
            retour(['success']);
            break;
        case "setData":
            $val = str_replace("'","\'",$_POST['value']);
            $label = $_POST['label'];
            if (empty($val))
                retour(['erreur', "la donnée est vide."]);
            if ($label == "name") {
                $sql = "select 1 from perso where prenom like '" . explode(' ', $val)[0] . " %'";
                if ($bdd->query($sql)->fetch()) {
                    retour(['erreur', 'Le premier mot de votre prénom est déjà utilisé.']);
                }
            }
            $myRetour = "vide";
            if(!checkForCookie($label)){
                $now = date("Y-m-d H:i:s");
                $sql = "insert into ficheData values ('{$_SESSION['idPerso']}','$label','$val','$now')  ON DUPLICATE KEY UPDATE VALUE='$val',dateInsert='$now'";
                $bdd->query($sql);
            }
            $myRetour = empty($_POST['otherVoie']) ? $_POST['value'] : selectRace([$_POST['value'], $_POST['otherVoie']]);
            retour(['success', $myRetour]);
            break;
        case "saveData":
            $now = date("Y-m-d H:i:s");
            foreach ($_POST['data'] as $label => $val){
                $val = str_replace("'","\'",$val);
                $sql = "insert into ficheData values ('{$_SESSION['idPerso']}','$label','$val','$now')  ON DUPLICATE KEY UPDATE VALUE='$val',dateInsert='$now'";
                $bdd->query($sql);
            }
            retour(['success', "Enregistrement fait"]);
            break;
        case "valid":
            $sql = "update site set state=1 where state=0 and idPerso='{$_SESSION['idPerso']}'";
            $bdd->query($sql);

    }

}else{
    $finalStep = 1;
    $dataUseDefault = [];
    $dateInsert = (new DateTime())->getTimestamp();
    foreach ($_COOKIE as $label => $val) {
        if (strpos($label, "data") === 0) {
            $ls = explode(":", $label);
            $data = explode("[dateCookie]", $val);
            $data[1] = date("Y-m-d H:i:s", $data[1]);
            $res = $bdd->query("select value from ficheData where idPerso ='{$_SESSION['idPerso']}' and label='{$ls[1]}' and dateInsert > '{$data[1]}'")->fetch();
            if (!empty($res)) {
                if(checkForCookie($label)){
                    setcookie($label, $res[0] . "[dateCookie]$dateInsert", time() + 3600 * 24 * 15);
                }
                $data[0] = $res[0];
            }
            $insertData($ls[1],$data[0]);
        }
    }


    $qry = "select label,value from ficheData where idPerso ='{$_SESSION['idPerso']}' and label not in ('" . implode("','", array_keys($dataUseDefault)) . "')";
    foreach ($bdd->query($qry)->fetchAll() as $bddCookie) {
        if(checkForCookie($bddCookie['label'])){
            setcookie("data:" . $bddCookie['label'], $bddCookie['value'] . "[dateCookie]$dateInsert", time() + 3600 * 24 * 15);
        }
        $insertData($bddCookie['label'],$bddCookie['value']);
    }

    //var_dump($dataUseDefault);
}


function SelectFor($type)
{
    global $selectAndOption;
    if (!empty($selectAndOption[$type])) {
        return genereSelect($selectAndOption[$type], $type);
    } else {
        false;
    }

}

function genereSelect($array, $name)
{
    global $dataUseDefault;
    $rep = function ($a) {
        return str_replace(["'", "<br>"], ['&#39;', '&#10;'], $a);
    };

    $ret = "<select class='TheSelect' name='$name'><option value='' style='display: none'>Choisis</option>";
    foreach ($array as $option => $title) {
        $ckeck = isset($dataUseDefault[$name]) && $option == $dataUseDefault[$name] ? 'selected' : '';
        $ret .= "<option required value='$option' title='" . $rep($title) . "' $ckeck>$option</option>";
    }
    $ret .= "</select>";


    return $ret;

}