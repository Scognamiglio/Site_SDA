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
                <td><label>Ton genre</label></td><td><?=genereSelect('genre')?></td>
                <td></td>
            </tr>


            <tr class="step-2">
                <td><label>Voie physique</label></td><td><?=genereSelect('vPhysique')?></td>
                <td><a class="info" id="vPhysique">info</a></td>
            </tr>
            <tr class="step-2">
                <td><label>Voie magique</label></td><td><?=genereSelect('vMagique')?></td>
                <td><a class="info" id="vMagique">info</a></td>
            </tr>


            <tr class="step-3">
                <td><label>Ta race</label></td><td><input name="race" placeholder="Humain"></td>
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
        <h3 class="spoiler" name="show-story">Histoire</h3>
        <div id="show-story">
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