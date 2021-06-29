<link href="styles/new_char.css" rel="stylesheet" type="text/css">
<?php

if(!empty($_SESSION['idPerso'])){

    // Rajouter une vérif si le personnage existe déjà
    ?>
        <h3 class="spoiler" name="show-gene">générale</h3>
        <table id="show-gene">


            <tr class="step-1">
                <td><label>Ton Identité</label></td><td><input name="name" placeholder="Zheneos Hikari"></td>
                <td></td>
            </tr>
            <tr class="step-1">
                <td><label>Ton âge</label></td><td><input name="age" type="number" placeholder="21"></td>
                <td></td>
            </tr>
            <tr class="step-1">
                <td><label>Ton genre</label></td><td><?=SelectFor('genre')?></td>
                <td></td>
            </tr>


            <tr class="step-2">
                <td><label>Classe</label></td><td><?=SelectFor('classe')?></td>
                <td><a class="info" id="classe">info</a></td>
            </tr>
            <tr class="step-2">
                <td><label>Voie physique</label></td><td><?=SelectFor('vPhysique')?></td>
                <td><a class="info" id="vPhysique">info</a></td>
            </tr>
            <tr class="step-2">
                <td><label>Voie magique</label></td><td><?=SelectFor('vMagique')?></td>
                <td><a class="info" id="vMagique">info</a></td>
            </tr>


            <tr class="step-3">
                <td><label>Ta race</label></td><td><?=SelectFor('race')?></td>
                <td><a class="info" id="race">info</a></td>
            </tr>
        </table>

    <span class="step-4">
        </br>
        <h3 class="spoiler" name="show-perso">Personnalité</h3>
        <span id="show-perso">
            <label>Décrit nous rapidement ta personnalité (Limité à 2000 caractères)</label>
            <br>
            <textarea></textarea>
            <br><br>
            <label>Ton objectif ?</label>
            <br>
            <textarea name="objectif" placeholder="Devenir un grand magicien et tous savoir du monde"></textarea>
            <br>
        </span>
    </span>

    <span class="step-5">
        <br>
        <h3 class="spoiler" name="show-cat-story">Histoire</h3>
        <div id="show-cat-story">
            <p>Il faudra séparé ton histoire en chapitre de maximum 2000 caractères (Il est possible de n'avoir qu'un seul chapitre)</p>
            <button id="newChap">Nouveau chapitre</button>
            <br>
            <br>
            <span id="TheSpanForStory">

            </span>
            <button id="deleteChap" class="buttonDelete">X</button>
        </div>
        <br>
    </span>


    <!-- Un tpl pour créer dynamiquement les champs pour l'histoire -->
    <span id="tpl_story" class="not">
        <input name="title-story-x" placeholder="enfance">
        <br>
        <strong class="spoiler" name="show-story-x">Voir</strong>
        <div id="show-story-x">
            <textarea name="text-story-x"></textarea>
        </div>
        <br>
    </span>

    <script type="text/javascript" src="script/new_char.js"></script>



    <?php

}else{?>
    <p>il est nécéssaire de créer un compte pour créer un personnage, c'est très rapide à faire !</p>Si tu as déjà un compte, ferme la fênetre d'inscription et clique sur "Connexion"
    <script>
        openPopin('dialog')
    </script>

<?php }