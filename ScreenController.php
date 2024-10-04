<?php
	include_once 'DailyData.php';
	include_once 'FT_Viewer.php';

	class ScreenController {
		function __construct (
			public $Daily,
			public $Tanks
		) {}

		function open($starting) {
			$this->Tanks->FTModel->FTFileSystem->open_tank($starting);
			$this->Daily->open_day();
		}

		function close() {
			$this->Tanks->FTModel->FTFileSystem->close_tank();
			$this->Daily->close_day($this->Daily->day);
		}
	} $ScreenControllerObj = new ScreenController($DailyDataMVC, $FTViewer);

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	interface ScreenStateI {
		function echo_controller();
		function echo_view();
		function echo_screen();
	}
?>