<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Check that the contact ID exists
if (isset($_GET['IdListe'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM mailing_lists WHERE IdListe = ?');
    $stmt->execute([$_GET['IdListe']]);
    $mailing_list = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mailing_list) {
        exit('Aucune mailing list correspondant à cet ID !');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM mailing_lists WHERE IdListe = ?');
            $stmt->execute([$_GET['IdListe']]);
            $msg = 'Mailing list supprimée !';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: readlist.php');
            exit;
        }
    }
} else {
    exit('Aucun ID spécifié !');
}
?>

<?=template_header('Suppression')?>

<div class="content delete">
	<h2>Supprimer mailing list #<?=$mailing_list['IdListe']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php else: ?>
	<p>Etes-vous sûr de vouloir supprimer la mailing list #<?=$mailing_list['IdListe']?>?</p>
    <div class="yesno">
        <a href="deletelist.php?IdListe=<?=$mailing_list['IdListe']?>&confirm=yes">Oui</a>
        <a href="deletelist.php?IdListe=<?=$mailing_list['IdListe']?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>