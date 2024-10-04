<?php
	include_once "SQLPublicRoots.php";

	class CreateTables {
		function __construct(
			public $loader,
			public $saver,
			public $login
		) {
			$this->saver->pdo->query(
				"CREATE TABLE " . $this->login . "DailyData( _money DOUBLE, _total DOUBLE, _start TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, _finish TIMESTAMP DEFAULT CURRENT_TIMESTAMP);"
			);

			$this->saver->pdo->query(
				"CREATE TABLE " . $this->login . "FuelTanks( _starting DOUBLE, _total DOUBLE, _start TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, _finish TIMESTAMP DEFAULT CURRENT_TIMESTAMP);"
			);
		}
	} $CreateTablesDo = new CreateTables($SQLLoader, $SQLModelSaver, '');
?>