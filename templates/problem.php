<?php ob_start(); ?>

<section id="problem">
	<h2>Classement :</h2>
	<ul>
		<li><?php if($problem[2][0] != null) echo $problem[2][0].' ('.$problem[2][1].' coups)'; else echo 'vide'; ?></li>
		<li><?php if($problem[3][0] != null) echo $problem[3][0].' ('.$problem[3][1].' coups)'; else echo 'vide'; ?></li>
		<li><?php if($problem[4][0] != null) echo $problem[4][0].' ('.$problem[4][1].' coups)'; else echo 'vide'; ?></li>
	</ul>
	<form method="post" action="index.php?action=problem">
		<input type="submit" name="newProblem" value="COMMENCER">
	</form>
</section>

<?php $content = ob_get_clean(); ?>


<?php require_once 'templates/homepage.php'; ?>
