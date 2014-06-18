<?php 

	//Initialisation de la variable contenant les résultats
	$resultats = "";
	$nbreParametres = 2; //Nombre de paramètres à renseigner

	//Traitement de la requête
	if(isset($_POST['query']) && !empty($_POST['query'])){
		//Si l'utilisateur a entré quelque chose, on traite sa requête

		//On rend clean la requête de l'utilisateur
		$query = preg_replace("#[^a-z ?0-9]#i", "", $_POST['query']);

		if($_POST['filtre'] == "Site entier"){
			$nbreParametres = 4;
			$sql = "(SELECT id, blog_title AS title FROM blog WHERE blog_title LIKE ? OR blog_content LIKE ?) UNION (SELECT id, page_title AS title FROM pages WHERE page_title LIKE ? OR page_content LIKE ?)";
		} else if($_POST['filtre'] == "Blog") {
			$sql = "SELECT id, blog_title AS title FROM blog WHERE blog_title LIKE ? OR blog_content LIKE ?";

		} else if($_POST['filtre'] == "Pages") {
			$sql = "SELECT id, page_title AS title FROM pages WHERE page_title LIKE ? OR page_content LIKE ?";
		}


		//Connexion à la base de données
		include("includes/connect_db.php");

		$req = $db->prepare($sql);
		if($nbreParametres == 2){
			$req->execute(array('%'.$query.'%', '%'.$query.'%'));	
		} else {
			$req->execute(array('%'.$query.'%', '%'.$query.'%', '%'.$query.'%', '%'.$query.'%'));	
		}

		$count = $req->rowCount();

		if($count >= 1){
			echo "$count résultat(s) trouvé(s) pour <strong>$query</strong><hr/>";
			while($data = $req->fetch(PDO::FETCH_OBJ)){
				echo '#'.$data->id.' - Titre: '.$data->title.'<br/>';
			}
			echo '<hr/>';
		} else {
			echo "0 résultat trouvé pour <strong>$query</strong><hr/>";
		}


	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Mini moteur de recherche</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="query">Entrer votre recherche: </label>
			<input type="search" name="query" maxlength="80" size="80" id="query" /><br/>
			Rechercher au niveau de: 
			<select name="filtre">
				<option value="Site entier">Site entier</option>
				<option value="Blog">Blog</option>
				<option value="Pages">Pages</option>	
			</select><br/>
			<input type="submit" value="Rechercher">
		</form>


		<?php echo $resultats; ?>
	</body>
</html>