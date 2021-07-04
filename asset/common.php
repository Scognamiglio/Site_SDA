<?php
date_default_timezone_set( "Europe/Paris");

header('Content-Type: text/html; charset=utf-8');
include "../conf.php";

function retour($a){
    $htmlRecur = function ($tab) use (&$htmlRecur){
        foreach ($tab as $k => $v){
            $tab[$k] = (is_array($v)) ? $htmlRecur($v) : htmlentities($v);
        }
        return $tab;
    };

    echo json_encode($htmlRecur($a), JSON_FORCE_OBJECT);
    die();
}


if(!empty($_COOKIE['myToken'])){
    $qry = "SELECT s.idPerso,prenom FROM login l INNER JOIN site s ON s.idPerso=l.idPerso LEFT JOIN perso p ON p.idPerso=l.idPerso WHERE idLogin='{$_COOKIE['myToken']}'";
    $account = $bdd->query($qry)->fetch();
    if(!empty($account)){
        $_SESSION['idPerso'] = $account['idPerso'];
        $_SESSION['name'] = empty($account['prenom']) ? $account['idPerso'] : $account['prenom'];
    }
}