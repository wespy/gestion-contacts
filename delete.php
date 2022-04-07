<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->

<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM prescripteurs WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $prescripteur = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$prescripteur) {
        exit('Aucun prescripteur correspondant à cet ID !');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM prescripteurs WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Prescripteur supprimé !';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('Aucun ID spécifié !');
}
?>

<?=template_header('Supprimer')?>

<div class="content delete">
	<h2>Supprimer prescripteur #<?=$prescripteur['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php else: ?>
	<p>Etes-vous sûr de vouloir supprimer le prescripteur #<?=$prescripteur['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$prescripteur['id']?>&confirm=yes">Oui</a>
        <a href="delete.php?id=<?=$prescripteur['id']?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>