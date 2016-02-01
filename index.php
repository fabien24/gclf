<!DOCTYPE html>

<?php
// Connexion à la DB
$dsn = 'mysql:host=localhost;dbname=gclf;charset=UTF8';
$user = 'root';
$password = 'webforce3';
$pdo = NEW PDO($dsn,$user,$password);

require 'fonctions.php';
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>GCLF - Affiche du catalogue</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include 'html/header.html' ?>
		<section>
			<form action="catalogue.php" method="get">
				<input type="text" name="q" value="" />
				<input type="submit" value="Rechercher" />
			</form>
		</section>
		
	</body>
</html>