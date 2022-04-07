<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');

// Check if the contact id exists, for example update.php? id=1 will get the contact with the id of 1
if (isset($_GET['IdTemplate'])) {
    if (!empty($_POST)) {

        // This part is similar to the create.php, but instead we update a record and not insert
        $idTemplate = $_GET['IdTemplate'];
        $nomTemplate = $_POST['NomTemplate'];
        $texteMail = $_POST['TexteMail'];

        // Update query preparation
        $stmt = $pdo->prepare('UPDATE templates SET NomTemplate = :nomTemplate, TexteMail = :texteMail WHERE IdTemplate = :idTemplate;');

        
        $stmt->bindParam('nomTemplate', $nomTemplate, PDO::PARAM_STR);
        $stmt->bindParam('texteMail', $texteMail, PDO::PARAM_STR);
        $stmt->bindParam('idTemplate', $idTemplate, PDO::PARAM_INT);

        // Update query execution
        $stmt->execute();

        // Confirmation message
        $msg = 'Modèle d\'e-mail modifié avec succès !';
    }

    // Get the contact from the prescripteurs table
    $stmt = $pdo->prepare('SELECT * FROM templates WHERE IdTemplate = ?');
    $stmt->execute([$_GET['IdTemplate']]);
    $templates = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$templates) 
    {
        exit('Aucun modèle existant pour cet ID !');
    }
} 
else 
{
    exit('Aucun ID spécifé !');
}
?>

<?=template_header('Modification');?>

<div class="content update">
	<h2>Modification du modèle d'e-mail #<?=$templates['IdTemplate']?></h2>
    <form action="updatetemplate.php?IdTemplate=<?=$templates['IdTemplate']?>" method="post">

        <label for="NomTemplate">Libellé du modèle d'e-mail</label>
        <label></label>
        <input type="text" style='width: 100%' name="NomTemplate" placeholder="ex : Informer les écoles de présence sur salon" value="<?=$templates['NomTemplate']?>" id="NomTemplate" required>
        
        <label></label>
        <label></label>
       
        <label for="TexteMail">Modèle d'e-mail</label>
        <label></label>
        <textarea name="TexteMail" id="TexteMail" rows="30" cols="100%" spellcheck="false" required><?=$templates['TexteMail']?></textarea>
        
        <input type="hidden" name="DateCrea" value="<?=date('Y-m-d')?>" id="DateCrea">
        <input type="submit" style='width: 250px' value="Modifier le modèle d'e-mail">

    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>