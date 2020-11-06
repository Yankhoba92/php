<?php
// Ce fichier contient toutes les fonctions et sera inclus dans toutes les pages.

function debug($variable){
    echo '<div style="border: 1px solid orange">';
        echo'<pre>';
            print_r($variable);
        echo '</pre>';
    echo '</div>';
}


//------------------------------
// Fonctions liées au membre
function estConnecte(){
    // Cette fonction indique sinl'internaute est connécté.
    
    if(isset($_SESSION['membre'])){ // si existe "membre" dans la session, c'est que l'internaute est passé par la page de connexion avec les bons identifiants, et que nous avons rempli cette session avec ses informations
        return true; // il est connecté
    }else{
        return false; // il n'est pas connecté
    }
}

function estAdmin(){
    // cette fonction indique si le membre est admin est connécté.
    if(estConnecte()&& $_SESSION['membre']['statut'] == 1){ // Si le membre est connectée
        return true;
    }else {
        return false;
    }
}


//--------------------------------------
// Fonction qui éxecute des requêtes

function executeRequete($requete, $param = array()) { // le paramètre $requete attend de recevoir une requéte SQL sous forme de string. $param attend un array avec les marqueurs associés à la valeur. ce paramétre est optionnel car on lui a affecté un array() vide par défaut.

    //Echapper les donnees de $param car elles proviennet de l'internaute :
    foreach ($param as $indice => $valeur) {
        $param[$indice] = htmlspecialchars($valeur);
    }// htmlspecialchars transforme les chevrons pour neutraliser les balises </script> et <style> (évite les faille XSS et CSS). Dans cette boucle, on prend à chaque tour la valeur du tableau $param que l'on échape et que l'on réaffecteà son emplacement d'iorigine.

    //Requéte préparée :
    global $pdo; // on accéde à la variable globale $pdo qui est définie dans init.php à l'exterieur de cette fonction.
    $resultat = $pdo -> prepare($requete); // on prépare la requéte envoyée à notre fonction 
    $succes = $resultat-> execute($param); // puis on éxécute la requéte en lui passant le tableau qui contient les marqueurs et leur valeur pour faire les bindParam(). On récupére dans ma variable $succes true si la requéte a marché, sinon false. 

    if($succes) {
        return $resultat; // si $succes contient true, donc que la requéte a marché, je retourne le résultat de ma requéte
    } else {
        return false; // si la requéte n'a pas marché on retourne false.
    }

}




