<!DOCTYPE html>
	<?php
	//on inclut nos packages composer
	require_once 'vendor/autoload.php';
	//Insertion des Fonctions
	require 'fonctions.php';
	//Connection à la DB
	$pdo = NEW PDO('mysql:host=localhost;dbname=gclf;charset=UTF8','root','webforce3');
	//Déclaration des variables de vérifications
	$formSend = false;
	$titre = false;
	$cat_id = 0;
	$sup_id = 0;
	$movTitre = '';
	$movSynopsis = '';
	$movDescription = '';
	$movActeurs = '';
	$movFichier = '';
	$movAffiche = '';
	$annee = 0;
	//Récupération de l'ID de l'url
	if(isset($_GET['id'])){
		$id=intval($_GET['id']);
		//Connection à la DB avec récupération des données inscrit pour cette ID
		$sql = 'SELECT * FROM film WHERE fil_id='.$id;
		$pdoStatement = $pdo->query($sql);
		$listValeur = $pdoStatement->FETCH(PDO::FETCH_ASSOC);
		$movTitre = $listValeur['fil_titre'];
		$movSynopsis = $listValeur['fil_synopsis'];
		$movDescription = $listValeur['fil_description'];
		$movActeurs = $listValeur['fil_acteurs'];
		$movFichier = $listValeur['fil_filename'];
		$movAffiche = $listValeur['fil_affiche'];
		$cat_id = $listValeur['cat_id'];
		$sup_id = $listValeur['sup_id'];
		$annee = $listValeur['fil_annee'];
	}
	//Récupération du film dans l'url
	if(isset($_GET['movie'])){
		$_GET['movie']=strip_tags(trim($_GET['movie']));
		$test = $_GET['movie'];
		try {
			$movie = \Jleagle\Imdb\Imdb::retrieve($test);
			$movTitre = $movie->title;
			$movSynopsis = $movie->plot;
			$movDescription = $movie->plot;
			$movActeurs = $movie->actors;
			$movAffiche = $movie->poster;
			$annee = $movie->year;
		}
		catch (Exception $e){
			echo '<script> alert("Titre ou ID pas trouver"); </script>';
		}		
	}
	// Récupère toutes les catégories pour générer le menu déroulant des catégories
	$sql = '
		SELECT cat_id, cat_nom
		FROM categorie
	';
	$pdoStatement = $pdo->query($sql);
	if ($pdoStatement && $pdoStatement->rowCount() > 0) {
		$categoriesList = $pdoStatement->fetchAll();
	}
	// Récupère tous les supports pour générer le menu déroulant des supports
	$sql = '
		SELECT sup_id, sup_nom
		FROM support
	';
	$pdoStatement = $pdo->query($sql);
	if ($pdoStatement && $pdoStatement->rowCount() > 0) {
		$supportsList = $pdoStatement->fetchAll();
	}
	//Validation de la form//************************************************************************************
	if (!empty($_POST)){
		$formSend = true;
		//Traitement des données en Post
		traitement('fil_titre');
		traitement('fil_synopsis');
		traitement('fil_description');
		traitement('fil_acteurs');
		traitement('fil_filename');
		traitement('fil_affiche');
		$movTitre = $_POST['fil_titre'];
		$movSynopsis = $_POST['fil_synopsis'];
		$movDescription = $_POST['fil_description'];
		$movActeurs = $_POST['fil_acteurs'];
		$movFichier = $_POST['fil_filename'];
		$movAffiche = $_POST['fil_affiche'];
		$cat_id = isset($_POST['cat_id']) ? intval(trim($_POST['cat_id'])) : 0;
		$sup_id = isset($_POST['sup_id']) ? intval(trim($_POST['sup_id'])) : 0;		
		//Vérification si le titre et bien rempli
		if (!empty($_POST['fil_titre'])) {
			$titre = true;
		}else{
			echo '<script> alert("Le titre doit être rempli"); </script>';
		}
		//Connection à la DB
		if ($titre){
			//Test si c'est une modification ou pour ajouter
			if (!isset($_GET['id'])){
				//Ajout des données
				add();
				//Dernière ID ajouter
				$lastId= $pdo->lastInsertId();
			}else if ($listValeur['fil_id']==$id){
				modify($id);
				// SI c'est pas un ajout la last id doit devenir la ID modifier
				$lastId=$id;
			}else{//Vérification si l'ID a été changé dans l'url
				echo '<script> alert("L\'ID est invalide "); </script>';
				$formSend = false;
			}
		}


	}//*************************************************************************************************************
	?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>form_film</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
	<?php include 'html/header.html' ?>
		<main> 
			<h2>Gestion de film</h2>
			<!-- tant que le formulaire n'est pas soumis valablement le formulaire reste affiché -->
			<?php if (!$titre || !$formSend) {			?>
			<form class="remplissage" action="" method="get">
				<!-- Champ pour préremplir le formulaire -->
				<input type="text" name="movie" placeholder="Film ou ID de IMDB" />
				<input type="submit" value="Search">
			</form>
			<form class="gestion" action="" method="post">
				<!-- Titre -->
				<label>Titre :</label>
				<input type="text" name="fil_titre" value="<?php echo $movTitre ?>" /><br/>
				<!-- Année -->
				<label>Année :			</label>
				<select name="fil_annee">
				<option value="0">Choisissez</option>
					<?php //Création des années pour le select se référant à l'année actuel
					for ($i=date('Y'); $i > 1949; $i--) { ?>
						<option value="<?php echo $i; ?>"<?php echo $i==$annee ? 'selected="selected"' : '';?>><?php echo $i; ?></option>
					<?php } ?>
				</select><br/>
				<!-- Catégorie -->
				<label>Catégorie :			</label>
				<select name="cat_id">
					<option value="">Choisissez</option>
					<?php foreach ($categoriesList as $curCategorie) : ?>
						<option value="<?php echo $curCategorie['cat_id']; ?>"<?php echo $cat_id == $curCategorie['cat_id'] ? ' selected="selected"' : ''; ?>><?php echo $curCategorie['cat_nom']; ?></option>
					<?php endforeach; ?>
				</select><br/>
				<!-- Support -->
				<label>Support :			</label>
				<select name="sup_id">
					<option value="">Choisissez</option>
					<?php foreach ($supportsList as $curSupport) : ?>
						<option value="<?php echo $curSupport['sup_id']; ?>"<?php echo $sup_id == $curSupport['sup_id'] ? ' selected="selected"' : ''; ?>><?php echo $curSupport['sup_nom']; ?></option>
					<?php endforeach; ?>
				</select><br/>
				<!-- Synopsis -->
				<label>Synopsis :		</label>
				<textarea name="fil_synopsis"><?php echo $movSynopsis ?></textarea><br/>
				<!-- Description -->
				<label>Description :	</label>
				<textarea name="fil_description"><?php echo $movDescription ?></textarea><br/>
				<!-- Acteurs -->
				<label>Acteurs :		</label>
				<input type="text" name="fil_acteurs" value="<?php echo $movActeurs ?>" /><br/>
				<!-- Fichier -->
				<label>Fichier :		</label>
				<input type="text" name="fil_filename" value="<?php echo $movFichier ?>" /><br/>
				<!-- Affiche -->
				<label>Affiche :		</label>
				<input type="text" name="fil_affiche" value="<?php echo $movAffiche ?>" /><br/>
				<!-- Button de validation -->
				<center><input id="valid" type="submit" value="Valider"></center>
			</form>
			<!-- S'affiche quand le formulaire était bien soumis et l'ajout ou la modification terminé -->
			<?php ;}else if (!isset($_GET['id'])){  					?>
			<h3>Insertion effectuée</h3>
			<a href="<?php echo "form_film.php?id=".$lastId ?>">modifier</a>
			<a href="<?php echo "form_film.php" ?>">ajouter</a>
			<?php ;}else{						?>
			<h3>Modification effectuée</h3>
			<a href="<?php echo "form_film.php?id=".$lastId ?>">modifier</a>
			<a href="<?php echo "form_film.php" ?>">ajouter</a>
			<?php ;}							?>
		</main>
	</body>
</html>