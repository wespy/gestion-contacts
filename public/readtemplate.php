<!--
Développé par Louis-Aymerick DREVON
Durant un stage de 2 mois 14/02/2022 - 08/04/2022 
-->


<script>
    // Script qui permet de mettre le texte dans TexteMail de la ligne sur laquelle on se trouve dans le clipboard
    function Copy(element) 
    {
        let identifiers = element.id.split('-');
        let contenu = document.getElementById('TexteMail-'+identifiers[1]);

        
        contenu.select();
        document.execCommand('copy')
        console.log('Copied Mail')

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

// Prepare the SQL statement and get records from our templates table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM templates ORDER BY IdTemplate DESC LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$templates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of templates, this is so we can determine whether there should be a next and previous button
$num_templates = $pdo->query('SELECT COUNT(*) FROM templates')->fetchColumn();

?>

<?=template_header('Modèles d\'e-mail')?>

<div class="content read">
	<h2>Modèles d'e-mails</h2>
	<a href="createtemplate.php" class="create-contact">Créer un modèle d'e-mail</a>
    <a class="refresh-array" onClick="history.go(0)">Rafraîchir</a>
	<table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Libellé</td>
                <td align=center>Date de création</td>
                <td align=right>Copier le modèle</td>
                <td align=right>Editer</td>
                <td align=right>Supprimer</td>
            </tr>
        </thead>
        <tbody>

            <?php for ($i = 0; $i < count($templates); ++$i): ?>
            <tr>
                <td><?=$templates[$i]['IdTemplate']?></td>
                <td><?=$templates[$i]['NomTemplate']?></td>
                <td align=center><?=$templates[$i]['DateCrea']?></td>
                <td class="actions">
                    <textarea class="listfield" id="TexteMail-<?php echo $i?>" readonly=1><?=$templates[$i]['TexteMail']?></textarea>
                    <button onclick="Copy(this)" id="Copy-<?php echo $i?>">Copier</button>
                </td>
                <td class="actions">
                    <a href="updatetemplate.php?IdTemplate=<?=$templates[$i]['IdTemplate']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                </td>
                <td class="actions">
                    <a href="deletetemplate.php?IdTemplate=<?=$templates[$i]['IdTemplate']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
	<div class="pagination">
    <?php if ($page > 2): ?>
		<a href="readtemplate.php?page=<?=$page-$page+1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page > 1): ?>
		<a href="readtemplate.php?page=<?=$page-1?>"><i class="fas fa-angle-left fa-sm"></i></a>
	<?php endif; ?>
	<?php if ($page*$records_per_page < $num_templates): ?>
		<a href="readtemplate.php?page=<?=$page+1?>"><i class="fas fa-angle-right fa-sm"></i></a>
    <?php endif; ?>
    <?php if ($page*$records_per_page < $num_templates): ?>
		<a href="readtemplate.php?page=<?=ceil($num_templates/$records_per_page)?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
	<?php endif; ?>
	</div>
</div>

<?=template_footer()?>

