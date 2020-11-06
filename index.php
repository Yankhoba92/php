<?php

require_once'inc/init.php';
$contenu_gauche =''; // pour le HTML du bloc "catégories"
$contenu_droite =''; // pour le HTML du bloc "produits"

// 1- Affichage des catégories
$resultat =executeRequete("SELECT DISTINCT categorie FROM produit"); // On selectionne toutes les catégorie en enlevant les doublons avec DISTINST.

$contenu_gauche .='<div class="list-group mb-4">';
    // Lien "toutes les categories" :
    $contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Toutes les catégories </a>'; // On passe dans l'URL que la catégorie est en "all" vers la même page

    // Lien des catégorie en bdd :
    while ($cat =$resultat->fetch(PDO::FETCH_ASSOC)) {
        // debug($cat); // Est un tableau avec un indice 'categorie'
        $contenu_gauche .='<a href="?categorie=' . $cat['categorie']. '" class="list-group-item">'. $cat['categorie'] .'</a>';
    }

$contenu_gauche .= '</div>';

// 2- Affichage des produits :
// debug($_GET);
if(isset($_GET['categorie']) && $_GET['categorie'] != 'all' ){ // si on a demandé une catégorie autre que "toutes les catégories", on selectionne en BDD les produits de la catégorie demandée :
    $resultat = executeRequete("SELECT id_produit, reference, titre, photo, prix, description FROM produit WHERE categorie = :categorie", array (':categorie' => $_GET['categorie']));
}else{ // sinon si categorie n'est pas dans l'URL (j'arrive pour la premiére fois) ou que l'on a choisi "toutes les categories", on séléctionne tous les produits
    $resultat = executeRequete("SELECT id_produit, reference, titre, photo, prix, description FROM produit ");
}

while($produit = $resultat ->fetch(PDO::FETCH_ASSOC)){ // on fait une boucle car il ya plusieurs produits
    // debug($produit);

    $contenu_droite .= '<div class="col-md-4 mb-4">';
        $contenu_droite .= '<div class="card">';
            // image cliquable
            $contenu_droite .= '<a href="detail_produit.php?id_produit='. $produit['id_produit'].'"><img class="card-img-top" src="'. $produit['photo'] .'" alt="'.$produit['titre'].'"></a>'; // On envoie à la page detaiml_produit.php l'id_produit par l'URL.

            // infos produoit
            $contenu_droite .= '<div class ="card-body">';
                $contenu_droite .= '<h4>' . $produit['titre'] . '</h4>';
                $contenu_droite .= '<h4>' . $produit['prix'] . '€TTC</h4>';

                if(strlen($produit['description']) > 20){ // Si la longeur est supérieur à 20, on coupe : 
                    $contenu_droite .= '<p>' .substr($produit['description'], 0, 20)  .'...</p>'; // Couper la description à partir de 20 caractéres et ajouter des "..."
                }else {
                    $contenu_droite .= '<p>' . $produit['description'] .'</p>'; // Couper la description à partir de 20 caractéres et ajouter des ...
                }
                


            $contenu_droite .= '</div>'; // card-body
        $contenu_droite .= '</div>'; // card
    $contenu_droite .= '</div>';
}


require_once'inc/header.php';
?>

<h1 class="mt-4">Nos vêtements</h1>

<div class="row">

    <div class="col-md-3"> <!--pour afficher les catégories-->
        <?php echo $contenu_gauche; ?>

    </div>
    <div class="col-md-9"><!-- pour afficher les produits-->
        <div class="row">
            <?php echo $contenu_droite; ?>
        </div>
    </div>






</div> <!--.row-->



<?php
require_once'inc/footer.php';