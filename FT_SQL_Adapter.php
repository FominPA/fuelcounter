<?php 
	include_once 'UserSet.php';

	interface FTFileSystem {
		function load_current_total();
		function update_current($total);
		function load_last_prev();
		function load_list_prevs();
		function new_current();
	}

	include_once "SQLPublicRoots.php";

	class SQLFTAdapter implements FTFileSystem {

		public $TBName; // ссылка на таблицу пользователя

		function __construct (
			public $loader, 
			public $saver, 
			public $CurUserSet
		) {
			$this->TBName = $this->CurUserSet->login . 'FuelTanks';
		}

		function load_current_total() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' WHERE _finish IS NULL;';
			$stmt = $this->loader->pdo->query($sql);
			$result = $stmt->fetch();
			return $result['_total'];
		}

		function load_current_starting() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' WHERE _finish IS NULL;';
			$stmt = $this->loader->pdo->query($sql);
			$result = $stmt->fetch();
			return $result['_starting'];
		}

		function update_current($total) {
			$sql = 'UPDATE ' . $this->TBName . ' SET _total = :total WHERE _finish IS NULL;';
			$stmt = $this->saver->pdo->prepare($sql);
			$stmt->execute([':total' => $total]);
		}

		function load_last_prev() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' WHERE `_finish` IS NOT NULL ORDER BY `_start` DESC LIMIT 1;';
			$stmt = $this->loader->pdo->query($sql);
			$result = $stmt->fetch();
			return $result;
		}

		function load_list_prevs() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' ORDER BY _start DESC;';
			$stmt = $this->loader->pdo->query($sql);
			$result = $stmt->fetchAll();
			return $result; // упорядоченный массив ассоциативных массивов
		}

		function close_tank() {
			$this->saver->pdo->query('UPDATE ' . $this->TBName . ' SET _finish = default WHERE _finish IS NULL;');
		}

		function open_tank ($starting) {
			$sql = 'INSERT INTO ' . $this->TBName . ' (_total, _start, _finish, _starting) VALUES (0, default, null, :starting);';
			$stmt = $this->saver->pdo->prepare($sql);
			$stmt->execute([':starting' => $starting]);
		}

		function new_current() {
			$this->close_tank();
			$this->open_tank(330);
		}

	} $SQLFTAdapter = new SQLFTAdapter($SQLLoader, $SQLModelSaver, $CurUserSet);
?>