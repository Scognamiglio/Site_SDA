<style>
    .TheSelect{
        width: 100%;
    }

    h3{
        border: 1px black solid;
        width: 10em;
        border-radius: 3em;
        padding-bottom: 0.1em;
        border-left: 1em #8219D5 solid;
        border-right: 1em #8219D5 solid;
        text-align: center;
    }

    div[role="dialog"][aria-describedby="popin_info"]{
        width: 30em!important;
        height: 7em!important;
    }

    .info{
        padding-left: 0.5em;
    }
</style>


<?php

if(!empty($_SESSION['idPerso'])){

    // Rajouter une vérif si le personnage existe déjà
    ?>
        <h3 class="spoiler" name="show-gene">générale</h3>
        <table id="show-gene" style="display: none">
            <tr>
                <td><label>Ton Identité</label></td><td><input name="name" placeholder="Zheneos Hikari"></td>
            </tr>
            <tr>
                <td><label>Ton âge</label></td><td><input name="age" placeholder="21"></td>
            </tr>
            <tr>
                <td><label>Ton genre</label></td><td><?=genereSelect('genre')?></td>
            </tr>
            <tr>
                <td><label>Voie physique</label></td><td><?=genereSelect('vPhysique')?></td>
                <td><a class="info" id="vPhysique">info</a></td>
            </tr>
            <tr>
                <td><label>Voie magique</label></td><td><?=genereSelect('vMagique')?></td>
                <td><a class="info" id="vMagique">info</a></td>
            </tr>
            <tr>
                <td><label>Ta race</label></td><td><input name="race" placeholder="Humain"></td>
            </tr>
        </table>
    </br>
    <h3 class="spoiler" name="show-perso">Personnalité</h3>
    <span id="show-perso" style="display: none">
        <label>Décrit nous rapidement ta personnalité (Limité à 2000 caractères)</label>
        <br>
        <textarea></textarea>
        <br><br>
        <label>Ton objectif ?</label>
        <br>
        <textarea name="objectif" placeholder="Devenir un grand magicien et tous savoir du monde"></textarea>
        <br>
    </span>
    <br>
    <h3 class="spoiler" name="show-story">Histoire</h3>
    <span id="show-story">
        <br>
        <p>Il faudra séparé ton histoire en chapitre de maximum 2000 caractères (Il est possible de n'avoir qu'un seul chapitre)</p>
        <br>
        <input name="Title" placeholder="enfance">
        <br>
        <strong class="spoiler" name="show-story-0">Voir</strong>
        <div id="show-story-0" style="display: none">
            <textarea></textarea>
        </div>
        <br>
        <input name="Title" placeholder="enfance">
        <br>
        <strong class="spoiler" name="show-story-1">Voir</strong>
        <div id="show-story-1" style="display: none">
            <textarea></textarea>
        </div>
        <br>
        <input name="Title" placeholder="enfance">
        <br>
        <strong class="spoiler" name="show-story-2">Voir</strong>
        <div id="show-story-2" style="display: none">
            <textarea></textarea>
        </div>
    </span>

    <script type="text/javascript" src="script/new_char.js"></script>



    <?php

}else{?>
    <p>il est nécéssaire de créer un compte pour créer un personnage, c'est très rapide à faire !</p>Si tu as déjà un compte, ferme la fênetre d'inscription et clique sur "Connexion"
    <script>
        openPopin('dialog')
    </script>

<?php }