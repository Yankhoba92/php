<?php 
require_once '../inc/init.php'; // on remonte vers le dossier parent avec ../
// 1- on vérifie que mle membre est bien admin sinon on le reditrige vers la page de coneexion :
    if(!estAdmin()){
        header('location:../connexion.php');
        exit;
    }

    // 7- Suppression du produit
    // debug($_GET);
if(isset($_GET['id_produit'])) { // S'il a 'id_produit" dans $_GET donc dans l'URL

        $resultat = executeRequete("DELETE FROM produit WHERE id_produit =:id_produit", array(':id_produit' => $_GET['id_produit']));

        // debug($resultat -> rowCount());// on obtient 1 lors de la suppression d'un produit

        if($resultat->rowCount() == 1) { // si le DELETE retourne 1 ligne c'est que la requête a marché
            $contenu .= '<div class ="alert alert-success">Le produit a bien été supprimé.</div>';

        }else{ 
            $contenu .= '<div class ="alert alert-danger">Le produit n\'a pu être supprimé.</div>';
        }

    }
    





    // 6- liste des produits dans une table HTML :
    $resultat = executeRequete("SELECT * FROM produit"); // on selectionne tous les produits

    $contenu .= 'Nombre de produit dans la boutique : ' . $resultat->rowCount();

    $contenu.= '<table class="table">';
        // Les entêtes
        $contenu .= '<tr>';
            $contenu .= '<th>ID</th>';
            $contenu .= '<th>Référence</th>';
            $contenu .= '<th>Catégorie</th>';
            $contenu .= '<th>Titre</th>';
            $contenu .= '<th>Description</th>';
            $contenu .= '<th>Couleur</th>';
            $contenu .= '<th>Taille</th>';
            $contenu .= '<th>Public</th>';
            $contenu .= '<th>Photo</th>';
            $contenu .= '<th>Prix</th>';
            $contenu .= '<th>Stock</th>';
            $contenu .= '<th>Actions</th>'; // colonne pour les liens "modifier" et "supprimer"
        $contenu .= '</tr>';

        //Les lignes de produits
        // debug($resultat);
        while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // debug($produit); // puisque $produit est un tableau, on le parcourt avec une foreach :
                $contenu .= '<tr>'; // On crée 1 ligne de table par produit
                    foreach($produit as $indice => $information){ // $informations parcourt les valeurs de $produit
                        
                    if($indice == 'photo'){ // Si l'indice se trouve sur le champ "photo", on affiche une balise <img>
                        $contenu .= '<td><img src="../' . $information . '" style="width:90px"></td>'; // $information contient le chemin relatif de la photo vers le dossier "photo/" qui se trouve dasn le dossier parent. On concaténe donc "../".
                     }else{ // sinon on affiche les autres valeurs dans un <td> seul :
                        $contenu .= '<td>' . $information . '</td>';
                    }
                        
                    }

                    //on ajoute les liens "modifier" et "supprimer" :
                        $contenu.= '<td>
                                        <a href="formulaire_produit.php?id_produit='. $produit['id_produit'] .'">modifier</a> | <a href="?id_produit=' .$produit['id_produit'] . '" onclick="return confirm(\'Etes-vous certain de vouloir supprimer ce produit? \');" >supprimer</a>
                        
                                    </td>';

                $contenu .= '</tr>';
        }


    $contenu.='<table>';

require_once '../inc/header.php';
// 2- onglets de ,navigation
?>
<h1 class="mt-4">Gestion de la boutique</h1>

<ul class="nav nav-tabs">
    <li><a href="gestion_boutique.php" class="nav-link active">Liste des produits</a></li>
    <li><a href="formulaire_produit.php" class="nav-link">Formulaire produit</a></li>
</ul>


<?php
echo $contenu; // pour afficher les messages et le tableau des produits

require_once '../inc/footer.php';