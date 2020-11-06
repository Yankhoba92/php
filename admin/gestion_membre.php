<?php
 require_once '../inc/init.php';
/* Exercice :
 1- seul un administrateur a accés à cette page. Les autres membres seront redirigés vers connexion.php
 2- Afficher dans cette page, tous les membres inscrits dans une table HTML avec toutes les informations du membre sauf le mdp. 
 3- vous ajoutez une colonne "action"dans la quelle vous mettez un lien pour pouvoir supprimer un membre, SAUF vous même qui étes connecté. Bonus : demander la confirmation en JS.

*/


// 1-
if(!estAdmin()){
    header('location:../connexion.php');
    exit;
}

//3 - suppression des membres
if(isset($_GET['id_membre'])) { // Si on a id-membre dans l'URL, x'est qu'on a demandé sa suppression
    debug($_SESSION);

    if($_GET['id_membre'] != $_SESSION['membre']['id_membre']) { // Si l'id pass" dans l'URL est différent de l'ID présent dans la session, donc du membre connecté, c'est donc que je n'ai pas cliqué sur moi même
        // on supprime le membre :
        $resultat = executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
        if($resultat->rowCount() == 1) { // si le DELETE retourne 1 ligne, c'est quelle a bien été suprimée
            $contenu .= '<div class ="alert alert-success">Le membre a été supprimé.</div>';  
        }else{
            $contenu .= '<div class ="alert alert-danger">Le membre n\'a pu être supprimé.</div>'; 
        }

    }else{
        $contenu .= '<div class ="alert alert-danger">Le produit n\'a pu être supprimé.</div>';   
    }

   

}


 require_once '../inc/header.php';
 $resultat = executeRequete("SELECT id_membre, pseudo, nom, prenom, email, civilite, ville, code_postal, adresse, statut FROM membre");

 $contenu .= 'Nombre de membre est : ' . $resultat->rowCount();

 $contenu.= '<table class="table">';
     // Les entêtes
     $contenu .= '<tr>';
        $contenu .=  '<th>ID</th>';
         $contenu .= '<th>Pseudo</th>';
         $contenu .= '<th>Nom</th>';
         $contenu .= '<th>Prenom</th>';
         $contenu .= '<th>Email</th>';
         $contenu .= '<th>Civilite</th>';
         $contenu .= '<th>Ville</th>';
         $contenu .= '<th>Code postal</th>';
         $contenu .= '<th>Adresse</th>';
         $contenu .= '<th>Statut</th>';
         $contenu .= '<th>Action</th>';
     $contenu .= '</tr>';

     
     while ($membre = $resultat->fetch(PDO::FETCH_ASSOC)) {
        
             $contenu .= '<tr>'; 
                 foreach($membre as $indice => $information){ 
                 
                 $contenu .= '<td>' . $information . '</td>';
                }
//3- vous ajoutez une colonne "action"dans la quelle vous mettez un lien pour pouvoir supprimer un membre, SAUF vous même qui étes connecté. Bonus : demander la confirmation en JS.
                
                    $contenu.= '<td>
                <a href="?id_membre=' .$membre['id_membre'] . '" onclick=" return confirm(\'Etes-vous certain de vouloir supprimer ce produit? \');">supprimer</a></td>';
                
                // $contenu.= '<td><a href="?id_membre=' .$membre['id_membre'] . '" onclick=" return confirm        (\'Etes-vous certain de vouloir supprimer ce produit? \');" >supprimer</a></td>';
                
             $contenu .= '</tr>';
     }


 $contenu.='</table>';

 echo $contenu;
 
 require_once '../inc/footer.php';

?>