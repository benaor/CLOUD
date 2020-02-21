<?php
session_start();
if(empty($_SESSION['user'])):
    header('location:index.php');
endif;

include "fonction.php";
ouvrirDossier();
uploadFichier();
?>

<h2>upload un fichier</h2>

    <form action="" method="post" enctype="multipart/form-data">
    <label for="fichier">ajouter un fichier</label>
        <input type="file" name="fichier[]" multiple>
        <input type="submit" value="envoyer" name="upload">
    </form>
