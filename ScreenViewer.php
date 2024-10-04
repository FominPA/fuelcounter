<?php
	include_once 'ShiftOnState.php';
	include_once 'ShiftOffState.php';

	class ScreenViewer implements ScreenStateI {

		public $ScreenState;
		
		function __construct(public $ScreenController) {
			if ($this->ScreenController->Tanks->FTModel->FTFileSystem->load_current_total() !== null)
				$this->ScreenState = new ShiftOnState($this->ScreenController); 
			else
				$this->ScreenState = new ShiftOffState($this->ScreenController);
		}

		function echo_controller() {	$this->ScreenState->echo_controller(); 	}
		function echo_view()	   { 	$this->ScreenState->echo_view(); 		}
		function echo_screen()     { 	$this->ScreenState->echo_screen(); 		}

	} $ScreenViewerObj = new ScreenViewer($ScreenControllerObj);
?>