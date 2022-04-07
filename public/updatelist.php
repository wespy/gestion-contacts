<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');

// Check if the contact id exists, for example update.php? id=1 will get the contact with the id of 1
if (isset($_GET['IdListe'])) {
    if (!empty($_POST)) {

        // This part is similar to the create.php, but instead we update a record and not insert
        $idListe = $_GET['IdListe'];
        $nom = $_POST['Nom'];
        $liste = $_POST['Liste'];

        // Update query preparation
        $stmt = $pdo->prepare('UPDATE mailing_lists SET Nom = :nom, Liste = :liste WHERE IdListe = :idliste;');

        
        $stmt->bindParam('nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam('liste', $liste, PDO::PARAM_STR);
        $stmt->bindParam('idliste', $idListe, PDO::PARAM_INT);

        // Update query execution
        $stmt->execute();

        // Confirmation message
        $msg = 'Mailing list modifiée avec succès !';
    }

    // Get the contact from the prescripteurs table
    $stmt = $pdo->prepare('SELECT * FROM mailing_lists WHERE IdListe = ?');
    $stmt->execute([$_GET['IdListe']]);
    $mailing_list = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$mailing_list) 
    {
        exit('Aucune mailing liste existante pour cet ID !');
    }
} 
else 
{
    exit('Aucun ID spécifié !');
}
?>

<?=template_header('Modification');?>

<div class="content update">
	<h2>Modification de la mailing list #<?=$mailing_list['IdListe']?></h2>
    <form action="updatelist.php?IdListe=<?=$mailing_list['IdListe']?>" method="post">

        <label for="Nom">Libellé</label>
        <label for="Liste">Liste de mails</label>
        <input type="text" name="Nom" value="<?=$mailing_list['Nom']?>" id="Nom"required>
        <input type="text" name="Liste" placeholder="Mail1; Mail2; Mail3..." value="<?=$mailing_list['Liste']?>" id="Liste" required>

        <input type="submit" value="Modifier">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>