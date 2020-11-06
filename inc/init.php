<?php
// Ce fichier init.php sera inclus dans tous les scripts du site (hors inclusions) pour initialiser les éléments nécessaires au fonctionnement du site.

// Connexion à la BDD "boutique"
$pdo = new PDO('mysql:host=localhost;dbname=boutique', 'root', '',
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            )
);
// Session
session_start(); // crée un fichier appelé session sur le serveur dans lequel on stck des données : celles du membre ou de son panier. si la session existe déja, on y accéde directement à l'aide de l'identifaint reçu dans un cookie depuis le navigateur de l'internaute.

// Constante qui contient le chemin du site
define('RACINE_SITE', '/PHP/08-site/'); // ic on indique le dossier dans le quelle se trouve le site à partir de "localhost". S'il n'est dans aucun dossier, on met un "/" seul. Permet de créer des chemins à partir de "localhost". Rappel le / au début du chemin caractérise un chemin absolu.

// Initialisation d'une variable pour afficher du contenu HTML : 
$contenu = ''; // On y mettra du HTML 

// Inclusion des fonctions : 
require_once'functions.php';