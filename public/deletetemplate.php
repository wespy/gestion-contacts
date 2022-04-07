<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->


<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Check that the contact ID exists
if (isset($_GET['IdTemplate'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM templates WHERE IdTemplate = ?');
    $stmt->execute([$_GET['IdTemplate']]);
    $templates = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$templates) {
        exit('Aucun modèle d\'e-mail correspondant à cet ID !');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM templates WHERE IdTemplate = ?');
            $stmt->execute([$_GET['IdTemplate']]);
            $msg = 'Modèle d\'e-mail supprimé !';
        } else {
            // User clicked the "No" button, redirect them back to the readtemplate page
            header('Location: readtemplate.php');
            exit;
        }
    }
} else {
    exit('Aucun ID spécifié !');
}
?>

<?=template_header('Suppression')?>

<div class="content delete">
	<h2>Supprimer modèle d'e-mail #<?=$templates['IdTemplate']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php else: ?>
	<p>Etes-vous sûr de vouloir supprimer le modèle d'e-mail #<?=$templates['IdTemplate']?>?</p>
    <div class="yesno">
        <a href="deletetemplate.php?IdTemplate=<?=$templates['IdTemplate']?>&confirm=yes">Oui</a>
        <a href="deletetemplate.php?IdTemplate=<?=$templates['IdTemplate']?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>