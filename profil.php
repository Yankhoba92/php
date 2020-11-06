<?php
require_once 'inc/init.php';
// - vous redirigez le membre NON connecté vers la page de connexion
if(!estConnecte()){
    header('location:connexion.php'); // on fait une redirection vars connexion.php
    exit; // et on quitte le script
}


require_once 'inc/header.php';
?>
<h1 class="mt-4">Profil</h1>
<?php

// debug($_SESSION);
// Vous affichez le profil : 
// dans un <h2> Bonjour prenom nom !</h2>
?>
    <h2>Bonjour <?php echo $_SESSION ['membre'] ['prenom'] . ' ' . $_SESSION['membre']['nom']; ?>  !</h2>

    <hr>
    <h3>Vos coordonnées</h3>

    <ul>
        <li>Email : <?php echo $_SESSION['membre'] ['email']; ?> </li>
        <li>Adresse : <?php echo $_SESSION['membre'] ['adresse']; ?> </li>
        <li>Code_postal : <?php echo $_SESSION['membre'] ['code_postal']; ?> </li>
        <li>Ville : <?php echo $_SESSION['membre'] ['ville']; ?> </li>
    </ul>


<?php
// vous affichez dans une liste <ul> <li>
// email
// adresse
// code_postal
// ville
require_once 'inc/footer.php';