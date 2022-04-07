<?php
include "xlsx.php";
include 'functions.php';
ini_set('memory_limit', '2048M');
template_header('Import de fichier excel');
?>

<!DOCTYPE html>

<div class="content read">
	<h2>Prescripteurs</h2>

<html>
<head>
	<title>XLSx</title>
</head>
<body>
<form action="#" method="POST" enctype="multipart/form-data">
	<div class="parent-div">
</br>
</br>

	<label for="excel">Choisir le fichier excel (.xlsx)</label>
    	<button>Choisir le fichier</button>
		<input type="file" name="excel" />
		<button type="submit" name="submit">Executer la requête</button>
	</div>
</form>
</div>
<?php

if(isset($_FILES['excel']['name'])){
	$con = mysqli_connect("127.0.0.1","admindb_marine","admindb","MARINE_NATIONALE");

	if($con) {
		$excel=SimpleXLSX::parse($_FILES['excel']['tmp_name']);
		
		echo "<pre>";	
		// print_r($excel->rows(1));
		print_r($excel->dimension(2));
		print_r($excel->sheetNames());


        for ($sheet=0; $sheet < sizeof($excel -> sheetNames()) ; $sheet++) { 
			$rowcol=$excel->dimension($sheet);

			$i=0;
			$query_update="";

			if ($rowcol[0] != 1 && $rowcol[1] != 1) {
				foreach ($excel->rows($sheet) as $key => $row) {
					//print_r($row);
					$cell_content="";
					foreach ($row as $key => $cell) {
						//print_r($cell);echo "<br>";
						if ($i==0) {
							$cell_content.=$cell. " varchar(50),";
						} else {
							$cell_content.="".$cell. "|¤|";
						}
					}

					if($i==0) {
						// TODO (Bonus) Mettre des int quand c'est posssible
						// Attention: il faudra remplacer les valeurs vides du Excel par des NULL lors de l'insert
						$query="CREATE table Candidats (Civilite varchar(50), Nom varchar(50), Prenom varchar(50), Age varchar(10), CodePostal varchar(20), DateEntree date, DerniereDateModif date,RecruteurAyantAjoute varchar(200),Entite varchar(300),TypeOrigine varchar(200),Categorie varchar(100),Origine varchar(200),DernierNiveauEtude varchar(300),SecteursActivitesSouhaites varchar(300),PosteSouhaite varchar(300),Experience varchar(3),TypeContratSouhaite varchar(300),SituationActuelle varchar(200),AvancementRecherche varchar(200),Tags varchar(300),EtatAvancement varchar(200),DateAnonymisee varchar(20),NombreEmailsEnvoyes varchar(3),NombreCommentairesSaisis int, PRIMARY KEY (Nom, Prenom))CHARACTER SET utf8;";

					} else {
						$values_array = explode("|¤|", $cell_content);

						$query = generateInsertRequest($values_array);
						$query_update = generateUpdateRequest($values_array);

						echo "<br>";
					}

					executeRequest($con, $query);
					executeRequest($con, $query_update);

					$i++;
				}
			}
		}
	}
}

function generateInsertRequest($values_array) {
	$postalCode = trim($values_array[4]);
	$civilite = trim($values_array[0]);

	if (empty($postalCode)) {
		$postalCode = "NC";
	}
	if (empty($civilite)) {
		$civilite = "NC";
	}
	return "INSERT INTO Candidats values (\"{$civilite}\", \"{$values_array[1]}\", \"{$values_array[2]}\", \"{$values_array[3]}\", \"{$postalCode}\", \"{$values_array[5]}\", \"{$values_array[6]}\", \"{$values_array[7]}\", \"{$values_array[8]}\", \"{$values_array[9]}\", \"{$values_array[10]}\", \"{$values_array[11]}\", \"{$values_array[12]}\", \"{$values_array[13]}\", \"{$values_array[14]}\", \"{$values_array[15]}\", \"{$values_array[16]}\", \"{$values_array[17]}\", \"{$values_array[18]}\", \"{$values_array[19]}\", \"{$values_array[20]}\", \"{$values_array[21]}\", \"{$values_array[22]}\", {$values_array[23]});";
}

function generateUpdateRequest($values_array) {

	// TODO Sortir une fonction qui permet de générerle postalcode correct et l'utiliser dans les deux fonctions de génération des requêtes
	$postalCode = trim($values_array[4]);
	if (empty($postalCode)) {
		$postalCode = "NC";
	}
	$civilite = trim($values_array[0]);
	if (empty($civilite)){
		$civilite = "NC";
	}

	// TODO Sortir une fonction pour l'anonymous date aussi, pour la lisibilité
	$anonymousDate = trim($values_array[21]);
	if (empty($anonymousDate)) {
		$anonymousDate = "NC";
	}

	return "UPDATE Candidats SET Civilite =\"".$civilite."\", Age =\"".$values_array[3]."\", CodePostal =\"".$postalCode."\", DateEntree =\"".$values_array[5]."\", DerniereDateModif =\"".$values_array[6]."\", RecruteurAyantAjoute =\"".$values_array[7]."\", Entite =\"".$values_array[8]."\", TypeOrigine =\"".$values_array[9]."\", Categorie =\"".$values_array[10]."\", Origine =\"".$values_array[11]."\", DernierNiveauEtude =\"".$values_array[12]."\", SecteursActivitesSouhaites =\"".$values_array[13]."\", PosteSouhaite =\"".$values_array[14]."\", Experience =\"".$values_array[15]."\",TypeContratSouhaite =\"".$values_array[16]."\", SituationActuelle =\"".$values_array[17]."\",AvancementRecherche =\"".$values_array[18]."\",Tags =\"".$values_array[19]."\",EtatAvancement =\"".$values_array[20]."\", DateAnonymisee = \"{$anonymousDate}\", NombreEmailsEnvoyes = {$values_array[22]}, NombreCommentairesSaisis = {$values_array[23]} WHERE (Nom=\"".$values_array[1]."\" AND Prenom=\"".$values_array[2]."\");";
}

function sanitizePostalCode($values_array) {
	return ""; // TODO
}

function sanitizeAnonymousDate($values_array) {
	return ""; // TODO
}

function executeRequest($connection, $query) {
	echo $query;
	echo "<br>";

	if ($query != "") {
		$queryResult = mysqli_query($connection,$query);

		if (!$queryResult) {
			printf("Message d'erreur : %s\n", mysqli_error($connection));
		}
	}
}
template_footer()
?>
</body>
</html>