<?php
session_start();
if(empty($_SESSION['user'])):
    header('location:index.php');
endif;
?>


<div>
<h2>bienvenue sur votre espace membre</h2>

<p>bienvenue <?php echo $_SESSION['user'] ?> </p>

<a href="deconnexion.php">deconnexion</a>


</div>