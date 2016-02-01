<?php 

// Connexion à la DB
$dsn = 'mysql:host=localhost;dbname=gclf;charset=UTF8';
$user = 'root';
$password = 'webforce3';
$pdo = NEW PDO($dsn,$user,$password);

$currentPage = 1 ;
$nbFilmsParPage = 4;
$offsetPage = 0;
$searchTerms = '';

//je récupère le paramétre url "page" de type integer
if (isset($_GET['page'])){
	$currentPage = intval($_GET['page']);
	// on souhaite afficher 4 film par page

	$offsetPage=($currentPage-1)*$nbFilmsParPage;
}

// Je récupère le paramètre d'URL "q"
if (isset($_GET['q'])) {
	$searchTerms = trim($_GET['q']);
}
// Requete pour recupérer les informations
$sql = 'SELECT fil_affiche , fil_titre , fil_id , fil_synopsis FROM film ORDER BY fil_id DESC LIMIT '.$offsetPage.','.$nbFilmsParPage;
$pdostatement = $pdo->query($sql);
$lastFilms = $pdostatement->fetchAll(PDO::FETCH_ASSOC);
// J'écris ma requête dans une variable
$sql = '
	SELECT fil_id, fil_titre, fil_affiche, fil_id, fil_annee, fil_synopsis
	FROM film
';
// Je teste que la query (q) n'est pas vide
$rechercheEnCours = false;
if (!empty($searchTerms)) {
	$rechercheEnCours = true;
	$sql .= '
		WHERE fil_titre LIKE :terms
		OR fil_synopsis LIKE :terms
		OR fil_acteurs LIKE :terms
	';
}
$sql .= '
	ORDER BY fil_id DESC
	LIMIT '.$offsetPage.', '.$nbFilmsParPage.'
';
// Je prépare ma requête à MySQL et je récupère le Statement
$pdoStatement = $pdo->prepare($sql);
if ($rechercheEnCours) {
	$pdoStatement->bindValue(':terms', '%'.$searchTerms.'%');
}

// Si la requête a fonctionnée
if ($pdoStatement->execute()) {
	$lastFilms = $pdoStatement->fetchAll();
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
		<article>
			<?php
			if (isset($lastFilms)&& sizeof($lastFilms)> 0 ){ 
				foreach ($lastFilms as $info) : ?>
					<section class="info">
						<div class="form">
							<a href="<?php echo "details.php?id=".$info['fil_id'] ;?>">Détails</a>
							<a href="<?php echo "form_film.php?id=".$info['fil_id'] ;?>">Modifier</a>
						</div>
							<img src="<?php echo $info['fil_affiche'] ;?>">
						<div class="contenu">
							<span class="filmId">#<?php echo $info['fil_id'] ;?></span>
							<a href="<?php echo "details.php?id=".$info['fil_id'] ;?>" class="filmTitre"><?php echo $info['fil_titre'] ;?></a>
							<p class="synopsis"><?php echo $info['fil_synopsis'] ;?></p>
						</div>
					</section>
				<?php endforeach ;
			}?>
		</article>
		<article class="pagination">
			<?php if ($currentPage > 1){ ?>
				<a href="?page=<?php echo $currentPage-1; ?>&q=<?php echo $searchTerms; ?>">&lt; précédent</a>
			<?php }?>
			&nbsp; &nbsp;<span>Page <?php echo $currentPage ?></span>&nbsp; &nbsp;
			<a href="?page=<?php echo $currentPage+1; ?>&q=<?php echo $searchTerms; ?>">suivant &gt;</a>
		</article>
	</body>
</html>