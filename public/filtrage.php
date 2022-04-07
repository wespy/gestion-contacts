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

    $initialQuery = 'SELECT DISTINCT * FROM prescripteurs WHERE ';
    $conditions = "";

}else{
    'SELECT * FROM prescripteurs ORDER BY id LIMIT :current_page, :record_per_page'
}
$initialQuery = 'SELECT * FROM prescripteurs WHERE ';
$conditions = '';

foreach($_POST as $key => $value) {

    if (len($conditions) == 0) {

        $condition = $condition.' ($key = $value) ';
    } else {

        $condition = $condition.' AND ($key = $value) ';
    }

}


$finalQuery = $initialQuery.$conditions


?>


<?=template_footer()?>
                                