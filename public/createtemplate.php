<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->

<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');

// Check if POST data is not empty
if (!empty($_POST)) {
    
    // Post data not empty insert a new record

    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $nomTemplate = isset($_POST['NomTemplate']) ? $_POST['NomTemplate'] : '';
    $texteMail = isset($_POST['TexteMail']) ? $_POST['TexteMail'] : '';
    $dateCrea = isset($_POST['DateCrea']) ? $_POST['DateCrea'] : date('Y-m-d');

    // Insert new record into the templates table
       
        $stmt = $pdo->prepare('INSERT INTO templates (NomTemplate, TexteMail, DateCrea) VALUES (:nomtemplate, :textemail, :datecrea);');
     
        $stmt->bindParam('nomtemplate', $nomTemplate, PDO::PARAM_STR);
        $stmt->bindParam('textemail', $texteMail, PDO::PARAM_STR);
        $stmt->bindParam('datecrea', $dateCrea, PDO::PARAM_STR);

        $stmt->execute();
     
    // Output message
    $msg = 'Modèle d\'e-mail ajouté avec succès !';
}
?>

<?=template_header('Création modèle d\'e-mail')?>

<div class="content update">
	<h2>Ajout d'un modèle d'e-mail</h2>
    <form action="createtemplate.php" method="post">

        <label for="NomTemplate">Libellé du modèle d'e-mail</label>
        <label></label>
        <input type="text" style='width: 100%' name="NomTemplate" placeholder="ex : Informer les écoles de présence sur salon" id="NomTemplate" required>
        
        <label></label>
        <label></label>
       

        <label for="TexteMail">Modèle d'e-mail</label>
        <label></label>
        <textarea name="TexteMail" id="TexteMail" rows="30" cols="100%" spellcheck="false" required></textarea>
        
        <input type="hidden" name="DateCrea" value="<?=date('Y-m-d')?>" id="DateCrea">
        <input type="submit" style='width: 250px' value="Enregistrer le modèle d'e-mail">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>