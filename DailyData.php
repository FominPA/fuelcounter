<?php 
	include_once "SQLPublicRoots.php";
	include_once "UserSet.php";

	class DailyData {

		public $TBName; // ссылка на таблицу пользователя
		public $day; // пролив сегодня

		function __construct (
			public $SQLLoaderUser,
			public $SQLSaverUser,
			public $CurTax,
			public $UserSet
		) {
			$this->TBName = $this->UserSet->login . 'DailyData';
			$this->load_cur_day();
		}

		function close_day($day) {
			$money = $this->CurTax->calc_money($day, 'night');

			$sql = "UPDATE " . $this->TBName . " SET _money = :money , _total = :total WHERE _finish IS NULL;";
			$stmt = $this->SQLSaverUser->pdo->prepare($sql);
			$stmt->execute([':money' => $money, ':total' => $day]);

			$this->SQLSaverUser->pdo->query("UPDATE " . $this->TBName . " SET _finish = default WHERE _finish IS NULL;");
		}

		function open_day() {
			$this->SQLSaverUser->pdo->query("INSERT INTO " . $this->TBName . " (_money, _total, _start, _finish) VALUES (0, 0, default, null);");
		}

		function save_last_daily($day) {
			$this->close_day($day);
			$this->open_day();
		}

		// загрузить данные за вчера по запросу

		function load_last_daily() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' ORDER BY _start DESC;';
			$stmt = $this->SQLLoaderUser->pdo->query($sql);
			$result = $stmt->fetchAll();
			return $result['1']['_money'];
		}

		function load_last_finished_money() {
			$sql = 'SELECT * FROM ' .$this->TBName . ' WHERE `_finish` IS NOT NULL ORDER BY `_start` DESC LIMIT 1;';
			$stmt = $this->SQLLoaderUser->pdo->query($sql);
			$result = $stmt->fetch();
			return $result['_money'];
		}

		function load_cur_daily() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' ORDER BY _start DESC;';
			$stmt = $this->SQLLoaderUser->pdo->query($sql);
			$result = $stmt->fetchAll();
			return $result['0'];
		}

		// загрузить данные за сегодня

		function load_cur_day() {
			$sql = 'SELECT * FROM ' . $this->TBName . ' WHERE _finish IS NULL;';
			$stmt = $this->SQLLoaderUser->pdo->query($sql);
			$result = $stmt->fetch();
			$this->day =  $result['_total'];
		}

		// сохранить данные за сегодня

		function save_cur_day() {
			$sql = 'UPDATE ' . $this->TBName . ' SET _total=:total WHERE _finish IS NULL;';
			$stmt = $this->SQLSaverUser->pdo->prepare($sql);
			$stmt->execute([':total' => $this->day]);
		}

		function increase_day($__value__) {
			$this->day += $__value__;
			$this->save_cur_day();
		}

		function end_day() {
			$this->save_last_daily($this->day);
			$this->day = 0;
			// $this->save_cur_day();
		}

		//
		//	Future Viewer
		//

		// вывести данные за сегодня

		function echo_cur_day() {
			echo 'Слито сегодня: ' . htmlspecialchars($this->day) . '<br>';
		}  

		// вывести данные за вчера

		function echo_last_daily() {
			if ($this->load_last_finished_money() !== null) echo 'Прошлый день: ' . htmlspecialchars($this->load_last_finished_money()) . ' руб.<br>';
		}

	}  $DailyDataMVC = new DailyData($SQLLoader, $SQLModelSaver, $CurTax, $CurUserSet);
?>