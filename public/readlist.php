<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->


<script>
    // Script qui permet de mettre le texte dans ListeMail de la ligne sur laquelle on se trouve dans le clipboard
    function Copy(element) 
    {
        let identifiers = element.id.split('-');
        let contenu = document.getElementById('ListeMail-'+identifiers[1]);

        
        contenu.select();
        document.execCommand('copy')
        console.log('Copied Text')

    }   
</script>

<?php
include 'functions.php';

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = 20;  



// Prepare the SQL statement and get records from our prescripteurs table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM mailing_lists ORDER BY IdListe DESC LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$mailing_lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of prescripteurs, this is so we can determine whether there should be a next and previous button
$num_mailing_lists = $pdo->query('SELECT COUNT(*) FROM mailing_lists')->fetchColumn();

?>

<?=template_header('Mailing lists')?>

<div class="content read">
	<h2>Mailing lists</h2>
	<a href="createlist.php" class="create-contact">Créer une mailing list manuellement</a>
    <a class="refresh-array" onClick="history.go(0)">Rafraîchir</a>
	<table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Libellé</td>
                <td align=center>Date de création</td>
                <td align=right>Copier la liste</td>
                <td align=right>Editer</td>
                <td align=right>Supprimer</td>
            </tr>
        </thead>
        <tbody>

            <?php for ($i = 0; $i < count($mailing_lists); ++$i): ?>
            <tr>
                <td><?=$mailing_lists[$i]['IdListe']?></td>
                <td><?=$mailing_lists[$i]['Nom']?></td>
                <td align=center><?=$mailing_lists[$i]['DateCrea']?></td>
                <td class="actions">
                    <input type="text" class="listfield" value="<?=$mailing_lists[$i]['Liste']?>" id="ListeMail-<?php echo $i?>" readonly=1>
                    <button onclick="Copy(this)" id="Copy-<?php echo $i?>">Copier</button>
                </td>
                <td class="actions">
                    <a href="updatelist.php?IdListe=<?=$mailing_lists[$i]['IdListe']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                </td>
                <td class="actions">
                    <a href="deletelist.php?IdListe=<?=$mailing_lists[$i]['IdListe']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
	<div class="pagination">
    <?php if ($page > 2): ?>
		<a href="readlist.php?page=<?=$page-$page+1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page > 1): ?>
		<a href="readlist.php?page=<?=$page-1?>"><i class="fas fa-angle-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page*$records_per_page < $num_mailing_lists): ?>
		<a href="readlist.php?page=<?=$page+1?>"><i class="fas fa-angle-right fa-sm"></i></a>
    <?php endif; ?>
    <?php if ($page*$records_per_page < $num_mailing_lists): ?>
		<a href="readlist.php?page=<?=ceil($num_mailing_lists/$records_per_page)?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
	<?php endif; ?>
	</div>
</div>

<?=template_footer()?>

