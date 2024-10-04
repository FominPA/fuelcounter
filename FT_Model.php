<?php
	include_once 'FT_SQL_Adapter.php';
	include_once 'DailyData.php';

	class FTModel {

		public $total, $start, $finish;

		function __construct(private $DailyDataMVC, public $FTFileSystem) {
			$this->total = $this->FTFileSystem->load_current_total();
		}

		function add($__value__) { 
			$this->total += $__value__;
			$this->FTFileSystem->update_current($this->total);
		}

		function residue($__value__) { 
			$this->total += $this->FTFileSystem->load_current_starting() - $__value__;
			$this->FTFileSystem->update_current($this->total);
		}

		function sub($__value__) { 
			$this->total -= $__value__; 
			$this->FTFileSystem->update_current($this->total);
		}

		function end_tank() {
			$this->DailyDataMVC->increase_day($this->total);
			$this->FTFileSystem->new_current();
			$this->total = 0;
		}
	} $FTModel = new FTModel($DailyDataMVC, $SQLFTAdapter);
?>