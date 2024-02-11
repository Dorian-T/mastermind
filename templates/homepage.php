<?php
$title = 'Dorian Tonnis - Mastermind';
$links = [
	'rel="icon" href="../../assets/favicon.ico"',
	'rel="stylesheet" type="text/css" href="css/style.css"',
	'rel="stylesheet" type="text/css" href="css/mastermind.css"'
];


$headerTitle = 'Mastermind';
$headerNav = [
	'<a href="../../">Accueil</a>',
	'<div><img src="../../assets/icon/help.svg" alt="bouton d\'aide"><span>Choisissez un mode de jeu.</span></div>'
];
?>


<?php ob_start(); ?>

<section id="gameMode">
	<h2>Mode de jeu</h2>
	<div>
		<a href="index.php?action=classic">CLASSIQUE</a>
		<a href="index.php?action=problem">PROBLEME</a>
	</div>
</section>

<?php if(isset($content)) echo $content; ?>

<?php $main = ob_get_clean(); ?>


<?php require_once 'templates/layout.php'; ?>
