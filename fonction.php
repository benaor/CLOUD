<?php

function connectBDD(){
    return new PDO('mysql:host=localhost;dbname=cloud','root','root');
}

function inscription(){
    
    //on se connecte a la base de données
    $bdd = connectBDD();

    //si on clique sur le bouton "je m'inscris"
    if(   isset(   $_POST['inscription']   )   ){

        $nom         = htmlspecialchars($_POST['name']);
        $prenom      = htmlspecialchars($_POST['firstName']);
        $tel         = htmlspecialchars($_POST['phone']);
        $mail        = htmlspecialchars($_POST['mail']);
        $mail2       = htmlspecialchars($_POST['mail2']);
        $naissance   = htmlspecialchars($_POST['naissance']);
        $identifiant = htmlspecialchars($_POST['user']);
        $mdp         = sha1($_POST['password']);
        $mdp2        = sha1($_POST['password2']);


        //On verifie que tous les champs soient rempli 
        if(   !empty($_POST['name']) &&    !empty($_POST['firstName']) &&    !empty($_POST['phone']) &&    !empty($_POST['mail']) &&    !empty($_POST['mail2']) &&    !empty($_POST['naissance']) &&    !empty($_POST['user']) &&    !empty($_POST['password']) &&    !empty($_POST['password2'])  ){

            //On verifie la longueur de l'identifiant 
            if(strlen($identifiant) <= 16){         

                //on verifie que les mots de passe sont identiques
                if($_POST['password'] == $_POST['password2'] ){
               
                    //On verifie la longueur du mdp
                    if(strlen($_POST['password']) <= 16){

                        //on verifie que les deux mails sont identiques
                        if($mail == $mail2){
                            
                            //On verifie la validité de l'adresse mail
                            if(filter_var($mail, FILTER_VALIDATE_EMAIL)){

                                //On verifie que l'identifiant ne soit pas deja utilisé
                                $reqid = $bdd->prepare("SELECT * FROM membres_cloud WHERE identifiant_membre = ?");
                                $reqid->execute(array($identifiant));
                                $idexist = $reqid->rowCount();

                                if($idexist == 0){
                                    
                                    //on insert les données dans la table "user"
                                    $insertmbr = $bdd->prepare("INSERT INTO membres_cloud(nom, prenom, tel, mail, naissance, identifiant, mdp) VALUES(?,?,?,?,?,?,?)");
                                    $insertmbr->execute(array($nom, $prenom, $tel, $mail, $naissance, $identifiant, $mdp));
                                
                                    //On redirige automatiquement vers index.php et on crée un dossier au nom de l'user
                                    $structure = "fichier_client/".$identifiant;
                                    mkdir($structure, 0777, TRUE);
                                    header('location: index.php');
                                    }

                                    else{
                                        //si l'email est deja utilisé 
                                        $erreur = "Cet email est déjà utilisé";
                                    }


                            }
                            else{
                                //si l'email n'est pas valide
                                $erreur = "l'adresse email n'est pas valide";
                            }

                        }

                        else{
                            //si les mails ne sont pas identiques
                            $erreur = "les deux mails sont differents";
                        }
                    }

                    else{
                        //Si l'identifiant est trop long
                        $erreur = "Le mdp est trop long";
                    }
                }
                else{
                    //si les mdps sont differents
                    $erreur = "les deux mdp ne correspondent pas";
                }

            }
            else{
                //Si l'identifiant est trop long
                $erreur = "L'identifiant est trop long";
            }
        }
        else{
            //si tous les champs ne sont pas rempli
            $erreur = "Tous les champs doivent être rempli";
        }

    }


}

function connexion(){
     //on se connecte a la base de données
     $bdd = connectBDD();

     //lorsqu'on clique sur submit
     if(   isset(   $_POST['connexion'])   ){
 
         //On recupère dans une variable l'identifiant et le mdp, et on securise (eviter l'injection SQL + chiffrage du mdp)
         $user = htmlspecialchars($_POST['user']);
         $password    = sha1($_POST['password']);
 
         //On verifie que les champs soient rempli
         if(  !empty($_POST['user'])   &&   !empty($_POST['password'])  ){
 
             // On compare l'identifiant et le mdp saisi avec ceux de la base de données
             $reqidentification = $bdd->query("SELECT * FROM membres_cloud WHERE identifiant = '$user' AND mdp = '$password'");
             $tableau = $reqidentification->fetch(PDO::FETCH_ASSOC);
 
             if(is_array($tableau)){
                 $_SESSION['user']=$tableau['prenom'];
                 $_SESSION['identifiant'] = $tableau['identifiant'];
                 echo "redirection vers votre espace membre";
                 header('location:espace.php');
             }
 
             else{
                 $erreur = "identifiant incorrect";
             }
 
         }
 
         else{
             //si les champs ne sont pas tous rempli
             $erreur = "tous les champs doivent être rempli";
         }
     }
}

function creeDossier(){

    //Quand new_dossier est submit
    if(isset($_POST['new_dossier'])){

        //On verifie que new_dossier contient quelque chose
        if(!empty($_POST['new_dossier'])){


         
            //On sécurise le contenu pour eviter les injection de script
            $nomNewDossier = htmlspecialchars($_POST['new_dossier']);
            $identifiant   = htmlspecialchars($_SESSION['identifiant']);

            //On crée le nouveau dossier dans le dossier perso de l'utilisateur
            $structure = "fichier_client/".$identifiant."/".$nomNewDossier;
            mkdir($structure, 0777, TRUE);
        }

        else{
        echo "Le champs ne doit pas être vide";
        }

    }

}

function afficherDossier(){
        
    $dossierUser = "fichier_client/".$_SESSION['identifiant']."/";

    $handle = opendir($dossierUser);

        while(false !== ($boucleDossierUser = readdir($handle))){

            if ($boucleDossierUser != "." && $boucleDossierUser != "..") {
                echo "<p><a href='open.php?dossier=".$boucleDossierUser."'>".$boucleDossierUser."</a>\n"."<button type='button'><a href='suppressionDossier.php?delete=".$boucleDossierUser."'>delete</a></button></p>";
            }
        }

    closedir($handle);
}

function suppressionDossier(){
    rmdir('fichier_client/'.$_SESSION['identifiant'].'/'.$_GET['delete']);
    header('location:explorer.php');
}

function ouvrirDossier(){
    $dossierOpenUser = "fichier_client/".$_SESSION['identifiant']."/".$_GET['dossier']."/";

    $handle = opendir($dossierOpenUser);

        while(false !== ($boucleDossierOpenUser = readdir($handle))){

            if ($boucleDossierOpenUser != "." && $boucleDossierOpenUser != "..") {
                echo "<p><a href='fichier_client/".$_SESSION['identifiant']."/".$_GET['dossier']."/".$boucleDossierOpenUser."'>".$boucleDossierOpenUser."</a>\n"."<button type='button'><a href='suppressionFichier.php?Fichier=".$boucleDossierOpenUser."&dossier=".$_GET['dossier']."'>delete</a></button></p>";
            }
        }

    closedir($handle);

}

function uploadFichier(){
    if(isset($_POST['upload'])){

        $file_name = $_FILES['fichier']['name'];
        $file_type = $_FILES['fichier']['type'];
        $file_size = $_FILES['fichier']['size'];
        $file_tem_loc = $_FILES['fichier']['tmp_name'];
    
        $nombreDeFichiers = count($_FILES['fichier']['name']);

        for($i = 0; $i < $nombreDeFichiers ; $i++){

            $file_name = $_FILES['fichier']['name'][$i];
            $file_store = "fichier_client/".$_SESSION['identifiant']."/".$_GET['dossier']."/".$file_name;
            move_uploaded_file($file_tem_loc[$i], $file_store);

        }//fin boucle for
        echo "fichier envoyé avec succès !";
        header('location:open.php?dossier='.$_GET['dossier']);
    
    }
}

function suppressionFichier(){
    unlink('./fichier_client/'.$_SESSION['identifiant'].'/'.$_GET['dossier']."/".$_GET['Fichier']);

    header('location:open.php?dossier='.$_GET['dossier']);
}

?>
