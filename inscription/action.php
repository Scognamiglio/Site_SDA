<?php
$id = $_POST['id'];
if(empty($id))
    retour(['erreur','Aucun identifiant renseigné']);



if(!preg_match('/[0-9]{18,20}/',$id)){
    retour(['erreur','Mauvais format']);
}


$sql = "select 1 from site where idPerso='$id'";
if($bdd->query($sql)->fetch()){
    retour(['erreur','Compte déjà créer(Voir Zheneos si problème)']);
}

$password = uniqid();
$bdd->query("insert into site values('$id',null,'".sha1($password)."',0)");
$idChannel = postDiscord('users/@me/channels',['recipients' => [$id]])['id'];
postDiscord("channels/$idChannel/messages", ['content' => $password]);

retour(['success','0']);