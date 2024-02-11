<?php

require_once('src/model.php');

$mastermind = unserialize($_SESSION['mastermind']);

if(isset($_POST['try']) && !$mastermind->isWin() && !$mastermind->isLoose())
	$mastermind->addTry($_POST);

// $tries :
$tries = $mastermind->getTries();

// $form :
if($mastermind->getNoTries() > 0) {
	if(!$mastermind->getGameMode() && $mastermind->isWin()) {
		$form = $mastermind->getClassicVictory();
	}
	else if(!$mastermind->getGameMode() && $mastermind->isLoose()) {
		$form = $mastermind->getClassicDefeat();
	}
	else if($mastermind->getGameMode() && $mastermind->isWin()) {
		Mastermind::stopTimer();
		$rank = $mastermind->getRanking();
		$emoji = ($rank < 4) ? $rank + 5 : rand(0, 2);
		$message = rand(3, 5);
		$form = '<td class="victory" colspan="' . ($mastermind->getNoColumns() + 2) . '">';
		$form .= '<p>' . Mastermind::VICTORY[$emoji] . Mastermind::VICTORY[$message] . Mastermind::VICTORY[$emoji] . '<br>';
		$form .= 'Vous avez réussi le problème du jour en ' . $mastermind->getNoTries() . ' essais.</p>';
		if($rank < 4) {
			if(isset($_POST['username'])) {
				if(Mastermind::validUsername($_POST['username'])) {
					$mastermind->addInRanking($_POST['username'], $rank);
					$form .= '<p>Votre score a été enregistré.</p>';
					$form .= '<div><a href = "../">Accueil</a><a href = "./">Nouvelle partie</a></div>';
				}
				else {
					$form .= '<p>Le pseudo choisi n\'est pas valide, veuillez en choisir un autre.<br>';
					$form .= '<form method="post" action="index.php?action=game">';
					$form .= '<input type="text" name="username" placeholder="Pseudo">';
					$form .= '<input type="submit" value="VALIDER">';
					$form .= '</form></p>';
				}
			}
			else {
				$form .= '<p>Vous allez apparaitre dans le classement des meilleurs joueurs, veuillez choisir un pseudo.<br>';
				$form .= '<form method="post" action="index.php?action=game">';
				$form .= '<input type="text" name="username" placeholder="Pseudo">';
				$form .= '<input type="submit" value="VALIDER">';
				$form .= '</form></p>';
			}
		}
		else
			$form .= '<div><a href = "../">Accueil</a><a href = "./">Nouvelle partie</a></div>';
		$form .= '</td>';
	}
	else
		$form = $mastermind->getForm();
}
else
	$form = $mastermind->getForm();

require('templates/game.php');

$_SESSION['mastermind'] = serialize($mastermind);