<?php 

$dsn = 'mysql:dbname=gclf;host=localhost;charset=UTF8';
$user = 'root';
$password = 'webforce3';

// Effectuer la connexion
$pdo = new PDO($dsn, $user, $password);

$sql = '
	SELECT fil_titre, fil_affiche ,fil_id,fil_annee,fil_synopsis
	FROM film
	ORDER BY fil_id DESC
';

$pdoStatement = $pdo->query($sql);

if ($pdoStatement && $pdoStatement->rowCount() > 0){
	$filmList = $pdoStatement->fetchALL();
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
		<section class="filmlist">
			<?php  
			if (isset($filmList)&& sizeof($filmList)> 0 ){
				foreach ($filmList as $currentFilmInfos) {
			?>
			<article>
				<img src="<?php echo $currentFilmInfos['fil_affiche']; ?>"border="0">	
			</article>	
			}
			}
			?>
		</section>
	</body>
	</html>
