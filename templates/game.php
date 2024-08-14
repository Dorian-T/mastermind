<?php
$title = "Dorian Tonnis - Mastermind";
$links = [
	'rel="icon" href="../../assets/favicon.ico"',
	'rel="stylesheet" type="text/css" href="css/style.css"',
	'rel="stylesheet" type="text/css" href="css/mastermind.css"'
];

$headerTitle = "Mastermind";

// Help button
ob_start();
?>

<div>
	<input type="checkbox" id="helpButton">
	<label for="helpButton">
		<img src="../../assets/icon/help.svg" alt="bouton d\'aide" title="Besoin d'aide ?">
	</label>
	<div>
		<ul>
			<li>Bonne couleur au mauvais endroit.</li>
			<li>Bonne couleur au bon endroit.</li>
		</ul>
		<label for="helpButton">
			<img src="../../assets/icon/x.svg" alt="croix">
		</label>
	</div>
</div>

<?php
$helpButton = ob_get_clean();
$headerNav = [
	'<a href="../../">Accueil</a>',
	$helpButton
];
?>
$headerNav = [
	'<a href="../../">Accueil</a>',
	''
];
?>


<?php ob_start(); ?>

<table>
	<?= $tries ?>

	<?= $form ?>
</table>

<?php $main = ob_get_clean(); ?>


<?php require_once 'templates/layout.php'; ?>
