<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');

// Check if POST data is not empty
if (!empty($_POST)) {

    // Post data not empty insert a new record



// il y aura des champs de filtre qui renverront $fCirfa, $fDepartement (f comme filtre)
// Si ils sont remplis, alors on renverra une chaine $sCirfa (string) du style "CIRFA LIKE '$sCirfa' AND

if ($fCirfa != ""){
    $sCirfa = "CIRFA LIKE '$fCirfa'";
}



























    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank    
    // Check if POST variable "IdMarche" exists, if not default the value to blank, basically the same for all variables
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

    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO prescripteurs (IdMarche, CIRFA, Departement, Nom, Organisme, Activite, Domaine, TypeScolarite, Population, Adresse, Contact, Role, Mail, Tel, Tel2, Commentaire, DernierContact, Source, Interet, DateCrea) VALUES (:idmarche, :cirfa, :departement, :nom, :organisme, :activite, :domaine, :typescolarite, :population, :adresse, :contact, :role, :mail, :tel, :tel2, :commentaire, :derniercontact, :source, :interet, :datecrea);');

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

    $stmt->execute();
    
    // Output message
    $msg = 'Prescripteur ajouté avec succès !';
}
?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Ajout d'un prescripteur</h2>
    <form action="create.php" method="post">
                
        <input type="radio" id="marcheEcoles" name="IdMarche" value="1">
        <label for="marcheEcoles">Marché des écoles</label>
        <input type="radio" id="marcheEmploi" name="IdMarche" value="2">
        <label for="marcheEmploi">Marché de l'emploi</label>
        <input type="radio" id="com" name="IdMarche" value="3">
        <label for="com">Communication</label>
        <input type="radio" id="divers" name="IdMarche" value="4" checked=1>
        <label for="divers">Divers</label>

        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>
        <label> </label>

        <label for="CIRFA">CIRFA</label>
        <label for="Departement">Département</label>
        <select id="CIRFA" name = "CIRFA" class = "select-margin"  style='margin-right: 25px; width: 400px; height: 45px' required>
            <option selected disabled value="">Choisissez un CIRFA</option>
            <option value="CIRFA BESANCON">CIRFA Besançon</option>
            <option value="CIRFA CLERMONT">CIRFA Clermont-Ferrand</option>
            <option value="CIRFA DIJON">CIRFA Dijon</option>
            <option value="CIRFA GRENOBLE">CIRFA Grenoble</option>
            <option value="CIRFA LYON">CIRFA Lyon</option>
        </select>
        <input type="text" name="Departement" placeholder="ex : 01_Ain" id="Departement">

        <label for="Nom">Nom de l'organisme</label>
        <label for="Organisme">Type d'organisme</label>
        <input type="text" name="Nom" id="Nom">
        <input type="text" name="Organisme" placeholder="ex : Pôle emploi" id="Organisme">

        <label for="Activite">Activité de l'organisme</label>
        <label for="Domaine">Domaine de l'organisme</label>
        <input type="text" name="Activite" placeholder="ex : Recrutement" id="Activite">
        <input type="text" name="Domaine" placeholder="ex : Nucléaire" id="Domaine">

        <label for="TypeScolarite">Type de scolarité (si école)</label>
        <label for="Population">Population</label>
        <input type="text" name="TypeScolarite" placeholder="ex : Public, Privé..." id="TypeScolarite">
        <input type="number" name="Population" id="Population">

        <label for="Adresse">Adresse</label>
        <label for="Contact">Prénom et nom du contact</label>
        <input type="text" name="Adresse" placeholder="ex : 1 Rue des lilas, 69008 Lyon" id="Adresse">
        <input type="text" name="Contact" placeholder="ex : Pierre MARTIN" id="Contact">

        <label for="Role">Role du contact</label>
        <label for="Mail">Adresse e-mail du contact</label>
        <input type="text" name="Role" placeholder="ex : Secrétaire" id="Role">
        <input type="text" name="Mail" id="Mail">

        <label for="Tel">Téléphone</label>
        <label for="Tel2">Téléphone secondaire</label>
        <input type="text" name="Tel" id="Tel">
        <input type="text" name="Tel2" id="Tel2">

        <label for="Commentaire">Commentaire</label>
        <label for="DernierContact">Dernier contact</label>
        <input type="text" name="Commentaire" id="Commentaire">
        <input type="text" name="DernierContact" id="DernierContact">

        <label for="Source">Source</label>
        <label for="Interet">Intérêt pour le prescripteur</label>
        <input type="text" name="Source" placeholder="ex : Salon" id="Source">
        <input type="number" name="Interet" placeholder="Notation de 1 à 5" id="Interet" maxlength=1 >
        
        <label for="DateCrea">Date de création du contact</label>
        <input type="date" name="DateCrea" value="<?=date('Y-m-d')?>" id="DateCrea" >
        <input type="submit" value="Ajouter le prescripteur">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
                                