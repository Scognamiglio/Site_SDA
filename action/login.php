<?php

$login = $_POST['login'];
$psw = $_POST['psw'];

$ret = [];
if(empty($login)){
$ret[] = "Le login est vide";
}
if(empty($psw)){
    $ret[] = "Le password est vide";
}
if(count($ret)>0){
    retour(['erreur',implode('\n',$ret)]);
}

$qry = "SELECT s.idPerso,prenom FROM site s LEFT JOIN perso p ON s.idPerso=p.idPerso where (s.login='$login' or s.idPerso='$login') and s.password='".sha1($psw)."'";
$account = $bdd->query($qry)->fetch();
if(!$account){
    retour(['erreur',"Aucune correspondance entre le mot de passe et l'identifiant"]);
}

$qry = "select idLogin from login where ip='{$_SERVER['REMOTE_ADDR']}' and idPerso='{$account['idPerso']}'";
$exist = $bdd->query($qry)->fetch();
if(!$exist){
    $qry = "delete from login where ip='{$_SERVER['REMOTE_ADDR']}' and idPerso='{$account['idPerso']}";
    $bdd->query($qry);
    $u = uniqid();
    $qry = "insert into login values('$u','{$_SERVER['REMOTE_ADDR']}','{$account['idPerso']}',now())";
    $bdd->query($qry);
}else{
    $u = $exist['idLogin'];
}

retour(['success',$u]);