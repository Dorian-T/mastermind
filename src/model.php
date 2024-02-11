<?php

declare(strict_types=1);

class Row {

	// VARIABLES :

	public array $try;
	public string $evaluation;


	// MAGIC METHODS :

	public function __construct(array $try, string $evaluation) {
		$this->try = $try;
		$this->evaluation = $evaluation;
	}

	public function __serialize(): array {
		return [
			'try' => $this->try,
			'evaluation' => $this->evaluation
		];
	}

	public function __unserialize(array $data): void {
		$this->try = $data['try'];
		$this->evaluation = $data['evaluation'];
	}

	// GETTERS :

	public function getTry(): string {
		$try = '';
		foreach($this->try as $color) {
			$try .= '<td>' . Mastermind::COLORS[$color] . '</td>';
		}
		return $try;
	}

	public function getEvaluation(): string {
		return '<td>' . $this->evaluation . '</td>';
	}
}

class Mastermind {

	// VARIABLES AND CONSTANTS :

	public const COLORS = ['🔴', '🟠', '🟡', '🟢', '🔵', '🟣', '🟤', '⚫', '⚪'];
	public const VICTORY = ['🥳', '👏', '🎉', ' Bien joué ! ', ' Bravo ! ', ' Félicitations ! ', '🥇', '🥈', '🥉'];
	public const DEFEAT = ['😭', '👎', '❌', ' Dommage ! ', ' Perdu ! ', ' Raté ! '];

	private bool $gameMode;		// false = classic, true = problem
	private bool $difficulty;	// false = easy, true = normal
	private int $noColumns;
	private int $noColors;
	private int $maxTries;

	private array $solution;
	private array $tries;
	private int $noFound;


	// MAGIC METHODS :

	public function __construct(bool $gameMode, array $array) {
		if($gameMode) {
			$this->gameMode = true;
			$this->difficulty = true;
			$this->noColumns = 6;
			$this->noColors = 9;
			$this->maxTries = -1;
			$this->solution = $array['solution'];
			$this->tries = [];
			foreach($array['tries'] as $try)
				$this->tries[] = new Row($try[0], $try[1]);
			self::startTimer();
		}
		else {
			$this->gameMode = false;
			$this->difficulty = $array['difficulty'] === 'easy' ? false : true;
			$this->noColumns = (int) $array['noColumns'];
			$this->noColors = (int) $array['noColors'];
			$this->maxTries = (int) $array['maxTries'];
			$this->solution = self::newSolution((int) $array['noColumns'], (int) $array['noColors']);
			$this->tries = [];
		}
		$this->noFound = 0;
	}

	public function __serialize(): array {
		return [
			'gameMode' => $this->gameMode,
			'difficulty' => $this->difficulty,
			'noColumns' => $this->noColumns,
			'noColors' => $this->noColors,
			'maxTries' => $this->maxTries,
			'solution' => $this->solution,
			'tries' => $this->tries,
			'noFound' => $this->noFound
		];
	}

	public function __unserialize(array $data): void {
		$this->gameMode = $data['gameMode'];
		$this->difficulty = $data['difficulty'];
		$this->noColumns = $data['noColumns'];
		$this->noColors = $data['noColors'];
		$this->maxTries = $data['maxTries'];
		$this->solution = $data['solution'];
		$this->tries = $data['tries'];
		$this->noFound = $data['noFound'];
	}


	// PRIVATE METHODS :

	private function addTryIp(array $try): void {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		$i = 0;
		while($ip[$i][0] != $userIp)
			$i++;
		$ip[$i][1][] = $try;
		file_put_contents('data/ip.json', json_encode($ip));
	}
	
	private function evaluate(array $try): string {
		$array = $this->evaluateArray($try);
		$evaluation = '';
		$noBlack = 0;
		if($this->difficulty) {
			$noWhite = 0;
			foreach($array as $value)
				switch($value) {
					case 1:
						$noWhite++;
						break;
						case 2:
							$noBlack++;
							break;
						}
			$evaluation = $noWhite . '⬜ - ' . $noBlack . '⬛';
		}
		else {
			foreach($array as $value)
			switch($value) {
				case 0:
						$evaluation .= '❌';
						break;
						case 1:
						$evaluation .= '⬜';
						break;
					case 2:
						$evaluation .= '⬛';
						$noBlack++;
						break;
					}
		}
		$this->noFound = $noBlack;
		return $evaluation;
	}

	private function evaluateArray(array $try): array {
		$evaluation = array_fill(0, count($this->solution), 0);
		$checked = array_fill(0, count($this->solution), true);
		foreach($try as $i => $value)
			if($value === $this->solution[$i]) {
				$evaluation[$i] = 2;
				$checked[$i] = false;
			}
		foreach($try as $i => $value) {
			if($evaluation[$i] === 0)
			foreach($this->solution as $j => $value)
			if($checked[$j] && $value === $try[$i]) {
				$evaluation[$i] = 1;
				$checked[$j] = false;
				break;
			}
		}
		return $evaluation;
	}


	// PUBLIC METHODS :

	public function getTries(): string {
		$tries = '';
		foreach($this->tries as $i => $try) {
			$tries .= '<tr><td>' . ($i+1) . '</td>';
			$tries .= $try->getEvaluation();
			$tries .= $try->getTry();
			$tries .= '</tr>';
		}
		return $tries;
	}

	public function getNoTries(): int {
		return count($this->tries);
	}

	public function getGameMode(): bool {
		return $this->gameMode;
	}

	public function getNoColumns(): int {
		return $this->noColumns;
	}

	public function getForm(): string {
		$form = '<form method="post" action="index.php?action=game"><tr><td></td><td></td>';
		for($i=0; $i<$this->noColumns; $i++) {
			$form .= '<td><select name="' . $i . '">';
			if($this->getNoTries() == 0) {
				$form .= '<option value="0" selected>' . self::COLORS[0] . '</option>';
				for($j=1; $j<$this->noColors; $j++)
					$form .= '<option value="' . $j . '">' . self::COLORS[$j] . '</option>';
			}
			else {
				for($j=0; $j<$this->noColors; $j++)
					$form .= '<option value="' . $j . '"' . ($this->tries[$this->getNoTries()-1]->try[$i] == $j ? ' selected' : '') . '>' . self::COLORS[$j] . '</option>';
			}
			$form .= '</select></td>';
		}
		$form .= '</tr><tr><td colspan="' . ($this->noColumns + 2) . '"><input type="submit" name="try" value="VERIFIER"></td></tr></form>';
		return $form;
	}

	public function getClassicVictory(): string {
		$victory = '';
		$emoji = rand(0, 2);
		$message = rand(3, 5);
		$victory .= '<td class="victory" colspan="' . ($this->noColumns + 2) . '">';
		$victory .= '<p>' . self::VICTORY[$emoji] . self::VICTORY[$message] . self::VICTORY[$emoji] . '<br>';
		$victory .= 'Vous avez gagné une partie classique ('.$this->noColumns.' colonnes - '.$this->noColors.' couleurs) en '.$this->getNoTries().' coups !</p>';
		$victory .= '<div><a href = "../">Accueil</a><a href = "./">Nouvelle partie</a></div>';
		$victory .= '</td>';
		return $victory;
	}

	public function getClassicDefeat(): string {
		$defeat = '';
		$emoji = rand(0, 2);
		$message = rand(3, 5);
		$defeat .= '<td class="defeat" colspan="' . ($this->noColumns + 2) . '">';
		$defeat .= '<p>' . self::DEFEAT[$emoji] . self::DEFEAT[$message] . self::DEFEAT[$emoji] . '<br>';
		$defeat .= 'Le nombre maximum de lignes a été atteint !<br>';
		$defeat .= 'La bonne réponse était : ';
		foreach($this->solution as $color)
			$defeat .= self::COLORS[$color];
		$defeat .= '</p>';
		$defeat .= '<div><a href = "../">Accueil</a><a href = "./">Nouvelle partie</a></div>';
		$defeat .= '</td>';
		return $defeat;
	}

	public function addTry(array $form): void {
		$try = [];
		for($i = 0; $i < $this->noColumns; $i++)
			$try[$i] = (int) $form[$i];
		$evaluation = $this->evaluate($try);
		$this->tries[] = new Row($try, $evaluation);
		if($this->gameMode)
			$this->addTryIp([$try, $evaluation]);
	}

	public function isWin(): bool {
		return $this->noFound === $this->noColumns;
	}

	public function isLoose(): bool {
		return !$this->gameMode && (count($this->tries) >= $this->maxTries);
	}

	public function getRanking(): int {
		$problem = json_decode(file_get_contents('data/problem.json'));
		for($i=4; $i>1; $i--)
			if($problem[$i][0] != null)
				if($problem[$i][1] < $this->getNoTries() || ($problem[$i][1] == $this->getNoTries() && $problem[$i][2] < self::getTotalTime()))
					return $i;
		return 1;
	}

	public function addInRanking(string $username, int $rank): void {
		$problem = json_decode(file_get_contents('data/problem.json'));
		$array = [];
		for($i=0; $i<5; $i++)
			if($i != $rank + 1)
				$array[] = $problem[$i];
			else {
				$array[] = [$username, $this->getNoTries(), self::getTotalTime()];
				$array[] = $problem[$i];
			}
			unset($array[5]);
			file_put_contents('data/problem.json', json_encode($array));
	}


	// PRIVATE STATIC METHODS :

	private static function startTimer(): void {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		foreach($ip as $i => $value)
			if($value[0] === $userIp) {
				if($value[2] === null)
					$ip[$i][2] = [time()];
				break;
			}
		file_put_contents('data/ip.json', json_encode($ip));
	}

	private static function getTotalTime(): int {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		foreach($ip as $value)
			if($value[0] === $userIp)
				return $value[2][1] - $value[2][0];
		return 0;
	}


	// PUBLIC STATIC METHODS :

	public static function newSolution(int $noColumns, int $noColors): array {
		$solution = [];
		for($i = 0; $i < $noColumns; $i++)
		$solution[$i] = rand(0, $noColors - 1);
		return $solution;
	}

	public static function getProblem(): array {
		$problem = json_decode(file_get_contents('data/problem.json'));
		if($problem[0] != date('z')) {
			$problem[0] = date('z');
			$problem[1] = self::newSolution(6, 9);
			for($i=2; $i<5; $i++)
				$problem[$i] = [null, null, null];
			file_put_contents('data/ip.json', '[]');
		}
		file_put_contents('data/problem.json', json_encode($problem));
		return $problem;
	}
	
	public static function hasAlreadyPlayed(array $solution): int {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		foreach($ip as $value)
			if($value[0] === $userIp)
				if($value[1] === null)
					return 1;
				else if($value[1][count($value[1]) - 1][0] === $solution)
					return 3;
				else
					return 2;
		return 0;
	}

	public static function addIp(): void {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		$ip[] = [$userIp, null, null];
		file_put_contents('data/ip.json', json_encode($ip));
	}

	public static function getTriesFromIp(): array {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		foreach($ip as $value)
			if($value[0] === $userIp)
				return $value[1];
		return [];
	}

	public static function stopTimer(): void {
		$ip = json_decode(file_get_contents('data/ip.json'));
		$userIp = $_SERVER['REMOTE_ADDR'];
		foreach($ip as $i => $value)
			if($value[0] === $userIp) {
				$ip[$i][2][1] = time();
				break;
			}
		file_put_contents('data/ip.json', json_encode($ip));
	}

	public static function validUsername(string $username): bool {
		$problem = json_decode(file_get_contents('data/problem.json'));
		for($i=2; $i<5; $i++)
			if($problem[$i][0] === $username)
				return false;
		return true;
	}
}



function deja_pris($t, $pseudo)
{
	for($i=2; $i<5; $i++)
	{
		if($t[$i][0] == $pseudo) return true;
	}
	return false;
}

function inserer_joueur($t, $rang, $pseudo)
{
	for($i=4; $i>$rang+1; $i--)
	{
		$t[$i][0] = $t[$i-1][0];
		$t[$i][1] = $t[$i-1][1];
	}
	$t[$rang+1][0] = $pseudo;
	$t[$rang+1][1] = $_SESSION['mastermind']['nb_coups'];
	return $t;
}