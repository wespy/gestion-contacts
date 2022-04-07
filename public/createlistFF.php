<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set('Europe/Paris');
template_header('Création mailing list');

// je sais pas pourquoi mais $stringMailKC apparaît avec un "1" à la toute fin du string. Si ce souci disparaît, il faut bidouiller les deux lignes dessous.
$stringMailsKC = $_POST['implodedMails'];
$stringMails = $rest = substr($stringMailsKC, 0, -1);
?>


<!-- Partie Formulaire de la création de mailing list par le tri de prescripteurs depuis read.php -->

<div class="content update">
	<h2>Ajout d'un prescripteur</h2>
    <form action="createlistFFconfirm.php" method="post">

        <label for="Nom">Libellé de la mailing list</label>
        <label for="Liste">Liste d'e-mails (séparés par des points virgules)</label>
        <input type="text" name="Nom" placeholder="ex : Pôle emplois de Lyon" id="Nom" required>
        <input type="textarea" name="Liste" placeholder="Mail1; Mail2; Mail3..." id="Liste" value="<?=$stringMails?>" required>
        
        <input type="hidden" name="DateCrea" value="<?=date('Y-m-d')?>" id="DateCrea">
        <input type="submit" value="Enregistrer la mailing list">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?>
    <br><br>
    <input type="button" value="Retour" onclick="history.go(-2)"></p>
    <?php endif; ?>
</div>

<?=template_footer()?>