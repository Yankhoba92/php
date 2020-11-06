<?php

require_once 'inc/init.php';


// 1- controle de l'existance du produit demandé (1 produit a pu être mis en favoris et supprimé de la BDD...)
debug($_GET);

if(isset($_GET['id_produit'])){ // s'il ya un "id_produit" dans l'URL
    // on vévérifie l'existance du produit en BDD :
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));

    if($resultat->rowCount() == 0) { // si resultat n'a pas de ligne, c'est que le produit n'est pas en BDD : on redirige aussi vers la boutique
        header('location:index.php');
        exit;

    }

    // 2- On prépare les données du produit à afficher
    debug($resultat); // Il faut faire un fetch sur cette objet PDOstatement sans boucle car il n'y a qu'un seul produit par ID 
    $produit = $resultat->fetch(PDO::FETCH_ASSOC);
    // debug($produit);
    extract($produit); // Cette fonction prédééfini crée des variables nommées comme des indices du tableau et au uelle on affecte des valeurs du tableau. Exemple :$produit['titre'] devient la variable $titre.
}else{
    header('location:index.php');// s'il y a pas d'id_produit dans l'url, on redirige vers la boutique
    exit;
}


//-------------------Affichage---------------------
require_once 'inc/header.php';
?>
    <div class="row">
    
        <div class="col-12">
            <h1 class="mt-4"><?php echo $titre; ?></h1>
        </div>
        <div class="col-md-8"><!-- photo -->
            <img class="img-fluid w-50" src="<?php echo $photo; ?>"alt ="<?php echo $titre; ?>">
        </div>

        <div class="col-md-4"><!-- les infos du produit -->
           <h2>Description</h2>
           <p><?php echo $description; ?></p>

           <h2>Détails</h2>
           <ul>
                <li>Catégorie : <?php echo $categorie; ?></li>
                <li>Couleur: <?php echo $couleur; ?></li>
                <li>Taille : <?php echo $taille; ?></li>
            </ul>

            <div class="lead">Prix : <?php echo number_format($prix, 2, ",", " "); ?> €TTC</div>

            <div><a href="index.php?categorie=<?php echo $categorie; ?>">Retour vers votre sélection</a></div>
        </div>

    
    
    </div><!-- .row -->




<?php
require_once 'inc/footer.php';