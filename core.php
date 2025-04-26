<?php
	include_once 'DailyData.php';
	include_once 'FT_Viewer.php';
	include_once 'Statistic.php';
	
	class Core {
		function __construct (
			public $Daily,
			public $Tanks,
			public $Stat,
		) {}
	} 

	$Core = new Core($DailyDataMVC, $FTViewer, $CurStat);
?>