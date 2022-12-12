<?php

// Gestion Ajax des réponses

$act = getParam("act");
$app = getParam('app') ?? 'all';
if(!empty($act)){
    $nameApp = "app".ucfirst($app); // voir asset/lib/
    $app = new $nameApp();
    $data = $_POST;
    if(!empty($data['app'])){unset($data['app']);}
    $app->process($data ?? []); // La méthode meurs ici
    die(); // inutile, mais au cas où.
}



// Si paramètre, les passer au javascrit pour qu'il soit exploitable

$app = getParam('app') ?? 'all';
$displayNone = "style='display:none'";
if(!empty($app)){
    echo "<input id='app' name='app' value='$app' $displayNone />";
}