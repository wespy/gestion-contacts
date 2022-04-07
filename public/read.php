<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->


<?php
include 'functions.php';


// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = 1000;  

// Prepare the SQL statement and get records from our prescripteurs table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM prescripteurs ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$prescripteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of prescripteurs, this is so we can determine whether there should be a next and previous button
$num_prescripteurs = $pdo->query('SELECT COUNT(*) FROM prescripteurs')->fetchColumn();

$mailsNul = "";
$mails = [];
$implodedMails = "";

// Check if POST data is not empty
if (!empty($_POST)) {

    // Post data not empty insert a new record

    // "Filters" part

    $initialQuery = "SELECT DISTINCT * FROM prescripteurs WHERE ";
    $initialMailQuery = "SELECT Mail FROM prescripteurs WHERE ";
    $conditions = "";


    foreach($_POST as $key => $value) {
        if ($value != '') {
            if (strlen($conditions) == 0) {

                $conditions = $conditions." (".$key." LIKE '%".$value."%') ";
            } else {
                
                $conditions = $conditions." AND (".$key." LIKE '%".$value."%') ";
                var_dump($value);
                
            }
        }    
    }

    $finalQuery = $pdo->prepare($initialQuery.$conditions."ORDER BY id LIMIT ".($page-1)*$records_per_page.", ".$records_per_page);
    $finalQuery->execute();
    $prescripteurs = $finalQuery->fetchAll(PDO::FETCH_ASSOC);

    $FinalMailQuery = $pdo->prepare($initialMailQuery.$conditions." ORDER BY id");
    $FinalMailQuery->execute();

    $mails = $FinalMailQuery->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($mails as $mail) {
        
        if (array_search($mail, $mails) + 1 === count($mails)){
            $implodedMails = $implodedMails.implode("",$mail);
        }else{
            $implodedMails = $implodedMails.implode("",$mail).' ; ';
        }
    }
    $buttonHidden = False;

}else{

    $initialQuery = $pdo->prepare("SELECT * FROM prescripteurs ORDER BY id LIMIT ".($page-1)*$records_per_page.", ".$records_per_page);
    $initialQuery->execute();
    $prescripteurs = $initialQuery->fetchAll(PDO::FETCH_ASSOC); 
    $buttonHidden = True;
}
?>

<?=template_header('Prescripteurs')?>

<div class="content read">
	<h2>Prescripteurs</h2>
	<a href="create.php" class="create-contact">Créer un nouveau prescripteur</a>
    <a class="refresh-array" onClick="history.go(0)">Rafraîchir</a>
    
    <form method="post" action="read.php">
        <label for="id">Numéro :</label>  
        <input type="text" name="id">
        <label for="IdMarche">ID du marché :</label>  
        <select id="IdMarche" name = "IdMarche">
            <option selected disabled value="">Choisissez un marché</option>
            <option value="1">Marché des écoles</option>
            <option value="2">Marché de l'emploi</option>
            <option value="3">Communication</option>
            <option value="4">Divers</option>
        </select>
        <label for="CIRFA">CIRFA Référent :</label>  
        <input type="text" name="CIRFA">
        <label for="Departement">Département :</label> 
        <input type="text" name="Departement">
        <label for="Nom">Nom de l'organisme :</label> 
        <input type="text" name="Nom">
        <label for="Organisme">Organisme :</label> 
        <input type="text" placeholder="" name="Organisme">
        <label for="Activite">Activité de l'organisme :</label> 
        <input type="text" name="Activite">
        <label for="Domaine">Domaine de l'organisme :</label> 
        <input type="text" name="Domaine">
        <label for="TypeScolarite">Type de scolarité :</label> 
        <input type="text" name="TypeScolarite">
        <label for="Adresse">Adresse physique :</label> 
        <input type="text" name="Adresse">
        <label for="Contact">Nom du contact :</label> 
        <input type="text" name="Contact">
        <label for="Role">Rôle du contact :</label> 
        <input type="text" name="Role">
        <button type="submit">Appliquer</button>
    </form>

    <form method='post' action='createlistFF.php'>
        <input type="text" name="implodedMails" id="implodedMails" value="<?=print($implodedMails);?>" hidden>
        <button type='submit' hidden=<?=$buttonHidden?>>Créer une mailing list à partir du tri</button>
    </form>    

	<table>
        <thead>
            <tr>
                <td align=center>Numéro</td>
                <td align=center>CIRFA</td>
                <td align=center>Département</td>
                <td align=center>Nom de l'organisme</td>
                <td align=center>Type d'organisme</td>
                <td align=center>Activité de l'organisme</td>
                <td align=center>Domaine de l'organisme</td> 
                <td align=center>Type de scolarité</td>   
                <td align=center>Population</td>            
                <td align=center>Adresse</td>
                <td align=center>Contact dans l'organisme</td>
                <td align=center>Rôle du contact</td>                
                <td align=center>Mail du contact</td>
                <td align=center>Téléphone</td>
                <td align=center>Téléphone n°2</td>
                <td align=center>Commentaire</td>
                <td align=center>Date de dernier contact</td>
                <td align=center>Source</td>
                <td align=center>Intéret pour le prescripteur</td>
                <td align=center> </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prescripteurs as $prescripteur): ?>
            <tr>
                <td><?=$prescripteur['id']?></td>
                <td><?=$prescripteur['CIRFA']?></td>
                <td><?=$prescripteur['Departement']?></td>
                <td><?=$prescripteur['Nom']?></td>
                <td><?=$prescripteur['Organisme']?></td>
                <td><?=$prescripteur['Activite']?></td>
                <td><?=$prescripteur['Domaine']?></td> 
                <td><?=$prescripteur['TypeScolarite']?></td> 
                <td><?=$prescripteur['Population']?></td>              
                <td><?=$prescripteur['Adresse']?></td>
                <td><?=$prescripteur['Contact']?></td>
                <td><?=$prescripteur['Role']?></td>                
                <td><?=$prescripteur['Mail']?></td>
                <td><?=$prescripteur['Tel']?></td>
                <td><?=$prescripteur['Tel2']?></td>
                <td><?=$prescripteur['Commentaire']?></td>
                <td><?=$prescripteur['DernierContact']?></td>
                <td><?=$prescripteur['Source']?></td>
                <td><?=$prescripteur['Interet']?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$prescripteur['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$prescripteur['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
    <?php if ($page > 2): ?>
		<a href="read.php?page=<?=$page-$page+1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page*$records_per_page < $num_prescripteurs): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-right fa-sm"></i></a>
    <?php endif; ?>
    <?php if ($page*$records_per_page < $num_prescripteurs): ?>
		<a href="read.php?page=<?=ceil($num_prescripteurs/$records_per_page)?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
	<?php endif; ?>
	</div>
</div>

<?=template_footer()?>
