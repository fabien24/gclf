<?php 

// Connexion à la DB
$dsn = 'mysql:host=localhost;dbname=gclf;charset=UTF8';
$user = 'root';
$password = 'webforce3';
$pdo = NEW PDO($dsn,$user,$password);

$movTitre = '';
$movDescription = '';
$movActeurs = '';
$movFichier =''; 
$movAffiche = '';
$movAnnee = '';
//je récupère le paramétre url "ID" de type integer
if (isset($_GET['id'])){
	$filmId = intval($_GET['id']);
	//Connection à la DB avec récupération des données inscrit pour cette ID
	$sql = 'SELECT * FROM film WHERE fil_id='.$filmId;
	$pdoStatement = $pdo->query($sql);
	$listValeur = $pdoStatement->FETCH(PDO::FETCH_ASSOC);
	$movTitre = $listValeur['fil_titre'];
	$movDescription = $listValeur['fil_description'];
	$movActeurs = $listValeur['fil_acteurs'];
	$movFichier = $listValeur['fil_filename'];
	$movAffiche = $listValeur['fil_affiche'];
	$movAnnee = $listValeur['fil_annee'];	
}


?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>GCLF - Affiche du catalogue</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
	<?php include 'html/header.html' ?>
		<main>
			<section id="section">
				<article id="left">
					<img src="<?php echo $movAffiche ?>">
					<p id="dateSortie"><?php echo $movAnnee  ?></p>
					<p id="support"><?php echo 'à faire avec un joint'  ?></p>
				</article>
				<article id="right">
					<span id="categorie"><?php echo 'à faire avec un joint'   ?></span>
					<a id="titre" href=""><?php echo $movTitre  ?></a>
					<p id="description"><?php echo $movDescription  ?></p>
					<p id="acteur"><?php echo $movActeurs  ?></p>
					<p id="fichier"><?php echo $movFichier  ?></p>
				</article>
			</section>
		</main>
	</body>
</html>