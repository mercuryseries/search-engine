<?php 
//Connexion à la base de données
try{
	$db = new PDO('mysql:host=localhost;dbname=tp_search', 'root', '');
} catch(PDOException $e) {
	die('Erreur : '.$e->getMessage());
}

?>