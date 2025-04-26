<?php
	class ShiftOff implements iScreen {
		function __construct (private $Core) {}

		function open($starting) {
			$this->Core->Tanks->FTModel->FTFileSystem->open_tank($starting);
			$this->Core->Daily->open_day();
		}

		function controller () {
			if (isset($_POST['residueBefore'])) {
				$this->open[$_POST['residueBefore']];
				echo("<meta http-equiv='refresh' content='1'>");
			}
		}

		function view () {
			$this->controller();

			$this->Core->Daily->echo_last_daily();
			echo <<< END
<form action="index.php" method="post"><pre>
<p>Введите количество бензина в начале смены</p>
<input type="text" name="residueBefore" placeholder="Остаток..."> <input type="submit" name='send_residueBefore' value="Начать смену">
</pre></form><br/>
END;
			$this->Core->Stat->view();
		}
	}
?>