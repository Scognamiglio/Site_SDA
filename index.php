<?php

include "asset/common.php";
$page = (empty($_GET['page']) ? 'accueil' : $_GET['page']).".php";

if(in_array($page,scandir("action"))){
    include "action/$page";
}
?>

<html>
    <head>
        <title>Souverain des âmes</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link href="styles/main.css" rel="stylesheet" type="text/css">
        <script
                src="https://code.jquery.com/jquery-3.5.1.min.js"
                integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
                crossorigin="anonymous">

        </script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body>

        <?php
        include "sqlet/header.php";
        include "sqlet/modal.php";
        ?>
        <script type="text/javascript" src="script/methode.js"></script>
        <?php
        if(in_array($page,scandir("module"))){
            include "module/$page";
        }
        ?>
        <script type="text/javascript" src="script/listener.js"></script>
    </body>
</html>