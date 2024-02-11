<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $title ?></title>
		<?php foreach($links as $link): ?>
			<link <?= $link ?>>
		<?php endforeach; ?>
		<?php
			if(isset($scripts)):
			foreach($scripts as $script):
		?>
			<script <?= $script ?> defer></script>
		<?php
			endforeach;
			endif;
		?>
	</head>

	<body>
		<header>
			<h1><?= $headerTitle ?></h1>
			<nav>
				<ul>
					<?php foreach($headerNav as $link): ?>
					<li><?= $link ?></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</header>

		<main>
			<?= $main ?>
		</main>

		<footer>
			<p>Dorian Tonnis - <?= date('Y'); ?></p>
		</footer>
	</body>
</html>
