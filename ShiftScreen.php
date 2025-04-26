<?php
	include_once 'core.php';
	include_once 'iScreen.php';
	include_once 'ShiftOnState.php';
	include_once 'ShiftOffState.php';

	class ShiftScreen implements iScreen {

		function (private $Core) {}

		function is_on() { return $this->Core->Tanks->FTModel->FTFileSystem->load_current_total() !== null; }

		function view() {
			if ($this->is_on()) 
				$ShiftState = new ShiftOn($this->Core);
			else 
				$ShiftState = new ShiftOff($this->Core);
			$ShiftState->view();
		}
	} $ShiftViewer = new ShiftScreen($Core);
?>