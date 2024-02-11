<?php

require_once('src/model.php');

$problem = Mastermind::getProblem();

if(isset($_POST['newProblem'])) {
	switch(Mastermind::hasAlreadyPlayed($problem[1])) {
		case 0:
			Mastermind::addIp();
			if(isset($_SESSION['mastermind']))
				unset($_SESSION['mastermind']);
			$array = [
				'solution' => $problem[1],
				'tries' => []
			];
			$_SESSION['mastermind'] = serialize(new Mastermind(true, $array));
			header('Location: index.php?action=game');
			break;

		case 1:
			if(isset($_SESSION['mastermind']))
				unset($_SESSION['mastermind']);
			$array = [
				'solution' => $problem[1],
				'tries' => []
			];
			$_SESSION['mastermind'] = serialize(new Mastermind(true, $array));
			header('Location: index.php?action=game');
			break;

		case 2:
			if(isset($_SESSION['mastermind']))
				unset($_SESSION['mastermind']);
			$tries = Mastermind::getTriesFromIp();
			$array = [
				'solution' => $problem[1],
				'tries' => $tries
			];
			$_SESSION['mastermind'] = serialize(new Mastermind(true, $array));
			header('Location: index.php?action=game');
			break;

		case 3:
			$content = '<section id=problem"><p>Vous avez déjà joué aujourd\'hui. Vous pourrez de nouveau jouer au <em>probleme du jour</em> demain. En attendant faites une <em><a href="index.php?action=classic">partie classique</a></em>.</p></section>';
			require('templates/homepage.php');
			break;
	}
}
else
	require('templates/problem.php');