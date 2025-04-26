<?php
	class ShiftOn implements iScreen {

		function __construct (private $Core) {}

		function close() {
			$this->Core->Tanks->FTModel->FTFileSystem->close_tank();
			$this->Core->Daily->close_day();
		}

		function controller() {
				if (isset($_POST['residue'])) { $this->Core->Tanks->FTModel->residue($_POST['residue']); }
				if (isset($_POST['add'])) { $this->Core->Tanks->FTModel->add($_POST['add']); }
				if (isset($_POST['sub'])) { $this->Core->Tanks->FTModel->sub($_POST['sub']); }
				if (isset($_POST['end_tank'])) { $this->Core->Tanks->FTModel->end_tank(); }
				if (isset($_POST['end'])) { $this->close();
					echo("<meta http-equiv='refresh' content='1'>");
				}
				$this->Core->Daily->echo_driver();
				$this->Core->Daily->echo_cur_day();
				$this->Core->Daily->echo_last_daily();
				$this->Core->Tanks->echo_cur();
				$this->Core->Tanks->echo_prev();
		}

		function view() {
			$this->controller();

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
	}
?>