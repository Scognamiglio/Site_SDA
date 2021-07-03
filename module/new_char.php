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
            <textarea name="caractere" maxlength="2000" minlength="500" id="cara_t"></textarea>
            <br><span id="cara_c" class="not"></span>
            <br><br>
            <label>Ton objectif ?</label>
            <br>
            <textarea name="objectif" maxlength="2000" minlength="500" id="obj_t"placeholder="Devenir un grand magicien et tous savoir du monde"></textarea>
            <br><span id="obj_c" class="not"></span>
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
            <span id="total">Total : 0 caractères (Minimum 2000)</span>
            <button id="deleteChap" class="buttonDelete">X</button>
        </div>
        <br>
    </span>


    <span class="step-6">
        <h3>Ton don</h3>
        <p>
            Le don est le pouvoir inérant à chaque personne dans ce monde. <br>Il permet à son utilisateur d'avoir des capacités que les autres ne possèdes pas.
            <br><br>P.S:Si tu clique sur un titre(ex:Description) Tu peux en cacher le contenu
            <br>Plus d'information : ##LINK##
        </p>

        <input name="donName" placeholder="Maître des âmes" style="width: 15em;text-align: center;">
        <br>
        <br>
        <strong class="spoiler" name="show-donDescr">Description</strong>
        <div id="show-donDescr">
            <label>Une petite description de son fonctionnement ?</label>
            <br>
            <textarea name="donDescr" maxlength="2000" minlength="30" id="dond_t" placeholder="Ce don donne à son utilisateur la capacité de percevoir les âmes et de s'en nourrir si la personne la possédant est morte.&#10;ça permet à son utilisateur de récupérer de l'énergie"></textarea>
            <br><span id="dond_c" class="not"></span>
        </div>

    </span>
    <span class="step-7">
        <br>
        <strong class="spoiler" name="show-donEveil">Eveil</strong>
        <div id="show-donEveil">
            <label>Un don suffisament exercé peut s'éveillé.<br>Ce n'est pas un simple gain de puissance (mais ça peut l'être si aucune idée)</label>
            <br>
            <textarea name="donEveil" maxlength="2000" minlength="30" id="done_t" placeholder="Suite à son éveil, l'utilisateur gagnera la capacité de rentrer en contact avec les âmes et d'utiliser la sienne&#10;&#10;celà lui permet de créer des attaques basés sur son âme, mais aussi de manipuler ou sentir ce qui se passe proche de lui, à condition d'être concentré."></textarea>
            <br><span id="done_c" class="not"></span>
        </div>

    </span>
    <span class="step-8">
        <br>
        <strong class="spoiler" name="show-donTranscendance">Transcendance</strong>
        <div id="show-donTranscendance">
            <label>Après l'éveil, il existe un stade encore plus puissant nommé la transcendance<br>Ce n'est pas un simple gain de puissance (mais ça peut l'être si aucune idée)</label>
            <br>
            <textarea name="donTranscendance" maxlength="2000" minlength="30" id="dont_t" placeholder="Maintenant l'âme et le corps de l'utilisateur ne font plus qu'un, lui permettant de légèrement plané, de passer au travers la matière non magique voir même de puissé dans la puissance de son âme pour gagner une force terrifiante."></textarea>
            <br><span id="dont_c" class="not"></span>
        </div>
    </span>
    <span class="step-9">

        <br>
        <strong class="spoiler" name="show-donComp">Complémentaire</strong>
        <div id="show-donComp">
            <label>Des informations complémentaires ? Des limitations ? Vous pouvez les mettres ici !</label>
            <br>
            <textarea name="donComp" maxlength="2000" minlength="0" id="donc_t" placeholder="- Sous sa forme d'âme il est très sensible, surtout au feu et à la lumière&#10;- Quand il utilise ses capacités, ses yeux sont violets&#10;- Si il ne se nourrit pas assez souvent, il finit par s'affaiblir.&#10;- Une âme morte depuis trop de temps le rend malade."></textarea>
            <br><span id="donc_c" class="not"></span>
        </div>
    </span>


    <!-- Un tpl pour créer dynamiquement les champs pour l'histoire -->
    <span id="tpl_story" class="not">
        <input name="title-story-x" placeholder="enfance">
        <br>
        <strong class="spoiler" name="show-story-x">Voir</strong>
        <div id="show-story-x">
            <textarea name="text-story-x" id="story-x_t" maxlength="2000" minlength="0"></textarea>
            <br><span id="story-x_c" class="not"></span>
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