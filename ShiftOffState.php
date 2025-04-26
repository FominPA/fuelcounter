<?php
    include_once 'ScreenController.php';

	class ShiftOffState implements ScreenStateI {

		function __construct (public $ScreenController) {}
	    
		function echo_controller() {
            if (isset($_POST['residueBefore']) && isset($_POST['send_residueBefore'])) { 
            	$this->ScreenController->open($_POST['residueBefore']);
            	echo("<meta http-equiv='refresh' content='1'>");
            }
		}
		
		function echo_view() {
		    $this->ScreenController->Core->Daily->echo_last_daily();
			echo <<< END
<form action="index.php" method="post"><pre>
<p>Введите количество бензина в начале смены</p>
<input type="text" name="residueBefore" placeholder="Остаток..."> <input type="submit" name='send_residueBefore' value="Начать смену">
</pre></form><br/>
END;
			$this->ScreenController->Core->Stat->echo_screen();
		}

		function echo_screen() { 
			$this->echo_controller();
			$this->echo_view();
		}
	}
?>