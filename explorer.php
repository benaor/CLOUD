<?php
    //On demarre la session est on verifie que l'utilisateur est connecté
    session_start();
    if(empty($_SESSION['user'])):

        //Si il n'est pas connecté, on le redirige vers l'index.php pour qu'il se connecte
        header('location:index.php');
    endif;

    include "fonction.php";
    creeDossier();
?>

<h2>parcourir vos fichier</h2>

<?php
    afficherDossier();
?>

<h2>crée un dossier</h2>

    <form action="" method="post">
    <label for="new_dossier">crée un dossier</label>
    <input type="text" name="new_dossier">
    <input type="submit" value="cree dossier">
    </form>


<h2>upload un fichier</h2>

    <form action="" method="post">
    <input type="file" name="fichier">
    <input type="submit" value="envoyer" name="envoyer">
    </form>
