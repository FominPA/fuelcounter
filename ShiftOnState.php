<?php
	include_once 'ScreenController.php';

	class ShiftOnState implements ScreenStateI {

		function __construct (public $ScreenController) {}

		function echo_controller() {
				if (isset($_POST['residue']) && isset($_POST['send_residue'])) { $this->ScreenController->Tanks->FTModel->residue($_POST['residue']); }
				if (isset($_POST['add']) && isset($_POST['send_add'])) { $this->ScreenController->Tanks->FTModel->add($_POST['add']); }
				if (isset($_POST['sub']) && isset($_POST['send_sub'])) { $this->ScreenController->Tanks->FTModel->sub($_POST['sub']); }
				if (isset($_POST['end_tank'])) { $this->ScreenController->Tanks->FTModel->end_tank(); }
				if (isset($_POST['end'])) { $this->ScreenController->close();
					echo("<meta http-equiv='refresh' content='1'>");
				}
				echo "Водитель: " . $this->ScreenController->Daily->UserSet->name . "<br>";
				$this->ScreenController->Daily->echo_cur_day();
				$this->ScreenController->Daily->echo_last_daily();
				$this->ScreenController->Tanks->echo_cur();
				$this->ScreenController->Tanks->echo_prev();
		}

		function echo_view() {
			echo <<< END
<style>
	input[type="submit"] {
		width: 124px;
	}
	.ib { display: inline-block; }
	.w46px { width: 46px; }
</style>

<form action="index.php" method="post"><pre>
<input type="text" name="residue" placeholder="Остаток..."> <input type="submit" name='send_residue' value="Добавить бак">
</pre></form>
<form action="index.php" method="post"><pre>
<input type="text" name="add" placeholder="Литраж"> <input type="submit" name='send_add' value="Добавить литраж">
</pre></form>
<form action="index.php" method="post"><pre>
<input type="text" name="sub" placeholder="Корректировка"> <input type="submit" name='send_sub' value="Вычесть">
</pre></form>
<pre>
<form action="index.php" method="post" class="ib"><input type="submit" name='end_tank' value="Новый бак"></form><!-- 
 --><div class="w46px ib"></div> <!-- 
  --><form action="index.php" method="post" class="ib"><input type="submit" name='end' value="Завершить день"></form></pre>
END;
		}

		function echo_screen() { 
			$this->echo_controller();
			$this->echo_view();
		}
	}
?>