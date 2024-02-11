<?php

session_start();

if (isset($_GET['action'])) {
	if($_GET['action'] === 'classic')
		require('src/controllers/classic.php');

	else if($_GET['action'] === 'problem')
		require('src/controllers/problem.php');

	else if($_GET['action'] === 'game' && isset($_SESSION['mastermind']))
		require('src/controllers/game.php');

	else
		header('Location: ./');
}
else
	require('templates/homepage.php');
