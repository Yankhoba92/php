<?php 
require_once '../inc/init.php'; // on remonte vers le dossier parent avec ../
// 1- on vérifie que mle membre est bien admin sinon on le reditrige vers la page de coneexion :
    if(!estAdmin()){
        header('location:../connexion.php');
        exit;

    }

    // 4- Insertion du produit en BDD 
    // debug($_POST);

    if(!empty($_POST)){ // si lme formulaire a été envoyé

        // ici il faudrait mettre les conditions de contrôle du formulaire...

        $photo_bdd = ""; // Le champ "photo" est vide par défaut en BDD
        
        // 9 suite - modification de la photo :
            if(isset($_POST['photo_actuelle'])){ // si existe "photo_actuelle" dans $_POST, c'est que je suis entrain de modifier le produit que je veut remettre le chemin de la photo en BDD
                $photo_bdd = $_POST['photo_actuelle']; // alors on affecte le chemin de la photo actuelle à la variable $photo_bdd quui est inséré en BDD.

            }
        // 5- Suite traitement de la photo :
        // debug($_FILES); // $_FILES est une superblobale générée par le type="file du champ "photo" du formulaire. Le premier indie de $_files correspond au "name" de cet input. A cet indice on trouve toujours un sous-tableau avec l'indice "name" qui contient le nom du foichier en cours d'upload, l'indice "type" qui contient le type du fichier (ici image), l'indice "size" qui contient sa taille en octets.
        
        if(!empty($_FILES['photo']['name'])){// si ,'est pas vide le nom de la photo, c'est qu'un fichier esst en cours d'upload

            $nom_fichier = $_FILES['photo']['name']; // on recupére le nom du fichier

            $photo_bdd = 'photo/' . $nom_fichier; // cette variable contient le chemin relatif de l'image que l'on insére en BDD elle est dans le dossier photo et s'appelle ($nom_fichier).

            copy($_FILES['photo']['tmp_name'], '../' .$photo_bdd); // on copie le fichier photo temporaire qui est dans $_FILES['photo']['tmp_name'] vers le répertoire dont le chemin est "../photo/nom_fichier".

        }
        // 4 Suite - insertion du produit en BDD :
        
        $succes = executeRequete("REPLACE INTO produit VALUES(:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)", 
                                    array(
                                        ':id_produit'   => $_POST['id_produit'], 
                                        ':reference'    => $_POST['reference'],
                                        ':categorie'    => $_POST['categorie'],
                                        ':titre'        => $_POST['titre'],
                                        ':description'  => $_POST['description'],
                                        ':couleur'      => $_POST['couleur'],
                                        ':taille'       => $_POST['taille'],
                                        ':public'       => $_POST['public'],
                                        ':photo'        => $photo_bdd, // chemin de la photo uplodée qui est vide par défaut
                                        ':prix'         => $_POST['prix'],
                                        ':stock'        => $_POST['stock'],
                                        
                                    ));
                            
    if($succes) { // si on a reçu un objet PDOStatement c'est que la requéte a marché
        $contenu .= '<div class="alert alert-success">Le produit a été enregistré.</div>';
    }else{// sinon on a reçu false, la requéte n'a pas marché
        $contenu .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
    }



    } // fin de if (!empty($_POST))

    // 8- Modification du produit :

        //exercice : si "id_produit" est dans l'URL, alors vous sélectionnez tous les champs du produit demandé. Puis vous affichez les informations de ce produit dans un debug

        if(isset($_GET['id_produit'])){ // si id_çproduit et dans l'URL c'est qu'on a demandé la modification d'un produit
            $resultat = executeRequete(" SELECT * FROM produit WHERE id_produit =:id_produit", array(':id_produit' => $_GET['id_produit']));
            // debug($resultat->fetch(PDO::FETCH_ASSOC)); // $$produit est un tableau associatif dont on va metter les valeurs dans les champs du formulaire.
            $produit=$resultat->fetch(PDO::FETCH_ASSOC);
            debug($produit);

        }

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

// 3- Formulaire de produit 
?>
<form action="" method="post" enctype="multipart/form-data" ><!-- l'attribut enctype ="multipart/form-data" spécifie que le formulaire envoie des données binaires (fichier) et du texyte (champs du formulaire ) : permet d'uploader un fichier -->

    <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit'] ?? 0; ?>"><!--le champ caché id_produit est nécessaire pour la modification d'un produit (UPDATE) car on an besoiun de récupérer l'ID du produit modifié pour la requéte SQL "REPLACE INTO". Quand on crée un produit nouveau (INSERT) on met une valeur par défaut 0 pour que "REPLACE INTO" se comporte comme un "INSERT". -->

    <div><label for="reference">Référence</label></div>
    <div><input type="text" name="reference" id="reference" value="<?php echo $produit['reference'] ?? ''; ?>"></div>

    <div><label for="categorie">Catégorie</label></div>
    <div><input type="text" name="categorie" id="categorie" value="<?php echo $produit['categorie'] ?? ''; ?>"></div>

    <div><label for="titre">Titre</label></div>
    <div><input type="text" name="titre" id="titre" value="<?php echo $produit['titre'] ?? ''; ?>"></div>
    
    <div><label for="description">Description</label></div>
    <div><textarea name="description" id="description"><?php echo $produit['description'] ?? ''; ?></textarea></div>

    <div><label for="couleur">Couleur</label></div>
    <div><input type="text" name="couleur" id="couleur" value="<?php echo $produit['couleur'] ?? ''; ?>"></div>

    <div><label for="couleur">Taille</label></div>
    <div>
        <select name="taille" id="taille">
            <option value="S">S</option>
            <option value="M" <?php if(isset($produit['taille']) && $produit['taille'] == 'M') echo 'selected';?> >M</option>
            <option value="L" <?php if(isset($produit['taille']) && $produit['taille'] == 'L') echo 'selected';?>>L</option>
            <option value="XL"<?php if(isset($produit['taille']) && $produit['taille'] == 'XL') echo 'selected';?>>XL</option>
        </select>
    </div>

    <div><label for="public">Public</label></div>
    <div><input type="radio" name="public" id="public" value="m" checked>Masculin</div>
    <div><input type="radio" name="public" id="public" value="f"<?php if(isset($produit['public']) && $produit['public']== 'femme') echo 'checked'?>>Féminin</div>
    <div><input type="radio" name="public" id="public" value="mixte"<?php if(isset($produit['public']) && $produit['public']== 'mixte') echo 'checked'?>>Mixte</div><!-- attention le champ public est un ENUM en BDD qui n'attend que les valeurs "m", "f" ou "mixte" -->

    <div><label for="photo">Photo</label></div>
    <!-- 5- upload de  photo -->
    <input type="file" name="photo"><!-- Le type ="file" permet de remplir la superglobal $_FILES. Le name="photo" corremspond à l'indice de $_FILES['photo'] pour uploader 1 fichier, il ne faut pas oublier l'attribut enctype="multipart/form-data" sur la balise </form>. --> 

    <!-- 9- modification de la photo : Il nous faut mettre la valeur du champ photo du produit dans le formulaire, pour le renvoyer en BDD à la place de string vide par défaut contenu dans $photo_bdd-->
    <?php
        if(isset($produit['photo'])){ // si existe $produit ['photo'] c'est que nous sommes entrain de mofifier le produit
            echo'<div>Photo actuelle du produit</div>';
            echo'<img src="../'. $produit['photo'].'"style="width:90px"></div>'; // On affiche la photo actuelle dont le chemin et dans le champ photo de la BDD donc dans $produit. 
            echo '<input type="hidden" name="photo_actuelle" value="' . $produit['photo'] .'"  >'; // on crée se champ caché pour remettre le chemin de la photo acturlle dqns le formiulaire, donc dans$_POST à l'indice "photo_actuelle". Ainsi on ré-insére ce chemin en BDD lors de la modification.

        }



    ?>

    <div><label for="prix">Prix</label></div>
    <div><input type="text" name="prix" id="prix" value="<?php echo $produit['prix'] ?? ''; ?>"></div>

    <div><label for="stock">Stock</label></div>
    <div><input type="text" name="stock" id="stock" value="<?php echo $produit['stock'] ?? ''; ?>"></div>

    <div><input type="submit" value="Enregistrer le produit" class="btn btn-info mt-4"></div>


</form>

<?php
require_once '../inc/footer.php';