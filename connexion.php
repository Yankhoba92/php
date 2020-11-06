<?php
require_once 'inc/init.php';
$message = ''; // Pour afficher le message de déconnexion

// 2- déconnexion du membre
// debug($_GET);
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') { // si "action" est dans l'url et qu'il a pour valeur "déconnexion", c'est que le membre a cliqué sur "Deconnexion".
    unset($_SESSION['membre']); // On vide la session de sa partie "membre" tout en conservant l'événement partie "panier".
    $message .= '<div class="alert alert-info"> Vous étes déconnecté.</div>';
}



// 3-On vérifie que le membre n'est pas déja connécté. Sinon on le redirige vers le profil
if(estConnecte()){
    header('location:profil.php'); // on autorise pas la reconnexion on redirige vers profil.php
    exit; // on quitte ce script
}






// 1- traitement du formulaire
debug($_POST);

    if(!empty($_POST)){ // si le formulaire à étét envoyé

        // contrôles du formulaire
        if(empty($_POST['pseudo']) ||empty($_POST['mdp'])) { // si le pseudo ou le mdp est vide
            $contenu .= '<div class = "alert alert-danger">Les identifiants sont obligatoires.</div>';

        }

            if(empty($contenu)) { // si la variable est vide, c'est qu'il n y a pas de message d'erreur 
            $resultat =executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

            if($resultat->rowCount() ==1){ // s'il y a une ligne de résultat c'est que le pseudo est een BDD :on peut alors vérifier le mdp
                // debug($resultat);

                $membre = $resultat -> fetch(PDO::FETCH_ASSOC);// on "fetch" l'objet $resultat pour en extraire les données, sans boucle car le pseudo est unique en BDD.
                debug($membre);

                if(password_verify($_POST['mdp'], $membre['mdp'])){ 
                    $_SESSION['membre'] = $membre;

                    header('location:profil.php');
                    exit; 

                }else{
                    $contenu = '<div class="alert alert-danger">Erreur sur les identifiants.</div>';
                    
                }

            } else {
                $contenu .= '<div class="alert alert-danger"> Erreur sur les identifiants.</div>';
            }

            }



    } // fin du if (!empty($_POST))





//------------------AFFICHAGE---------
require_once 'inc/header.php';

?>
<h1 class ="mt-4">Connexion</h1>

<?php
echo $message; // pour afficher le message de déconnexion
echo $contenu; // pour afficher les autres 
?>
<form action="" method="post">

    <div><label for="pseudo">Pseudo</label></div>
    <div><input type="text" name="pseudo" id="pseudo"></div>

    <div><label for="mdp">Mot de passe</label></div>
    <div><input type="password" name="mdp" id="mdp"></div>

    <div><input type="submit" value="Se connecter" class="btn btn-info mt-4"></div>


</form>



<?php
require_once 'inc/footer.php';
