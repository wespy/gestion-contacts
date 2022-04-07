<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->


<?php
function pdo_connect_mysql() {
    $DATABASE_HOST = '127.0.0.1';
    $DATABASE_USER = 'admindb_marine';
    $DATABASE_PASS = 'admindb';
    $DATABASE_NAME = 'MARINE_NATIONALE';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}

// Used to get the market name from a prescripteur ID
function getMarche($id) {
	$pdo = pdo_connect_mysql();
	$query = $pdo->prepare('SELECT IdMarche FROM prescripteurs WHERE id = :id;');
	$query->bindParam('id', $id, PDO::PARAM_INT);
	
	$query->execute();
	$marche = $query->fetch();
	$lemarche = $marche[0];
	
	return $lemarche;
}

// Used to check update radio buttons according to the db value
function is_checked($db_value, $radio_value){
	if($db_value == $radio_value){
	  return "checked";
	}
	else{
	  return "";
	}
}


function template_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>Prescripteurs CIRFA Lyon</h1>
            <a href="index.php"><i class="fas fa-home"></i>Accueil</a>
    		<a href="read.php"><i class="fas fa-address-book"></i>Prescripteurs</a>
			<a href="readlist.php"><i class="fas fa-mail-bulk"></i>Mailing lists</a>
			<a href="readtemplate.php"><i class="fas fa-envelope-open-text"></i>Modèles d'e-mails</a>
			<a href="template.php"><i class="fas fa-envelope-open-text"></i>Templates d'e-mails</a>
			<a href="test.php"><i class="fas fa-file-upload"></i>Import de fichier Excel</a>
			<a href="read_stat.php"><i class="fas fa-digital-tachograph"></i>Statistiques candidats</a>
			
    	</div>
    </nav>
EOT;
}
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>