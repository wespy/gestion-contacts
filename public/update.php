<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');
$lemarche = getMarche([$_GET['id']]);

// Check if the contact id exists, for example update.php? id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {

        // This part is similar to the create.php, but instead we update a record and not insert
        $id = $_GET['id'];
        $idMarche = isset($_POST['IdMarche']) ? $_POST['IdMarche'] : '';
        $cirfa = isset($_POST['CIRFA']) ? $_POST['CIRFA'] : '';
        $departement = isset($_POST['Departement']) ? $_POST['Departement'] : '';
        $nom = isset($_POST['Nom']) ? $_POST['Nom'] : '';
        $organisme = isset($_POST['Organisme']) ? $_POST['Organisme'] : '';
        $activite = isset($_POST['Activite']) ? $_POST['Activite'] : '';
        $domaine = isset($_POST['Domaine']) ? $_POST['Domaine'] : '';
        $typeScolarite = isset($_POST['TypeScolarite']) ? $_POST['TypeScolarite'] : '';
        $population = intval($_POST['Population']);
        $adresse = isset($_POST['Adresse']) ? $_POST['Adresse'] : '';
        $contact = isset($_POST['Contact']) ? $_POST['Contact'] : '';
        $role = isset($_POST['Role']) ? $_POST['Role'] : '';
        $mail = isset($_POST['Mail']) ? $_POST['Mail'] : '';
        $tel = isset($_POST['Tel']) ? $_POST['Tel'] : '';
        $tel2 = isset($_POST['Tel2']) ? $_POST['Tel2'] : '';
        $commentaire = isset($_POST['Commentaire']) ? $_POST['Commentaire'] : '';
        $dernierContact = isset($_POST['DernierContact']) ? $_POST['DernierContact'] : '';
        $source = isset($_POST['Source']) ? $_POST['Source'] : '';
        $interet = intval($_POST['Interet']);
        $dateCrea = isset($_POST['DateCrea']) ? $_POST['DateCrea'] : date('Y-m-d');

        // Update query preparation
        $stmt = $pdo->prepare('UPDATE prescripteurs SET IdMarche = :idmarche, CIRFA = :cirfa, Departement = :departement, Nom = :nom, Organisme = :organisme, Activite = :activite, Domaine = :domaine, TypeScolarite = :typescolarite, Population = :population, Adresse = :adresse, Contact = :contact, Role = :role, Mail = :mail, Tel = :tel, Tel2 = :tel2, Commentaire = :commentaire, DernierContact = :derniercontact, Source = :source, Interet = :interet, DateCrea = :datecrea WHERE id = :id;');

        
        $stmt->bindParam('idmarche', $idMarche, PDO::PARAM_STR);
        $stmt->bindParam('cirfa', $cirfa, PDO::PARAM_STR);
        $stmt->bindParam('departement', $departement, PDO::PARAM_STR);
        $stmt->bindParam('nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam('organisme', $organisme, PDO::PARAM_STR);
        $stmt->bindParam('activite', $activite, PDO::PARAM_STR);
        $stmt->bindParam('domaine', $domaine, PDO::PARAM_STR);
        $stmt->bindParam('typescolarite', $typeScolarite, PDO::PARAM_STR);
        $stmt->bindParam('population', $population, PDO::PARAM_INT);
        $stmt->bindParam('adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam('contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam('role', $role, PDO::PARAM_STR);
        $stmt->bindParam('mail', $mail, PDO::PARAM_STR);
        $stmt->bindParam('tel', $tel, PDO::PARAM_STR);
        $stmt->bindParam('tel2', $tel2, PDO::PARAM_STR);
        $stmt->bindParam('commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindParam('derniercontact', $dernierContact, PDO::PARAM_STR);
        $stmt->bindParam('source', $source, PDO::PARAM_STR);
        $stmt->bindParam('interet', $interet, PDO::PARAM_INT);
        $stmt->bindParam('datecrea', $dateCrea, PDO::PARAM_STR);
        $stmt->bindParam('id', $id, PDO::PARAM_STR);

        // Update query execution
        $stmt->execute();

        // Confirmation message
        $msg = 'Prescripteur modifié avec succès !';
    }

    // Get the contact from the prescripteurs table
    $stmt = $pdo->prepare('SELECT * FROM prescripteurs WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $prescripteur = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$prescripteur) 
    {
        exit('Aucun prescripteur existant pour cet ID !');
    }
} 
else 
{
    exit('Aucun ID spécifié !');
}
?>

<?=template_header('Modification');?>

<div class="content update">
	<h2>Modification du prescripteur #<?=$prescripteur['id']?></h2>
    <form action="update.php?id=<?=$prescripteur['id']?>" method="post">

        <input type="radio" id="marcheEcoles" name="IdMarche" value="1" <?=is_checked($lemarche,1)?>>
        <label for="marcheEcoles">Marché des écoles</label>
        <input type="radio" id="marcheEmploi" name="IdMarche" value="2" <?=is_checked($lemarche,2)?>>
        <label for="marcheEmploi">Marché de l'emploi</label>
        <input type="radio" id="com" name="IdMarche" value="3" <?=is_checked($lemarche,3)?>>
        <label for="com">Communication</label>
        <input type="radio" id="divers" name="IdMarche" value="4" <?=is_checked($lemarche,4)?>>
        <label for="divers">Divers</label>

        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>

        <label for="CIRFA">CIRFA</label>
        <label for="Departement">Département</label>
        <input type="text" name="CIRFA" placeholder="CIRFA ..." value="<?=$prescripteur['CIRFA']?>" id="CIRFA" required>
        <input type="text" name="Departement" placeholder="ex : 01_Ain" value="<?=$prescripteur['Departement']?>" id="Departement">

        <label for="Nom">Nom de l'organisme</label>
        <label for="Organisme">Type d'organisme</label>
        <input type="text" name="Nom" value="<?=$prescripteur['Nom']?>" id="Nom">
        <input type="text" name="Organisme" placeholder="ex : Pôle emploi" value="<?=$prescripteur['Organisme']?>" id="Organisme">

        <label for="Activite">Activité de l'organisme</label>
        <label for="Domaine">Domaine de l'organisme</label>
        <input type="text" name="Activite" placeholder="ex : Recrutement" value="<?=$prescripteur['Activite']?>" id="Activite">
        <input type="text" name="Domaine" placeholder="ex : Nucléaire" value="<?=$prescripteur['Domaine']?>" id="Domaine">

        <label for="TypeScolarite">Type de scolarité (si école)</label>
        <label for="Population">Population</label>
        <input type="text" name="TypeScolarite" placeholder="ex : Public, Privé..." value="<?=$prescripteur['TypeScolarite']?>" id="TypeScolarite">
        <input type="number" name="Population" value="<?=$prescripteur['Population']?>" id="Population">

        <label for="Adresse">Adresse</label>
        <label for="Contact">Prénom et nom du contact</label>
        <input type="text" name="Adresse" placeholder="ex : 1 Rue des lilas, 69008 Lyon" value="<?=$prescripteur['Adresse']?>" id="Adresse">
        <input type="text" name="Contact" placeholder="ex : Pierre MARTIN" value="<?=$prescripteur['Contact']?>" id="Contact">

        <label for="Role">Role du contact</label>
        <label for="Mail">Adresse e-mail du contact</label>
        <input type="text" name="Role" placeholder="ex : Secrétaire" value="<?=$prescripteur['Role']?>" id="Role">
        <input type="text" name="Mail" value="<?=$prescripteur['Mail']?>" id="Mail">

        <label for="Tel">Téléphone</label>
        <label for="Tel2">Téléphone secondaire</label>
        <input type="text" name="Tel" value="<?=$prescripteur['Tel']?>" id="Tel">
        <input type="text" name="Tel2" value="<?=$prescripteur['Tel2']?>" id="Tel2">

        <label for="Commentaire">Commentaire</label>
        <label for="DernierContact">Dernier contact</label>
        <input type="text" name="Commentaire" value="<?=$prescripteur['Commentaire']?>" id="Commentaire">
        <input type="text" name="DernierContact" value="<?=$prescripteur['DernierContact']?>" id="DernierContact">

        <label for="Source">Source</label>
        <label for="Interet">Intérêt pour le prescripteur</label>
        <input type="text" name="Source" placeholder="ex : Salon" value="<?=$prescripteur['Source']?>" id="Source">
        <input type="number" name="Interet" placeholder="Notation de 1 à 5" value="<?=$prescripteur['Interet']?>" id="Interet" maxlength=1 >
        
        <label for="DateCrea">Date de création du contact (AAAA-MM-JJ)</label>
        <input type="date" name="DateCrea" value="<?=$prescripteur['DateCrea']?>" id="DateCrea" >
        <input type="submit" value="Modifier">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>