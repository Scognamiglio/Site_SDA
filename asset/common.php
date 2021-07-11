<?php
date_default_timezone_set( "Europe/Paris");

header('Content-Type: text/html; charset=utf-8');
include "../conf.php";
include "asset/function.php";




if(!empty($_COOKIE['myToken'])){
    // TODO NAV PRIVE //

    $qry = "SELECT s.idPerso,prenom FROM login l INNER JOIN site s ON s.idPerso=l.idPerso LEFT JOIN perso p ON p.idPerso=l.idPerso WHERE idLogin='{$_COOKIE['myToken']}'";
    $account = $bdd->query($qry)->fetch();
    if(!empty($account)){
        $_SESSION['idPerso'] = $account['idPerso'];
        $_SESSION['name'] = empty($account['prenom']) ? $account['idPerso'] : $account['prenom'];
    }
}