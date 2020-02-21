<?php
session_start();
if(empty($_SESSION['user'])):
    header('location:index.php');
else:
    include "fonction.php";
    suppressionDossier();
endif;