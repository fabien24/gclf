<?php
//fonction pour le traitement 
function traitement($formName){
	if(isset($_POST[$formName])){
		$_POST[$formName]=strip_tags(trim($_POST[$formName]));
	}
}

//fonction pour l'ajout à la DB
function add(){
	global $pdo,$cat_id,$sup_id;
	$sql ='INSERT INTO 
		film (
			fil_titre,
			fil_annee,
			fil_synopsis,
			fil_description,
			fil_acteurs,
			fil_filename,
			fil_affiche,
			fil_created,
			cat_id,
			sup_id
			)
		VALUES(
			:titre,
			:annee,
			:synopsis,
			:description,
			:acteurs,
			:file,
			:affiche,
			NOW(),
			:cat_id,
			:sup_id
			)
	';
	$pdoStatement = $pdo->prepare($sql);
	$pdoStatement->bindvalue(':titre',$_POST['fil_titre'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':annee',$_POST['fil_annee'],PDO::PARAM_INT);
	$pdoStatement->bindvalue(':synopsis',$_POST['fil_synopsis'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':description',$_POST['fil_description'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':acteurs',$_POST['fil_acteurs'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':file',$_POST['fil_filename'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':affiche',$_POST['fil_affiche'],PDO::PARAM_STR);
	$pdoStatement->bindValue(':cat_id', $cat_id);
	$pdoStatement->bindValue(':sup_id', $sup_id);
	$pdoStatement->execute();
}


//Fontion pour la modification des films
function modify($id){
	global $pdo,$cat_id,$sup_id;
	$sql ='UPDATE 
		film 
		SET
			fil_titre = :titre,
			fil_annee = :annee,
			fil_synopsis = :synopsis,
			fil_description = :description,
			fil_acteurs = :acteurs,
			fil_filename = :file,
			fil_affiche = :affiche,
			fil_updated = NOW(),
			cat_id = :cat_id,
			sup_id = :sup_id
		WHERE fil_id = "'.$id.'"'
	;
	$pdoStatement = $pdo->prepare($sql);
	$pdoStatement->bindvalue(':titre',$_POST['fil_titre'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':annee',$_POST['fil_annee'],PDO::PARAM_INT);
	$pdoStatement->bindvalue(':synopsis',$_POST['fil_synopsis'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':description',$_POST['fil_description'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':acteurs',$_POST['fil_acteurs'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':file',$_POST['fil_filename'],PDO::PARAM_STR);
	$pdoStatement->bindvalue(':affiche',$_POST['fil_affiche'],PDO::PARAM_STR);
	$pdoStatement->bindValue(':cat_id', $cat_id);
	$pdoStatement->bindValue(':sup_id', $sup_id);
	$pdoStatement->execute();
}
//Plus besoin!!
/*
//Fonction pour remplir les champs 
function valeur($name){
	global $listValeur,$formSend;
	if (isset($listValeur)){
		echo $listValeur[$name];
	}else if ($formSend) {
		echo $_POST[$name];
	}	
}
*/

 ?>