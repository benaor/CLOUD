<?php
session_start();
//Si une session est deja ouverte
if(!empty($_SESSION['user'])):
    header('location:espace.php');
else:
    include "fonction.php";
    inscription();
endif;
?>


<h2>Inscrivez vous gratuitement</h2>

<form method="post">

    <label for="user">Nom</label>
    <input type="text" name="name" placeholder="votre nom"><br><br>

    <label for="firstName">Prenom</label>
    <input type="text" name="firstName" placeholder="votre prenom"><br><br>

    <label for="phone">numero telephone</label>
    <input type="tel" name="phone" placeholder="numero de tel"><br><br>

    <label for="email">Votre mail</label>
    <input type="mail" name="mail" placeholder="votre adresse mail"><br><br>

    <label for="email2">confirmez votre mail</label>
    <input type="mail" name="mail2" placeholder="confirmez mail"><br><br>

    <label for="naissance">date de naissance</label>
    <input type="date" name="naissance" placeholder="date de naissance"><br><br>

    <label for="user">Identifiant</label>
    <input type="text" name="user" placeholder="votre identifiant"><br><br>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" placeholder="votre mot de passe"><br><br>

    <label for="password2">Confirmez MDP</label>
    <input type="password" name="password2" placeholder="confirmez votre MDP"><br><br>

    <input type="submit" name="inscription" value="Je m'inscris">

</form>

<p>vous avez déjà un compte ? <a href="index.php">connectez-vous</a></p>

<!-- si une erreur doit s'afficher -->
<?php if(!empty($erreur)){echo"<p class='erreur'>".$erreur."</p>";}?>


<style>
*{text-align:center;}
.erreur{font-size:2rem;color:red;}
</style>