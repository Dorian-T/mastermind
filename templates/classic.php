<?php ob_start(); ?>

<section id="classic">
	<h2>Paramètres de la partie</h2>
	<form method="post" action="index.php?action=classic">
		<span>Difficulté : </span>
		<input type="radio" name="difficulty" id="easy" value="easy"><label for="easy">facile </label>
		<input type="radio" name="difficulty" id="normal" value="normal" checked><label for="normal">normal</label>
		<br>

		<label for="noColumns">Nombre de colonnes : </label>
		<input type="range" name="noColumns" id="noColumns" value="4" min="3" max="5" step="1" oninput="document.getElementById('columnsOutput').innerHTML = this.value">
		<output id="columnsOutput">4</output>
		<br>

		<label for="noColors">Nombre de couleurs : </label>
		<input type="range" name="noColors" id="noColors" value="6" min="6" max="9" step="1" oninput="document.getElementById('colorsOutput').innerHTML = this.value">
		<output id="colorsOutput">6</output>
		<br>

		<label for="maxTries">Nombre maximum de lignes : </label>
		<input type="range" name="maxTries" id="maxTries" value="10" min="1" max="20" step="1" oninput="document.getElementById('maxTriesOutput').innerHTML = this.value">
		<output id="maxTriesOutput">10</output>
		<br>

		<input type="submit" name="newClassic" value="COMMENCER">
	</form>
</section>

<?php $content = ob_get_clean(); ?>


<?php require_once 'templates/homepage.php'; ?>
