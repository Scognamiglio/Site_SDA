
<?php if(!empty($_SESSION['name'])):?>
    <div class="widgetLog">
        <div id="actif-login">
            <div class="my"><?=$_SESSION['name']?></div>

            <div style="display: none" class="actif-login"><a href="?page=account">Mon compte</a></div>

            <div style="display: none" class="actif-login"><a class="buttonDeco">Déconnexion</a></div>
        </div>
    </div>


<?php else:?>
    <div class="widgetNotLog">
        <span>
            <a class="login">Connexion</a>
        </span>
        <span>
            <a class="register">Inscription</a>
        </span>
    </div>


    <?php endif;?>
<br>
<div class="cont-logo">
    <span class="logo-header">
        <img src="asset/image/logo.png">
    </span>
</div>
