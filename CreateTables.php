<?php
	include_once "SQLPublicRoots.php";
	include_once 'UserSet.php'; # $CurUserSet

	class CreateTables {
		function __construct(
			public $loader,
			public $saver,
			public $login
		) {
			$this->saver->pdo->query(
				"CREATE TABLE " . $this->login . "DailyData( _money DOUBLE, _total DOUBLE, _start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, _finish TIMESTAMP NULL DEFAULT NULL);"
			);

			$this->saver->pdo->query(
				"CREATE TABLE " . $this->login . "FuelTanks( _starting DOUBLE, _total DOUBLE, _start TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, _finish TIMESTAMP NULL DEFAULT NULL);"
			);
		}
	} $CreateTablesDo = new CreateTables($SQLLoader, $SQLModelSaver, $CurUserSet->login);
?>