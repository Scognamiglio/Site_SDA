<?php
date_default_timezone_set( "Europe/Paris");

header('Content-Type: text/html; charset=utf-8');
include "../conf.php";
include "asset/function.php";
$bdd = new PDO("mysql:host={$dataBdd['host']};dbname={$dataBdd['dbname']};charset=utf8", $dataBdd['user'], $dataBdd['passwd']);

foreach (scandir("asset/lib/static") as $cls){
    if(strpos($cls,'.')!==0) {
        include "lib/static/$cls";
    }
}


spl_autoload_register(function ($class_name) {
    include "asset/lib/".$class_name . '.php';
});


if(!empty($_COOKIE['myToken'])){
    // TODO NAV PRIVE //

    $qry = "SELECT s.idPerso,prenom FROM login l INNER JOIN site s ON s.idPerso=l.idPerso LEFT JOIN perso p ON p.idPerso=l.idPerso WHERE idLogin='{$_COOKIE['myToken']}'";
    $account = $bdd->query($qry)->fetch();
    if(!empty($account)){
        $_SESSION['idPerso'] = $account['idPerso'];
        $_SESSION['name'] = empty($account['prenom']) ? $account['idPerso'] : $account['prenom'];
    }
}