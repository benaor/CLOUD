<?php
session_start();
//Si une session est deja ouverte
if(!empty($_SESSION['user'])):
    header('location:espace.php');
else:
    include "fonction.php";
    connexion();
endif;
?>



<h2>iterface de connexion</h2>

<form method="post">

    <label for="user">Identifiant</label>
    <input type="text" name="user" placeholder="votre identifiant"><br><br>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" placeholder="votre mot de passe"><br><br>

    <input type="submit" value="Je me connecte" name="connexion">
    <?php if(!empty($erreur)){echo"<p class='erreur'>".$erreur."</p>";}?>

</form>

<p>vous n'avez pas de compte ? <a href="inscription.php">inscrivez-vous</a> </p>


<style>
*{text-align:center;}
.erreur{font-size:2rem;color:red;}
</style>