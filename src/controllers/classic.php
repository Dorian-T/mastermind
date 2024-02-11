<?php

require_once('src/model.php');

if(isset($_POST['newClassic'])) {
	if(isset($_SESSION['mastermind']))
		unset($_SESSION['mastermind']);
	$_SESSION['mastermind'] = serialize(new Mastermind(false, $_POST));
	header('Location: index.php?action=game');
}
else
	require('templates/classic.php');