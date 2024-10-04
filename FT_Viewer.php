<?php
	include_once 'FT_Model.php';

	class FTViewer {
		public $FULL_TANK_DEFAULT = 330;

		function __construct (public $FTModel) {}

		function echo_cur() {
			echo 'Текущий бак: ' . $this->FTModel->total . ' л.<br>';
		}

		function echo_prev() {

			if ($this->FTModel->FTFileSystem->load_last_prev()['_starting'] !== null) {

				echo 'Последний бак: ' . htmlspecialchars($this->FTModel->FTFileSystem->load_last_prev()['_total']) . ' л.<br>';

				if ($this->FTModel->FTFileSystem->load_last_prev()['_starting'] != $FULL_TANK_DEFAULT) {

					echo 'До полного бака нужно указать ' . 

					($FULL_TANK_DEFAULT - $this->FTModel->FTFileSystem->load_last_prev()['_starting'] + $this->FTModel->FTFileSystem->load_last_prev()['_total']) . 

					' л.<br>';
				}
			}
		}
	} $FTViewer = new FTViewer($FTModel);
?>