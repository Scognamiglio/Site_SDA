<?php if(!empty($_SESSION['name'])):?>
    <div style="float: right;border: 1px solid black;padding: 0.4em 1em;margin-right: 1px;background-color: lightgrey;border-radius: 1em;">
        <div id="actif-login">
            <div class="my"><?=$_SESSION['name']?></div>

            <div style="display: none" class="actif-login"><a href="?page=account">Mon compte</a></div>

            <div style="display: none" class="actif-login"><a class="register">Déconnexion</a></div>
        </div>
    </div>


<?php else:?>
    <div style="float: right;margin-right: 1em;">
        <span>
            <a class="login">Connexion</a>
        </span>
        <span>
            <a class="register">Inscription</a>
        </span>
    </div>


    <?php endif;?>
<div class="cont-logo" style="margin-left:10em">
    <span class="logo-header">
        L'éveil du souverain des âmes
    </span>
</div>
