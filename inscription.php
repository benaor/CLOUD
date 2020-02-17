<?php
session_start();
//Si une session est deja ouverte
if(!empty($_SESSION['user'])):
    header('location:espace.php');
else:

    //on se connecte a la base de données
    $bdd = new PDO('mysql:host=localhost:3308;dbname=cloud','root','');

    //si on clique sur le bouton "je m'inscris"
    if(   isset(   $_POST['inscription']   )   ){

        $nom         = htmlspecialchars($_POST['name']);
        $prenom      = htmlspecialchars($_POST['firstName']);
        $tel         = htmlspecialchars($_POST['phone']);
        $mail        = htmlspecialchars($_POST['mail']);
        $mail2       = htmlspecialchars($_POST['mail2']);
        $naissance      = htmlspecialchars($_POST['naissance']);
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
                                $reqid = $bdd->prepare("SELECT * FROM membres WHERE identifiant_membre = ?");
                                $reqid->execute(array($identifiant));
                                $idexist = $reqid->rowCount();

                                if($idexist == 0){
                                    
                                    //on insert les données dans la table "user"
                                    $insertmbr = $bdd->prepare("INSERT INTO membres(nom_membre, prenom_membre, tel_membre, mail_membre, naissance_membre, identifiant_membre, mdp_membre) VALUES(?,?,?,?,?,?,?)");
                                    $insertmbr->execute(array($nom, $prenom, $tel, $mail, $naissance, $identifiant, $mdp));
                                
                                    //On redirige automatiquement vers index.php
                                    $_SESSION['comptecree'] = "<p class='vert'> votre compte a bien été crée ! </p>";
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

endif;
?>


<h1>bienvenue sur l'interface de gestion locative</h1>

<h2>Inscrivez vous gratuitement, et gèrez vos location en toute simplicité</h2>

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

<?php if(!empty($erreur)){echo"<p class='erreur'>".$erreur."</p>";}?>

<style>
*{text-align:center;}
.erreur{font-size:2rem;color:red;}
</style>