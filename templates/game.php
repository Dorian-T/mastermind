<?php
$title = "Dorian Tonnis - Mastermind";
$links = [
	'rel="icon" href="../../assets/favicon.ico"',
	'rel="stylesheet" type="text/css" href="css/style.css"',
	'rel="stylesheet" type="text/css" href="css/mastermind.css"'
];

$headerTitle = "Mastermind";
$headerNav = [
	'<a href="../../">Accueil</a>',
	'<div>
		<img src="../../assets/icon/help.svg" alt="bouton d\'aide">
		<ul>
			<li>Bonne couleur au mauvais endroit.</li>
			<li>Bonne couleur au bon endroit.</li>
		</ul>
	</div>'
];
?>


<?php ob_start(); ?>

<table>
	<?= $tries ?>

	<?= $form ?>
</table>

<?php $main = ob_get_clean(); ?>


<?php require_once 'templates/layout.php'; ?>
