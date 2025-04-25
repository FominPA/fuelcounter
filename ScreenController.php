<?php
	include_once 'core.php';

	class ScreenController {
		function __construct (
			public $Core
		) {}

		function open($starting) {
			$this->Core->Tanks->FTModel->FTFileSystem->open_tank($starting);
			$this->Core->Daily->open_day();
		}

		function close() {
			$this->Core->Tanks->FTModel->FTFileSystem->close_tank();
			$this->Core->Daily->close_day();
		}

		function if_shift_on() { return $this->Core->Tanks->FTModel->FTFileSystem->load_current_total() !== null; }

	} $ScreenControllerObj = new ScreenController($Core);

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	interface ScreenStateI {
		function echo_controller();
		function echo_view();
		function echo_screen();
	}
?>